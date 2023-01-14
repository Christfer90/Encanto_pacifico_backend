<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Servicios\UtilidadService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class UsuarioController extends Controller
{
    protected $usuario;
    protected $utilidadService;

    public function  __construct()
    {
        $this->middleware("auth:user",['except'=>['login','registrar']]);
        $this->usuario = new User;
        $this->utililidadService = new UtilidadService;
    }

    public function registrar(RegisterRequest $request)
    {
        $password_hash = $this->utilidadService->hash_password($request->password);
        $this->usuario->crear($request, $password_hash);
        $success_message = "Usuario registrado correctamente";
        return  $this->utilidadService->is200Response($success_message);
    }

    /**
     * Se obtiene un JWT a través de las credenciales dadas.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::guard('user')->attempt($credentials)) {
            $responseMessage = "Usuario o contraseña invalido";
            return $this->utilidadService->is422Response($responseMessage);
         }
         

        return $this->respondWithToken($token);
    }

    public function perfil()
    {
        return response()->json([
            'success'=>true,
            'user' => Auth::guard('user')->user()
            ]
            , 200);
    }

    /**
     * Log the application out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
           
             $this->logoutUsuario();

        } catch (TokenExpiredException $e) {
            
            $responseMessage = "token has already been invalidated";
            $this->tokenExpirationException($responseMessage);
        } 
    }

    public function logoutUsuario()
    {
        Auth::guard('user')->logout();
        $responseMessage = "Se desconectó con éxito";
        return  $this->utilidadService->is200Response($responseMessage);
    }

    public function tokenExpirationException($responseMessage)
    {
        return $this->utilidadService->is422Response($responseMessage);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        try
         {
            return $this->respondWithToken(Auth::guard('user')->refresh());
        }
         catch (TokenExpiredException $e)
         {
            $responseMessage = "El token ha caducado y ya no se puede actualizar";
            return $this->tokenExpirationException($responseMessage);
        } 
    }
    
}