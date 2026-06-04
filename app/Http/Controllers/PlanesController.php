<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanesController extends Controller
{
    public function pago(string $plan): View
    {
        return view('vistas.planes-checkout', [
            'plan' => $this->obtenerPlan($plan),
            'metodosPago' => $this->metodosPago(),
        ]);
    }

    public function procesarPago(Request $request, string $plan): RedirectResponse
    {
        $planSeleccionado = $this->obtenerPlan($plan);
        $metodosPago = $this->metodosPago();

        $data = $request->validate([
            'metodo_pago' => ['required', Rule::in(array_keys($metodosPago))],
        ]);

        $this->validateDatosMetodo($request, $data['metodo_pago']);

        return redirect()
            ->route('vistas.planes.pago', $planSeleccionado['slug'])
            ->with('status', 'Pago procesado correctamente para el plan ' . $planSeleccionado['nombre'] . '.');
    }

    private function obtenerPlan(string $slug): array
    {
        $planes = [
            'aventura' => [
                'slug' => 'aventura',
                'nombre' => 'Aventura',
                'precio' => '6,99',
                'periodo' => '/mes',
                'descripcion' => 'Ideal para usuarios activos que quieren retos premium y ventajas reales.',
                'beneficios' => [
                    'Retos premium y rutas exclusivas',
                    'Multiplicador extra en eventos especiales',
                    'Canjes y recompensas prioritarias',
                ],
            ],
            'crew' => [
                'slug' => 'crew',
                'nombre' => 'Crew',
                'precio' => '14,99',
                'periodo' => '/mes',
                'descripcion' => 'Pensado para grupos y equipos que quieren competir y colaborar juntos.',
                'beneficios' => [
                    'Hasta 5 miembros en un mismo plan',
                    'Clasificaciones privadas por equipo',
                    'Soporte prioritario para organizadores',
                ],
            ],
        ];

        abort_unless(isset($planes[$slug]), 404);

        return $planes[$slug];
    }

    private function validateDatosMetodo(Request $request, string $metodoPago): void
    {
        match ($metodoPago) {
            'tarjeta' => $request->validate([
                'tarjeta_titular' => ['required', 'string', 'max:120'],
                'tarjeta_numero' => ['required', 'digits_between:13,19'],
                'tarjeta_caducidad' => ['required', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
                'tarjeta_cvv' => ['required', 'digits_between:3,4'],
            ]),
            'bizum' => $request->validate([
                'bizum_titular' => ['required', 'string', 'max:120'],
                'bizum_telefono' => ['required', 'regex:/^[67][0-9]{8}$/'],
            ]),
            'paypal' => $request->validate([
                'paypal_email' => ['required', 'email', 'max:150'],
            ]),
            'transferencia' => $request->validate([
                'transferencia_titular' => ['required', 'string', 'max:120'],
                'transferencia_banco' => ['required', 'string', 'max:120'],
                'transferencia_iban' => ['required', 'regex:/^[A-Za-z]{2}[0-9A-Za-z]{10,30}$/'],
            ]),
            default => null,
        };
    }

    private function metodosPago(): array
    {
        return [
            'tarjeta' => [
                'nombre' => 'Tarjeta de debito o credito',
                'descripcion' => 'Introduce los datos de tu tarjeta para confirmar la suscripcion.',
            ],
            'paypal' => [
                'nombre' => 'PayPal',
                'descripcion' => 'Usa tu correo de PayPal para realizar el pago.',
            ],
            'bizum' => [
                'nombre' => 'Bizum',
                'descripcion' => 'Introduce tu telefono Bizum para autorizar el pago.',
            ],
            'transferencia' => [
                'nombre' => 'Transferencia bancaria',
                'descripcion' => 'Completa titular, banco e IBAN para tramitar la transferencia.',
            ],
        ];
    }
}
