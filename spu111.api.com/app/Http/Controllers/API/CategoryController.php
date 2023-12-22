<?php

namespace App\Http\Controllers\API;

use App\Models\Categories;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    function getAll(){
        $list = Categories::all();
        return response()->json($list, 200, ['Charset'=>'utf-8']);
    }

    function create(Request $request) {
        $input = $request->all();
        $image = $request->file("image");
        $manager = new ImageManager(new Driver());
        $imageName = uniqid().".webp";
        $sizes = [50,150,300,600,1200];
        foreach ($sizes as $size) {
            $imageSave = $manager->read($image);
            $imageSave->scale(width: $size);
            $path = public_path("upload/".$size."_".$imageName);
            $imageSave->toWebp()->save($path);
        }
        $input["image"]=$imageName;
        $category = Categories::create($input);
        return response()->json($category,200,['Charset'=>'utf-8']);
    }

    function update(Request $request, $id) {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|max:255'
        ]);

        $category->update($validatedData);

        return response()->json(['message' => 'Category updated successfully', compact('category')]);
    }

    function destroy($id) {
        $category = Categories::findOrFail($id);
    
        $sizes = [50,150,300,600,1200];
        foreach ($sizes as $size) {
            $imagePath = public_path("upload/".$size."_".$category->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200, ['Charset'=>'utf-8']);
    }
}