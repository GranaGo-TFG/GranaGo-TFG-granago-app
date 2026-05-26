<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TiendaController extends Controller
{
    public function index(): View
    {
        $productos = Producto::query()
            ->activos()
            ->orderByDesc('created_at')
            ->paginate(12);

        $masVendidos = Producto::query()
            ->activos()
            ->orderByDesc('vendidos_total')
            ->orderBy('nombre')
            ->limit(5)
            ->get();

        if ($masVendidos->isEmpty()) {
            $masVendidos = $productos->getCollection()->take(5);
        }

        return view('vistas.tienda', [
            'productos' => $productos,
            'masVendidos' => $masVendidos,
        ]);
    }

    public function show(Producto $producto): View
    {
        // Los productos retirados solo son accesibles en el flujo admin.
        abort_unless($producto->activo, 404);

        $relacionados = Producto::query()
            ->activos()
            ->whereKeyNot($producto->id)
            ->where('categoria', $producto->categoria)
            ->orderByDesc('vendidos_total')
            ->limit(3)
            ->get();

        return view('vistas.tienda-producto', [
            'producto' => $producto,
            'relacionados' => $relacionados,
        ]);
    }

    public function pago(Producto $producto): View
    {
        // Evita compras de productos retirados desde rutas directas.
        abort_unless($producto->activo, 404);

        return view('vistas.tienda-checkout', [
            'producto' => $producto,
            'metodosPago' => $this->metodosPago(),
            'maximoCantidad' => max(1, min(5, $producto->stock)),
            'saldoPuntos' => (int) auth()->user()->puntos_totales,
        ]);
    }

    public function procesarPago(Request $request, Producto $producto): RedirectResponse
    {
        // Seguridad en servidor: un producto retirado nunca se procesa para usuarios.
        abort_unless($producto->activo, 404);

        $metodosPago = $this->metodosPago();

        $data = $request->validate([
            'metodo_pago' => ['required', Rule::in(array_keys($metodosPago))],
            'cantidad' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $this->validateDatosMetodo($request, $data['metodo_pago']);

        if ($producto->stock < $data['cantidad']) {
            throw ValidationException::withMessages([
                'cantidad' => 'No hay stock suficiente para completar la compra.',
            ]);
        }

        if ($data['metodo_pago'] === 'puntos') {
            $precioPuntos = (int) $producto->precio_puntos_valor;
            $totalPuntos = $precioPuntos * $data['cantidad'];
            $usuario = $request->user();

            if ((int) $usuario->puntos_totales < $totalPuntos) {
                throw ValidationException::withMessages([
                    'metodo_pago' => 'No tienes puntos suficientes para completar este canje.',
                ]);
            }

            $usuario->forceFill([
                'puntos_totales' => (int) $usuario->puntos_totales - $totalPuntos,
            ])->save();
        }

        $producto->forceFill([
            'stock' => $producto->stock - $data['cantidad'],
            'vendidos_total' => $producto->vendidos_total + $data['cantidad'],
            'precio_puntos' => $producto->precio_puntos ?? $producto->precio_puntos_valor,
        ])->save();

        return redirect()
            ->route('vistas.tienda.producto', $producto)
            ->with('status', 'Compra realizada correctamente.');
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
            'puntos' => null,
            default => null,
        };
    }

    private function metodosPago(): array
    {
        return [
            'tarjeta' => [
                'nombre' => 'Tarjeta de debito o credito',
                'descripcion' => 'Introduce los datos de tu tarjeta para confirmar la compra.',
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
            'puntos' => [
                'nombre' => 'Canjear con puntos',
                'descripcion' => 'Usa tus puntos de GranaGO para obtener este producto.',
            ],
        ];
    }
}
