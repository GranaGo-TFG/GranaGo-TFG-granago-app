<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->string('titulo_relato')->nullable()->after('descripcion');
            $table->text('leyenda_relato')->nullable()->after('titulo_relato');
            $table->text('contenido_relato')->nullable()->after('leyenda_relato');
            $table->text('cierre_relato')->nullable()->after('contenido_relato');
        });

        $relatos = [
            1 => [
                'titulo_relato' => 'La postal que embruja',
                'leyenda_relato' => 'Dicen que quien mira la Alhambra desde San Nicolas entiende por que Granada cuesta tanto abandonarla.',
                'contenido_relato' => 'Desde este punto la fortaleza parece suspendida sobre la colina, como si no perteneciera del todo al presente. Durante siglos, viajeros, poetas y pintores han convertido esta vista en una promesa: Granada siempre guarda algo mas detras de la luz.',
                'cierre_relato' => 'Y asi, quien al mirador llegaba, no llevaba consigo solo una imagen, sino el recuerdo de una ciudad encendida sobre la colina.',
            ],
            2 => [
                'titulo_relato' => 'La torre que despertaba al Darro',
                'leyenda_relato' => 'Antes de que el paseo se llenara de pasos y fotografias, el rio tambien tenia musica.',
                'contenido_relato' => 'La Casa de las Chirimias era el lugar desde el que los musicos tocaban en fiestas y actos publicos. Imagina el sonido bajando por el Darro, rebotando entre piedra, agua y fachadas antiguas. Hoy parece callada, pero la ciudad todavia conserva esa memoria si sabes donde mirar.',
                'cierre_relato' => 'Y aunque hoy calle la torre, aun parece que el Darro guarda en su corriente el eco de aquella musica antigua.',
            ],
            3 => [
                'titulo_relato' => 'El rio que dividia secretos',
                'leyenda_relato' => 'La Carrera del Darro parece tranquila, pero sus puentes han visto pasar siglos de rumores, comercio y encuentros furtivos.',
                'contenido_relato' => 'Puentes como Cabrera o Espinosa no son solo pasos sobre el agua. Conectaban orillas, barrios y vidas en una Granada donde cada calle tenia una funcion y cada esquina podia esconder una historia. Cruzarlos hoy es pisar una version mas antigua de la ciudad.',
                'cierre_relato' => 'Y quien cruza estos puentes no atraviesa solo el rio, sino una frontera pequena entre la Granada presente y la que aun duerme bajo la piedra.',
            ],
            4 => [
                'titulo_relato' => 'La plaza donde pesaba la justicia',
                'leyenda_relato' => 'Plaza Nueva no siempre fue solo un lugar para quedar. Durante siglos, tambien fue un escenario de poder.',
                'contenido_relato' => 'La Real Chancilleria recordaba a cualquiera que cruzara la plaza que la ciudad tenia nuevos jueces, nuevas normas y una nueva autoridad. Su fachada no esta ahi para decorar: esta ahi para imponer respeto.',
                'cierre_relato' => 'Y ante tal fachada, todo caminante entendia que alli no hablaba una casa cualquiera, sino la voz severa de la justicia.',
            ],
            5 => [
                'titulo_relato' => 'La frase escondida en la piedra',
                'leyenda_relato' => 'La Catedral no se mira de una vez: se lee como una pagina gigante escrita en piedra.',
                'contenido_relato' => 'El Ave Maria sobre el arco central no esta colocado al azar. Forma parte de un mensaje visual pensado para impresionar, guiar y recordar el peso religioso de la nueva Granada cristiana. Entre columnas y relieves, la fachada habla sin necesidad de voz.',
                'cierre_relato' => 'Y asi quedo la piedra escrita para quien supiera leerla: no con tinta, sino con fe, poder y silencio.',
            ],
            6 => [
                'titulo_relato' => 'Las marcas del cambio',
                'leyenda_relato' => 'En Granada, algunos simbolos no decoran: senalan quien quiso escribir la historia despues de 1492.',
                'contenido_relato' => 'La Capilla Real esta ligada a Isabel y Fernando, y sus escudos e iniciales funcionan como huellas de poder. No son simples adornos: son una forma de decir que la ciudad habia cambiado para siempre.',
                'cierre_relato' => 'Y quedaron aquellas senales como firma de un tiempo nuevo, para que Granada recordase que su historia habia cambiado de manos.',
            ],
            7 => [
                'titulo_relato' => 'La ciudad bajo tus pies',
                'leyenda_relato' => 'Desde San Miguel Alto, Granada deja de ser un laberinto y se convierte en un mapa abierto.',
                'contenido_relato' => 'La subida tiene algo de prueba iniciatica. Cuando llegas arriba, la Alhambra, el Albaicin, el centro y la Vega aparecen conectados en una sola mirada. Es como si la ciudad revelara por fin su forma secreta.',
                'cierre_relato' => 'Y desde aquella altura, aun parece Granada un reino extendido a los pies de quien se atreve a subir hasta verla entera.',
            ],
            8 => [
                'titulo_relato' => 'El jardin que baja la voz',
                'leyenda_relato' => 'Hay rincones de Granada donde el agua no decora: parece guardar silencio por algo.',
                'contenido_relato' => 'El Carmen de los Martires mezcla estanques, jardines y caminos escondidos. No se descubre de golpe; se recorre como si cada esquina preparara otra escena. Cerca del ruido turistico, este lugar parece pertenecer a otra Granada mas lenta.',
                'cierre_relato' => 'Y entre el murmullo del agua y la sombra de los jardines, el viajero comprende que Granada tambien sabe esconder la calma.',
            ],
            9 => [
                'titulo_relato' => 'La huerta donde escribia el verano',
                'leyenda_relato' => 'No todas las historias de Granada viven en palacios. Algunas se esconden en una casa familiar rodeada de verde.',
                'contenido_relato' => 'La Huerta de San Vicente fue residencia de verano de la familia Garcia Lorca. Alli la ciudad se vuelve mas intima: menos monumento y mas memoria. Pensar en Lorca aqui es imaginar una Granada cotidiana, creativa y cercana.',
                'cierre_relato' => 'Y en aquella huerta, lejos del marmol y las fortalezas, Granada se volvia voz, verano y memoria.',
            ],
            10 => [
                'titulo_relato' => 'La calle donde los balcones casi se tocan',
                'leyenda_relato' => 'Cuenta la leyenda que en esta calle dos enamorados podian besarse desde balcones opuestos sin pisar la calle.',
                'contenido_relato' => 'Nadie sabe cuanto hay de verdad y cuanto de imaginacion, pero eso es precisamente lo que hace especial al Albaicin. Sus calles no solo se caminan: se inventan, se murmuran y se recuerdan.',
                'cierre_relato' => 'Y desde entonces, quien pasa por esta calle mira hacia arriba, por si aun quedase algun balcon esperando otro beso.',
            ],
            11 => [
                'titulo_relato' => 'La montana que canta por dentro',
                'leyenda_relato' => 'En el Sacromonte, la ciudad no siempre se construye: a veces se excava, se habita y se canta.',
                'contenido_relato' => 'Las cuevas guardan una forma de vida ligada a la ladera, al flamenco y a noches que parecen no acabar. Desde fuera pueden parecer silenciosas, pero el barrio tiene fama de conservar cada eco.',
                'cierre_relato' => 'Y asi, bajo la tierra y sobre la ladera, el Sacromonte fue guardando voces que no se apagan con facilidad.',
            ],
            12 => [
                'titulo_relato' => 'La pared que te devuelve la mirada',
                'leyenda_relato' => 'En el Realejo, algunas fachadas no cierran edificios: observan a quien pasa.',
                'contenido_relato' => 'Amelia del Realejo convierte una pared en personaje. Ese es el poder del arte urbano aqui: cambia la forma de caminar, porque de pronto una calle normal parece tener una presencia propia.',
                'cierre_relato' => 'Y en aquel muro, la calle dejo de ser solo calle, pues la ciudad aprendio tambien a hablar con pintura.',
            ],
            13 => [
                'titulo_relato' => 'La mano que aguarda la llave',
                'leyenda_relato' => 'Cuenta la vieja voz de la Alhambra que, cuando la mano alcance la llave, algo grande habra de suceder.',
                'contenido_relato' => 'En la Puerta de la Justicia, una mano aparece sobre el gran arco exterior y una llave aguarda en el interior. La leyenda dice que ambas no estan ahi por simple adorno: una guarda, la otra abre, y entre las dos se sostiene el misterio de la fortaleza roja.',
                'cierre_relato' => 'Y asi quedo la puerta velando su secreto, con la mano alzada sobre la piedra y la llave esperando un dia que tal vez nunca llegue.',
            ],
            14 => [
                'titulo_relato' => 'La posada de los mercaderes',
                'leyenda_relato' => 'Hubo un tiempo en que las mercancias llegaban a Granada antes que las historias, pero ambas dormian bajo el mismo techo.',
                'contenido_relato' => 'El Corral del Carbon fue alhondiga y lugar de paso para comerciantes. Su arco daba entrada a un patio donde se mezclaban voces, tratos, animales, cargas y noticias venidas de otros caminos.',
                'cierre_relato' => 'Y quien cruzaba aquel arco no entraba solo en un edificio, sino en el murmullo antiguo de una Granada mercader.',
            ],
            15 => [
                'titulo_relato' => 'El cielo pequeno del Banuelo',
                'leyenda_relato' => 'En los banos antiguos, la luz no entraba como en las casas: caia desde arriba, partida en estrellas.',
                'contenido_relato' => 'El Banuelo conserva la memoria de los banos arabes de Granada. En sus bovedas, pequenas aberturas con forma de estrella dejaban pasar la claridad y convertian el vapor en una escena casi secreta.',
                'cierre_relato' => 'Y bajo aquellas estrellas de piedra, el agua, el silencio y la luz hacian del bano un refugio apartado del ruido de la ciudad.',
            ],
        ];

        foreach ($relatos as $retoId => $relato) {
            DB::table('retos')->where('id', $retoId)->update($relato);
        }
    }

    public function down(): void
    {
        Schema::table('retos', function (Blueprint $table) {
            $table->dropColumn([
                'titulo_relato',
                'leyenda_relato',
                'contenido_relato',
                'cierre_relato',
            ]);
        });
    }
};
