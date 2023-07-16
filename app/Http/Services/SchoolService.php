<?php

namespace App\Http\Services;

use App\Models\Image;
use App\Models\School;

class SchoolService
{
    public function store($data): School {
        $school = School::create($data);
        if(isset($data['images'])) {
            $this->saveImages($school, $data['images']);
        }
        return $school;
    }

    public function update(School $school, $data): School {
        $school->update($data);
        if(isset($data['images'])) {
            $this->saveImages($school, $data['images']);
        }
        return $school;
    }

    public function delete(School $school): void {
        $school->delete();
        $school->images()->delete();
    }

    private function saveImages($school, $images): void
    {
        foreach($images as $image) {
            $img = Image::find($image);
            if($img) {
                $school->images()->save($img);
            }
        }
    }
}
