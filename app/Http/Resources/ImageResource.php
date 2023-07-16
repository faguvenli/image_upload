<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $folder = '/images/'.$this->id.'/';
        $paths = [];
        foreach($this->sizes as $size) {
            $paths[$size] = $folder.$size.'/'.$this->name;
        }

        $result = [
            'id' => $this->id,
            'original' => $folder.$this->name,
        ];

        return array_merge($result, $paths);
    }
}
