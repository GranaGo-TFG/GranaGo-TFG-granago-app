<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ValidacionRetoController extends Controller
{
    public function create(Reto $reto): View
    {
        abort_if($reto->estado === 'caducado', 403, 'No se pueden subir pruebas a retos caducados.');

        return view('vistas.subir-prueba', [
            'reto' => $reto,
        ]);
    }

    public function index(): JsonResponse
    {
        $validaciones = ValidacionReto::with([
            'user:id,nombre,email',
            'reto:id,nombre,estado',
        ])->orderByDesc('id')->get();

        return response()->json($validaciones);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reto_id' => ['required', 'exists:retos,id'],
            'foto_prueba' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'estado' => ['nullable', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['nullable', 'date'],
        ]);

        $this->asegurarQuePuedeEnviar(
            (int) $data['user_id'],
            (int) $data['reto_id']
        );

        $data['foto_prueba'] = $request->file('foto_prueba')->store('validaciones', 'public');
        $data['fecha_envio'] = $data['fecha_envio'] ?? Carbon::now()->toDateTimeString();

        $validacion = DB::transaction(function () use ($data) {
            $validacion = ValidacionReto::create($data);

            $this->sumarPuntosSiCorresponde(
                $validacion,
                'pendiente',
                $data['estado'] ?? 'pendiente'
            );

            return $validacion;
        });

        return response()->json($validacion, 201);
    }

    public function storeFromView(Request $request, Reto $reto): RedirectResponse
    {
        abort_if($reto->estado === 'caducado', 403, 'No se pueden subir pruebas a retos caducados.');

        $request->validate([
            'foto_prueba' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'foto_prueba.required' => 'Selecciona una imagen para enviar la prueba.',
            'foto_prueba.image' => 'El archivo enviado debe ser una imagen valida.',
            'foto_prueba.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'foto_prueba.max' => 'La imagen no puede superar los 5 MB.',
        ]);

        $this->asegurarQuePuedeEnviar((int) $request->user()->id, (int) $reto->id);

        $rutaImagen = $request->file('foto_prueba')->store('validaciones', 'public');

        ValidacionReto::create([
            'user_id' => (int) $request->user()->id,
            'reto_id' => (int) $reto->id,
            'foto_prueba' => $rutaImagen,
            'estado' => 'pendiente',
            'fecha_envio' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('vistas.reto-detalle', $reto)
            ->with('status', 'Prueba subida correctamente. La revision queda pendiente.')
            ->with('secreto_desbloqueado', $this->secretoDelReto($reto));
    }

    public function show(ValidacionReto $validaciones_reto): JsonResponse
    {
        $validaciones_reto->load([
            'user:id,nombre,email',
            'reto:id,nombre,estado,puntos_recompensa',
            'comentarios.user:id,nombre,email',
        ]);

        return response()->json($validaciones_reto);
    }

    public function update(Request $request, ValidacionReto $validaciones_reto): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'reto_id' => ['sometimes', 'exists:retos,id'],
            'foto_prueba' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'estado' => ['sometimes', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['sometimes', 'date'],
        ]);

        if ($request->hasFile('foto_prueba')) {
            $data['foto_prueba'] = $request->file('foto_prueba')->store('validaciones', 'public');
        }

        return DB::transaction(function () use ($data, $validaciones_reto) {
            $validacion = ValidacionReto::query()
                ->lockForUpdate()
                ->findOrFail($validaciones_reto->id);

            $estadoAnterior = $validacion->estado;
            $estadoNuevo = $data['estado'] ?? $estadoAnterior;

            if ($estadoAnterior === 'verificado' && $estadoNuevo !== 'verificado') {
                return response()->json([
                    'message' => 'No se puede cambiar el estado de una validacion ya verificada.',
                ], 422);
            }

            $validacion->update($data);

            $this->sumarPuntosSiCorresponde($validacion, $estadoAnterior, $estadoNuevo);

            return response()->json($validacion->fresh());
        });
    }

    public function destroy(ValidacionReto $validaciones_reto): JsonResponse
    {
        if ($validaciones_reto->foto_prueba && ! filter_var($validaciones_reto->foto_prueba, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($validaciones_reto->foto_prueba);
        }

        $validaciones_reto->delete();

        return response()->json(null, 204);
    }

    private function sumarPuntosSiCorresponde(
        ValidacionReto $validacion,
        string $estadoAnterior,
        string $estadoNuevo
    ): void {
        if ($estadoNuevo !== 'verificado' || $estadoAnterior === 'verificado') {
            return;
        }

        $user = User::query()->lockForUpdate()->findOrFail($validacion->user_id);
        $validacion->loadMissing('reto:id,puntos_recompensa');

        $puntosBase = (int) $validacion->reto->puntos_recompensa;
        $multiplicador = max(0, (float) $user->racha_multiplicador);
        $puntosGanados = (int) round($puntosBase * $multiplicador);

        if ($puntosGanados > 0) {
            $user->increment('puntos_totales', $puntosGanados);
        }
    }

    private function asegurarQuePuedeEnviar(int $userId, int $retoId): void
    {
        $yaExiste = ValidacionReto::query()
            ->where('user_id', $userId)
            ->where('reto_id', $retoId)
            ->whereIn('estado', ['pendiente', 'verificado'])
            ->exists();

        if (! $yaExiste) {
            return;
        }

        throw ValidationException::withMessages([
            'foto_prueba' => 'Ya tienes una prueba pendiente o verificada para este reto.',
        ]);
    }

    private function secretoDelReto(Reto $reto): array
    {
        $secretos = [
            1 => [
                'titulo' => 'La postal que embruja',
                'leyenda' => 'Dicen que quien mira la Alhambra desde San Nicolas entiende por que Granada cuesta tanto abandonarla.',
                'contenido' => 'Desde este punto la fortaleza parece suspendida sobre la colina, como si no perteneciera del todo al presente. Durante siglos, viajeros, poetas y pintores han convertido esta vista en una promesa: Granada siempre guarda algo mas detras de la luz.',
                'cierre' => 'Y asi, quien al mirador llegaba, no llevaba consigo solo una imagen, sino el recuerdo de una ciudad encendida sobre la colina.',
            ],
            2 => [
                'titulo' => 'La torre que despertaba al Darro',
                'leyenda' => 'Antes de que el paseo se llenara de pasos y fotografias, el rio tambien tenia musica.',
                'contenido' => 'La Casa de las Chirimias era el lugar desde el que los musicos tocaban en fiestas y actos publicos. Imagina el sonido bajando por el Darro, rebotando entre piedra, agua y fachadas antiguas. Hoy parece callada, pero la ciudad todavia conserva esa memoria si sabes donde mirar.',
                'cierre' => 'Y aunque hoy calle la torre, aun parece que el Darro guarda en su corriente el eco de aquella musica antigua.',
            ],
            3 => [
                'titulo' => 'El rio que dividia secretos',
                'leyenda' => 'La Carrera del Darro parece tranquila, pero sus puentes han visto pasar siglos de rumores, comercio y encuentros furtivos.',
                'contenido' => 'Puentes como Cabrera o Espinosa no son solo pasos sobre el agua. Conectaban orillas, barrios y vidas en una Granada donde cada calle tenia una funcion y cada esquina podia esconder una historia. Cruzarlos hoy es pisar una version mas antigua de la ciudad.',
                'cierre' => 'Y quien cruza estos puentes no atraviesa solo el rio, sino una frontera pequena entre la Granada presente y la que aun duerme bajo la piedra.',
            ],
            4 => [
                'titulo' => 'La plaza donde pesaba la justicia',
                'leyenda' => 'Plaza Nueva no siempre fue solo un lugar para quedar. Durante siglos, tambien fue un escenario de poder.',
                'contenido' => 'La Real Chancilleria recordaba a cualquiera que cruzara la plaza que la ciudad tenia nuevos jueces, nuevas normas y una nueva autoridad. Su fachada no esta ahi para decorar: esta ahi para imponer respeto.',
                'cierre' => 'Y ante tal fachada, todo caminante entendia que alli no hablaba una casa cualquiera, sino la voz severa de la justicia.',
            ],
            5 => [
                'titulo' => 'La frase escondida en la piedra',
                'leyenda' => 'La Catedral no se mira de una vez: se lee como una pagina gigante escrita en piedra.',
                'contenido' => 'El Ave Maria sobre el arco central no esta colocado al azar. Forma parte de un mensaje visual pensado para impresionar, guiar y recordar el peso religioso de la nueva Granada cristiana. Entre columnas y relieves, la fachada habla sin necesidad de voz.',
                'cierre' => 'Y asi quedo la piedra escrita para quien supiera leerla: no con tinta, sino con fe, poder y silencio.',
            ],
            6 => [
                'titulo' => 'Las marcas del cambio',
                'leyenda' => 'En Granada, algunos simbolos no decoran: senalan quien quiso escribir la historia despues de 1492.',
                'contenido' => 'La Capilla Real esta ligada a Isabel y Fernando, y sus escudos e iniciales funcionan como huellas de poder. No son simples adornos: son una forma de decir que la ciudad habia cambiado para siempre.',
                'cierre' => 'Y quedaron aquellas senales como firma de un tiempo nuevo, para que Granada recordase que su historia habia cambiado de manos.',
            ],
            7 => [
                'titulo' => 'La ciudad bajo tus pies',
                'leyenda' => 'Desde San Miguel Alto, Granada deja de ser un laberinto y se convierte en un mapa abierto.',
                'contenido' => 'La subida tiene algo de prueba iniciatica. Cuando llegas arriba, la Alhambra, el Albaicin, el centro y la Vega aparecen conectados en una sola mirada. Es como si la ciudad revelara por fin su forma secreta.',
                'cierre' => 'Y desde aquella altura, aun parece Granada un reino extendido a los pies de quien se atreve a subir hasta verla entera.',
            ],
            8 => [
                'titulo' => 'El jardin que baja la voz',
                'leyenda' => 'Hay rincones de Granada donde el agua no decora: parece guardar silencio por algo.',
                'contenido' => 'El Carmen de los Martires mezcla estanques, jardines y caminos escondidos. No se descubre de golpe; se recorre como si cada esquina preparara otra escena. Cerca del ruido turistico, este lugar parece pertenecer a otra Granada mas lenta.',
                'cierre' => 'Y entre el murmullo del agua y la sombra de los jardines, el viajero comprende que Granada tambien sabe esconder la calma.',
            ],
            9 => [
                'titulo' => 'La huerta donde escribia el verano',
                'leyenda' => 'No todas las historias de Granada viven en palacios. Algunas se esconden en una casa familiar rodeada de verde.',
                'contenido' => 'La Huerta de San Vicente fue residencia de verano de la familia Garcia Lorca. Alli la ciudad se vuelve mas intima: menos monumento y mas memoria. Pensar en Lorca aqui es imaginar una Granada cotidiana, creativa y cercana.',
                'cierre' => 'Y en aquella huerta, lejos del marmol y las fortalezas, Granada se volvia voz, verano y memoria.',
            ],
            10 => [
                'titulo' => 'La calle donde los balcones casi se tocan',
                'leyenda' => 'Cuenta la leyenda que en esta calle dos enamorados podian besarse desde balcones opuestos sin pisar la calle.',
                'contenido' => 'Nadie sabe cuanto hay de verdad y cuanto de imaginacion, pero eso es precisamente lo que hace especial al Albaicin. Sus calles no solo se caminan: se inventan, se murmuran y se recuerdan.',
                'cierre' => 'Y desde entonces, quien pasa por esta calle mira hacia arriba, por si aun quedase algun balcon esperando otro beso.',
            ],
            11 => [
                'titulo' => 'La montana que canta por dentro',
                'leyenda' => 'En el Sacromonte, la ciudad no siempre se construye: a veces se excava, se habita y se canta.',
                'contenido' => 'Las cuevas guardan una forma de vida ligada a la ladera, al flamenco y a noches que parecen no acabar. Desde fuera pueden parecer silenciosas, pero el barrio tiene fama de conservar cada eco.',
                'cierre' => 'Y asi, bajo la tierra y sobre la ladera, el Sacromonte fue guardando voces que no se apagan con facilidad.',
            ],
            12 => [
                'titulo' => 'La pared que te devuelve la mirada',
                'leyenda' => 'En el Realejo, algunas fachadas no cierran edificios: observan a quien pasa.',
                'contenido' => 'Amelia del Realejo convierte una pared en personaje. Ese es el poder del arte urbano aqui: cambia la forma de caminar, porque de pronto una calle normal parece tener una presencia propia.',
                'cierre' => 'Y en aquel muro, la calle dejo de ser solo calle, pues la ciudad aprendio tambien a hablar con pintura.',
            ],
            13 => [
                'titulo' => 'La mano que aguarda la llave',
                'leyenda' => 'Cuenta la vieja voz de la Alhambra que, cuando la mano alcance la llave, algo grande habra de suceder.',
                'contenido' => 'En la Puerta de la Justicia, una mano aparece sobre el gran arco exterior y una llave aguarda en el interior. La leyenda dice que ambas no estan ahi por simple adorno: una guarda, la otra abre, y entre las dos se sostiene el misterio de la fortaleza roja.',
                'cierre' => 'Y asi quedo la puerta velando su secreto, con la mano alzada sobre la piedra y la llave esperando un dia que tal vez nunca llegue.',
            ],
            14 => [
                'titulo' => 'La posada de los mercaderes',
                'leyenda' => 'Hubo un tiempo en que las mercancias llegaban a Granada antes que las historias, pero ambas dormian bajo el mismo techo.',
                'contenido' => 'El Corral del Carbon fue alhondiga y lugar de paso para comerciantes. Su arco daba entrada a un patio donde se mezclaban voces, tratos, animales, cargas y noticias venidas de otros caminos.',
                'cierre' => 'Y quien cruzaba aquel arco no entraba solo en un edificio, sino en el murmullo antiguo de una Granada mercader.',
            ],
            15 => [
                'titulo' => 'El cielo pequeno del Banuelo',
                'leyenda' => 'En los banos antiguos, la luz no entraba como en las casas: caia desde arriba, partida en estrellas.',
                'contenido' => 'El Banuelo conserva la memoria de los banos arabes de Granada. En sus bovedas, pequenas aberturas con forma de estrella dejaban pasar la claridad y convertian el vapor en una escena casi secreta.',
                'cierre' => 'Y bajo aquellas estrellas de piedra, el agua, el silencio y la luz hacian del bano un refugio apartado del ruido de la ciudad.',
            ],
        ];

        return $secretos[$reto->id] ?? [
            'titulo' => 'Un eco de Granada',
            'leyenda' => 'Cada paso por Granada puede esconder una historia que no aparece en los mapas.',
            'contenido' => 'Al completar este reto, has desbloqueado una pequena parte de la ciudad. Algunas historias se leen en placas, otras en piedras, y otras aparecen cuando miras dos veces el mismo rincón.',
            'cierre' => 'Y asi, paso a paso, Granada va abriendo sus relatos a quien camina con los ojos atentos.',
        ];
    }
}
