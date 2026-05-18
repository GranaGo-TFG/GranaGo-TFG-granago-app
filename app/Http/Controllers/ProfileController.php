<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('vistas.editar-perfil');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->nombre = $data['nombre'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()
            ->route('vistas.editar-perfil')
            ->with('status', 'Tu perfil se ha actualizado correctamente.');
    }
}
