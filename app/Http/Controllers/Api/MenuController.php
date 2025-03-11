<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
class MenuController extends Controller
{
    public function Store (Request $request){
        try{
            Gate::authorize('create',menu::class);
        $request->validate([
            'name'=>'required|string',
            'category'=>'required',
            'price'=>'required|numeric',
            'description'=>'required'

        ]);
        $menu=Menu::create([
            'name'=>$request->name,
            'category'=>$request->category,
            'price'=>$request->price,
            'description'=>$request->description

        ]);
        return response()->json(['status'=>'success','message'=>'inserted sucss','data'=>$menu],201);
    }
    catch(Exception $e){
        return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
    }
    }
    public function index(){
        try{
            $menu=Menu::with('Image')->get();
            return response()->json(['status'=>'success','message'=>'sucss','data'=>$menu],200);
        }
        catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    public function show($id){
        try{
            $menu=Menu::with('Image')->findOrFail($id);
            if(!$menu){
                return response()->json(['status'=>'error','message'=>'Menu Not Found'],404);
            }
            return response()->json(['status'=>'success','message'=>'sucss','data'=>$menu],200);

        }
        catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    } 



    public function update(Request $request,$id){
        try{
            Gate::authorize('update',menu::class);
        $request->validate([
            'name'=>'nullable|string',
            'category'=>'nullable',
            'price'=>'nullable|numeric',
            'description'=>'nullable'
        ]);
        $menu=Menu::find($id);
        if(!$menu){
            return response()->json(['status'=>'error','message'=>'Menu Not Found'],404);
        }
        $menu->update([
            'name'=>$request->name,
            'category'=>$request->category,
            'price'=>$request->price,
            'description'=>$request->description
        ]);
        return response()->json(['status'=>'succes','message'=>'updated','data'=>$menu],200);
    }
    catch(Exception $e){
        return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
    }
    }
    public function destroy($id){
        try{
            Gate::authorize('delete',menu::class);
            $menu=Menu::find($id);
            if(!$menu){
                return response()->json(['status'=>'error','message'=>'Menu Not Found'],404);
            }
            $menu->delete();
            return response()->json(['status'=>'succes','message'=>'deleted'],200);
        }
        catch(Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }


}
