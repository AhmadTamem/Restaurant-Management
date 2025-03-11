<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\menu;

class ImageController extends Controller
{
    public function uploadImage(Request $request, int $Id)
    {
        // Validate the request
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imageUrls = [];
        $menu = menu::find($Id);


        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'menu not found',
            ], 404);
        }


        $filePath =  'images/';


        foreach ($request->file('images') as $file) {

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($filePath), $fileName);


            $imagePath = $filePath . $fileName;
            $imageUrls[] = [
                'menu_id' => $menu->id,
                'image' => url($imagePath)
            ];
        }


        Image::insert($imageUrls);

        return response()->json([
            'status' => 'success',
            'message' => 'Uploaded successfully',
            'data' => $imageUrls
        ], 200);
    }
    public function UpdateImage(Request $request, int $Id)
    {

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imageUrls = [];
        $menu = menu::find($Id);


        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'menu not found',
            ], 404);
        }


        $filePath =  'images/';


        foreach ($request->file('images') as $file) {

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($filePath), $fileName);


            $imagePath = $filePath . $fileName;
            $imageUrls[] = [
                'menu_id' => $menu->id,
                'image' => url($imagePath)
            ];
        }


        Image::where('menu_id', $menu->id)->delete();
        Image::insert($imageUrls);

        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully',
            'data' => $imageUrls
        ], 200);
    }
}
