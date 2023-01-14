<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorias;

class CategoriasController extends Controller
{
    // Se crea función para mostrar todas las categorias
    public function index()
    {
        return Categorias::All();
    }

    // Se crea función para crear categorias
    public function crear(Request $request)
    {
        try
        {
            $data = new Categorias();
            $data->nombre = $request->nombre;
            
            if($data->save())
            {
                return response()->json(['status' => 'success', 'message' => 'Categoría registrada correctamente']);
            }
        }catch (\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    //Se crea función para actualzar categorias
    public function actualizar(Request $request, $id)
    {
        try
        {
            $data = Categorias::findOrFail($id);
            $data->nombre = $request->nombre;

            if($data->save())
            {
                return response()->json(['status' => 'success', 'message' => 'Categoría actualizada correctamente']);
            }
        }catch (\Exception $e)
        {
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    //Se crea función para eliminar categorias
    public function eliminar($id)
    {
        try
        {
            $data = Categorias::findOrFail($id);

            if($data->delete()){
                return response()->json(['status' => 'success', 'message' => 'Categoria eliminado correctamente']);
            }
        }catch (\Exception $e){
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]); 
        }
    }

}
