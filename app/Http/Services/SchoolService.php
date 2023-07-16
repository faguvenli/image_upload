<?php

namespace App\Http\Services;

use App\Models\School;

class SchoolService
{
    public function store($data): School {
        return School::create($data);
    }

    public function update(School $school, $data): School {
        $school->update($data);
        return $school;
    }

    public function delete(School $school): void {
        $school->delete();
    }
}
