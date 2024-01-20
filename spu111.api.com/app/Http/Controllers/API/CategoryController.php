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

    public function getById($id) {
        $category = Categories::findOrFail($id);
        return response()->json($category,200, ['Charset' => 'utf-8']);
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
        $category = Categories::findOrFail($id);
        $imageName=$category->image;
        $inputs = $request->all();
        if($request->hasFile("image")) {
            $image = $request->file("image");
            $imageName = uniqid() . ".webp";
            $sizes = [50, 150, 300, 600, 1200];
            $manager = new ImageManager(new Driver());
            foreach ($sizes as $size) {
                $fileSave = $size . "_" . $imageName;
                $imageRead = $manager->read($image);
                $imageRead->scale(width: $size);
                $path = public_path('upload/' . $fileSave);
                $imageRead->toWebp()->save($path);
                $removeImage = public_path('upload/'.$size."_". $category->image);
                if(file_exists($removeImage))
                    unlink($removeImage);
            }
        }
        $inputs["image"]= $imageName;
        $category->update($inputs);
        return response()->json($category,200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
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