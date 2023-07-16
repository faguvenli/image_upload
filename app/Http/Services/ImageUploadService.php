<?php

namespace App\Http\Services;

use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    private array $sizes;

    private string $folder;

    public function __construct() {
        $this->getSizes();
    }

    public function upload(array $files): array {
        $uploadedFiles = [];

        foreach($files as $file) {
            $this->setFolder();

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

    private function setFolder(): void {
        $lastImageID = 0;
        $lastImage = ImageModel::orderBy('id', 'desc')->first();

        if($lastImage) {
            $lastImageID = $lastImage->id;
        }

        $lastImageID++;

        $this->folder = public_path('/images/'.$lastImageID.'/');
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
        $this->sizes = [
            'small' => ['width' => 100, 'height' => 100],
            'medium' => ['width' => 400, 'height' => 400],
            'large' => ['width' => 800, 'height' => 800]
        ];
    }

    public function setSizes($size) {
        $this->sizes = [
            "custom_$size" => ['width' => $size, 'height' => $size]
        ];
    }
}
