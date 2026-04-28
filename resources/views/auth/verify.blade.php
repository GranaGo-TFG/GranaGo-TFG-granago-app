@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Verificar correo') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un nuevo enlace de verificacion a tu correo.') }}
                        </div>
                    @endif

                    <p>{{ __('Antes de continuar, revisa tu email para verificar tu cuenta.') }}</p>
                    <p class="mb-3">{{ __('Si no lo has recibido, puedes pedir otro desde aqui.') }}</p>

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reenviar verificacion') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
