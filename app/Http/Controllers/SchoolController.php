<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\SchoolStoreRequest;
use App\Http\Requests\School\SchoolUpdateRequest;
use App\Http\Resources\SchoolResource;
use App\Http\Services\SchoolService;
use App\Http\Traits\ResponseTrait;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    use ResponseTrait;

    protected SchoolService $schoolService;

    public function __construct(SchoolService $schoolService) {
        $this->schoolService = $schoolService;
    }

    public function index() {
        $schools = SchoolResource::collection(School::all());
        return $this->successResponse('School List', $schools);
    }

    public function store(SchoolStoreRequest $request) {
        try {
            $school = $this->schoolService->store($request->validated());
            return $this->successResponse('School Created!', new SchoolResource($school));
        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(School $school) {
        $school = new SchoolResource($school);
        return $this->successResponse('School', $school);
    }

    public function update(SchoolUpdateRequest $request, School $school) {
        try {
            $school = $this->schoolService->update($school, $request->validated());
            return $this->successResponse('School updated!', new SchoolResource($school));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(School $school) {
        try {
            $this->schoolService->delete($school);
            return $this->successResponse('School deleted!');
        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
