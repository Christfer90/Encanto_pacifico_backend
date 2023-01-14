<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;

class ProductosController extends Controller
{
    public function index()
    {
        return Productos::All();
    }

    public function crear(Request $request)
    {
        try
        {
            $data = new Productos();
            $data->categoria_id = $request->categoria_id;
            $data->nombre = $request->nombre;
            $data->precio = $request->precio;
            $data->es_stock = $request->es_stock;

            if($data->save())
            {
                return response()->json(['status' => 'success', 'message' => 'Producto registrado correctamente']);
            }
        }catch (\Exception $e) 
        {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function actualizar(Request $request, $id)
    {
        try
        {
            $data = Productos::findOrFail($id);
            $data->categoria_id = $request->categoria_id;
            $data->nombre = $request->nombre;
            $data->precio = $request->precio;
            $data->es_stock = $request->es_stock;

            if($data->save())
            {
                return response()->json(['status' => 'success', 'message' => 'Producto actualizado correctamente']);
            }
        }catch (\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function eliminar($id)
    {
        try
        {
            $data = Productos::findOrFail($id);

            if($data->delete())
            {
                return response()->json(['status' => 'success', 'message' => 'Producto actualizado correctamente']);
            }
        }catch (\Exception $e)
        {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
