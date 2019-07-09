@component('mail::message')
# Hola {{$user->name}}

Has cambiado tu correo eletrónico. Por favor verifica la nueva dirección usando el siguiente botón: 

@component('mail::button', ['url' => route('users.verify', $user->verification_token)])
Confirmar Cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent