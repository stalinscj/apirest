<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);
        
        $data = $request->all();

        $data['password']           = bcrypt($request->password);
        $data['verified']           = User::USER_NOT_VERIFIED;
        $data['verification_token'] = User::generateVerificationToken();
        $data['admin']              = User::USER_REGULAR;
        
        $user = User::create($data);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email'    => "email|unique:users,email,{$user->id}",
            'password' => 'min:6|confirmed',
            'admin'    => 'in:' . User::USER_ADMIN . ',' . User::USER_REGULAR,
        ];
        
        $this->validate($request, $rules);
        
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('email') && $user->email!=$request->email) {
            $user->email = $request->email;
            $user->verified = User::USER_NOT_VERIFIED;
            $user->verification_token = User::generateVerificationToken();
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if ($user->isVerified()) {
                $user->admin = $request->admin;
            } else {
                return $this->errorResponse(409, 'Únicamente los usuarios verificados pueden cambiar su valor de administrador.');
            }
        }

        if (!$user->isDirty()) {
            return $this->errorResponse(422, 'Se debe especificar al menos un valor diferente para actualizar.');
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USER_VERIFIED;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage("La cuenta ha sido verificada.");
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse(409, "Este usuario ya ha sido verificado.");
        }

        Mail::to($user)->send(new UserCreatedMail($user));

        return $this->showMessage("El correo de verificación se ha reenviado.");
    }
}
