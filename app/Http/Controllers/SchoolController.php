<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolStoreRequest;
use App\Http\Requests\School\SchoolUpdateRequest;
use App\Http\Resources\SchoolResource;
use App\Http\Services\SchoolService;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    protected SchoolService $schoolService;

    public function __construct(SchoolService $schoolService) {
        $this->schoolService = $schoolService;
    }

    public function index() {
        return SchoolResource::collection(School::all());
    }

    public function store(SchoolStoreRequest $request) {
        try {
            $school = $this->schoolService->store($request->validated());
            return new SchoolResource($school);
        } catch(\Exception $e) {

        }
    }

    public function show(School $school) {
        return new SchoolResource($school);
    }

    public function update(SchoolUpdateRequest $request, School $school) {
        try {
            return $this->schoolService->update($school, $request->validated());
        } catch (\Exception $e) {

        }
    }

    public function destroy(School $school) {
        try {
            $this->schoolService->delete($school);
        } catch(\Exception $e) {

        }
    }
}
