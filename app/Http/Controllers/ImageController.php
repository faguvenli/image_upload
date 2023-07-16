<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Services\ImageUploadService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request) {
        $request->validate([
           'images' => 'required|array|max:5',
           'images.*' => 'image|mimes:jpeg,png|max:10000'
        ]);

        $imageUploadService = new ImageUploadService();
        $uploadedImages = $imageUploadService->upload($request->file('images'));

        return response()->json([
           'images' => ImageResource::collection($uploadedImages)
        ]);
    }
}
