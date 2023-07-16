<?php

namespace App\Http\Services;

use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    public array $sizes;

    private string $folder;

    public function __construct() {
        $this->getSizes();
    }

    public function upload(array $files): array {
        $uploadedFiles = [];

        foreach($files as $file) {
            $this->getFolder();

            $fileName = $this->getFileName($file);
            $filePath = $this->folder . $fileName;

            $file = $this->saveFile($file, $fileName, $filePath);

            $this->generateThumbs($filePath);

            $uploadedFiles[] = $file;
        }

        return $uploadedFiles;
    }

    private function saveFile($file, $fileName, $filePath): ImageModel {
        Image::make($file)->save($filePath);

        return ImageModel::create([
           'name' => $fileName,
           'sizes' => array_keys($this->sizes)
        ]);
    }

    public function generateThumbs(string $path):void {
        $pathInfo = pathinfo($path);

        foreach($this->sizes as $key => $size) {
            $this->checkFolder('/'.$key);
            $filePath = $pathInfo['dirname'] . '/' . $key . '/' . $pathInfo['filename'] . '.' . $pathInfo['extension'];
            $file = Image::make($pathInfo['dirname'] . '/' . $pathInfo['basename']);

            $file->fit($size['width'], $size['height'])
                ->save($filePath);
        }
    }

    private function getFolder(): void {
        $lastImageID = 0;
        $lastImage = ImageModel::orderBy('id', 'desc')->first();

        if($lastImage) {
            $lastImageID = $lastImage->id;
        }

        $lastImageID++;

        $this->folder = public_path('/images/'.$lastImageID.'/');
    }

    public function setFolder($folder) {
        $this->folder = public_path('/images/'.$folder.'/');
    }

    private function getFileName($file): string {
        $this->checkFolder();
        $fileFullName = $file->getClientOriginalName();
        $fileName = Str::slug(pathinfo($fileFullName, PATHINFO_FILENAME));

        return $fileName . '.' . $file->getClientOriginalExtension();
    }

    private function checkFolder($subFolder = null): void
    {
        if (!File::isDirectory($this->folder.$subFolder)) {
            File::makeDirectory($this->folder.$subFolder, 0775, true, true);
        }
    }

    private function getSizes(): void
    {
        $this->sizes = config('image_upload.sizes');
    }

    public function setSizes($size) {
        $this->sizes = [
            "custom_$size" => ['width' => $size, 'height' => $size]
        ];
    }
}
