<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Servicios\UtilidadService;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AdminController extends Controller
{
    protected $administrador;
    protected $utilidadService;

    public function  __construct()
    {
        $this->middleware("auth:admin",['except'=>['login','registrar']]);
        $this->administrador = new Admin;
        $this->utilidadService = new UtilidadService;
    }

    public function registrar(AdminRegisterRequest $request)
    {
        $password_hash = $this->utilidadService->hash_password($request->password);
        $this->administrador->crear($request, $password_hash);
        $success_message = "Administrador registrado correctamente";

        return  $this->utilidadService->is200Response($success_message);
    }

    /**
     * Se obtiene un JWT a través de las credenciales dadas.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::guard('admin')->attempt($credentials)) {
            $responseMessage = "Usuario o contraseña invalido";

            return $this->utilidadService->is422Response($responseMessage);
         }
         

        return $this->respondWithToken($token);
    }

    public function perfil()
    {
        return response()->json([
            'success'=>true,
            'user' => Auth::guard('admin')->admin()
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

    public function logoutAdministrador()
    {
        Auth::guard('admin')->logout();
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
            return $this->respondWithToken(Auth::guard('admin')->refresh());
        }
         catch (TokenExpiredException $e)
         {
            $responseMessage = "El token ha caducado y ya no se puede actualizar";
            return $this->tokenExpirationException($responseMessage);
        } 
    }
    
}
