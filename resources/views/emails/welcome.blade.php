@component('mail::message')
# Hola {{$user->name}}

Gracias por crear una cuenta. Por favor verifícala usando el siguiente botón: 

@component('mail::button', ['url' => route('users.verify', $user->verification_token) ])
Confirmar Cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent