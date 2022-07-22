<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;







class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return Producto::select('id','titulo','descripcion','responsable','image')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        {
            $request->validate([
                'titulo'=>'required',
                'descripcion'=>'required',
                'responsable'=>'required',
                'image'=>'required|image'
            ]);
    
            try{
                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('producto/image', $request->image,$imageName);
                Producto::create($request->post()+['image'=>$imageName]);
    
                return response()->json([
                    'message'=>'Registro creado correctamente'
                ]);
            }catch(\Exception $e){
                \Log::error($e->getMessage());
                return response()->json([
                    'message'=>'Algo ha salido mal creando el registro, ' . $e->getMessage()
                ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
        return response()->json([
            'producto'=>$producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //

        $request->validate([
            'titulo'=>'required',
            'descripcion'=>'required',
            'responsable'=>'required',
            'image'=>'required|image'
        ]);

        try{

            $producto->fill($request->post())->update();

            if($request->hasFile('image')){

                // Elimina Imagen guardada
                if($producto->image){
                    $exists = Storage::disk('public')->exists("producto/image/{$producto->image}");
                    if($exists){
                        Storage::disk('public')->delete("producto/image/{$producto->image}");
                    }
                }

                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('producto/image', $request->image,$imageName);
                $producto->image = $imageName;
                $producto->save();
            }

            return response()->json([
                'message'=>'Registro actualizado correctamente'
            ]);

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Algo ha salido mal actualizando el registro'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //

        try {

            if($producto->image){
                $exists = Storage::disk('public')->exists("producto/image/{$producto->image}");
                if($exists){
                    Storage::disk('public')->delete("producto/image/{$producto->image}");
                }
            }

            $producto->delete();

            return response()->json([
                'message'=>'El Registro fue eliminado'
            ]);
            
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Algo ha salido mal eliminando el registro'
            ]);
        }
    }
}
