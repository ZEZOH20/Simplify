<?php

namespace App\Http\Controllers\CRUD;

use App\Classes\Filtering;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicStaffStoreRequest;
use App\Http\Resources\AcademicStaffResource;
use App\Models\AcademicStaff;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class AcademicStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staff = (new Filtering($request->query(), 'academic_staffs', [
            'name',
            'verbose_title',
            'email',
            'phone_number',
            'department',
            'degree',
            'title',
        ]))->start();

        return AcademicStaffResource::collection($staff);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademicStaffStoreRequest $request)
    {
        $validated = $request->validated();

        $member = AcademicStaff::firstOrCreate(
            [
                'email' => $validated['email']
            ],
            $validated
        );

        return response([
            'message' => 'successfully store',
            'data' => new AcademicStaffResource($member)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $email)
    {
        $member = AcademicStaff::where('email', $email)->first();
        return ($member) ? response(['data' => new AcademicStaffResource($member)], 200) :
            response(['errorMessage' => 'staff member email ' . $email . ' doesn\'t exist'], 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AcademicStaffStoreRequest $request, string $email)
    {
        $validated = $request->validated();

        //check exists
        $exists = false;

        if (!empty($validated['email']))
            $exists = AcademicStaff::where('email', $request->email)->exists();

        if ($exists && !empty($validated['email'])) {
            return response(['errorMessage' => 'can\'t update staff member email ' . $email . ' another member already exist'], 400);
        }
        // update member
        $updateMember = AcademicStaff::where('email', $email)->update($validated);

        if (!empty($validated['email']))
            $email = $request->email;

        return ($updateMember) ? response([
            'message' => 'successfully update staff member email :  ' . $email,
            'data' => new AcademicStaffResource(AcademicStaff::where('email', $email)->first())
        ], 200) :
            response(['errorMessage' => 'staff member email ' . $email . ' doesn\'t exist'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $email)
    {
        $member = AcademicStaff::where('email', $email)->first();
        //check exists 
        if (!$member)
            return response(['errorMessage' => 'staff member email ' . $email . ' doesn\'t exist'], 400);

        $member->delete();
        return response(['message' => 'successfully delete staff member email :  ' . $email]);
    }



    public function attachDetachCourse(AcademicStaffStoreRequest $request)
    {
        $routeName = $request->Route()->getName();
        $validated = $request->validated();
        $member = AcademicStaff::where('email', $validated['email'])->first();
        $course = Course::where('course_code', $validated['course_code'])->first();
        //check exists 
        if (!($member && $course))
            return response([
                'errorMessage' =>
                'staff member email ' . $validated['email'] . ' doesn\'t exist or ' .
                    'course code ' . $validated['course_code'] . ' doesn\'t exist.',
            ], 400);

        $start = strpos($routeName, ".") + 1; //routeName position from (.) + 1
        $end = 6; //end = 6 , because (detach && attach) characters number = 6
        $process = substr($routeName, $start, $end) . 'ed';

        //check exists before attach and detach
        $exists = $member->makeResponsible()
            ->wherePivot('academic_staff_id', $member->id)
            ->wherePivot('course_code', $course->course_code)->exists();

        // check if attached 
        if ($process == 'detached') $exists = !$exists;
        if (($exists)) {
            return response(['errorMessage' => 'staff member email ' . $member->email
                . ' already ' . $process  . ' course code ' . $course->course_code], 400);
        }
        if ($routeName == 'staff.attachCourse') {
            // you must check if element exist so you can't insert same
            $member->makeResponsible()->attach($course->course_code);
        } else if ($routeName == 'staff.detachCourse') {
            $member->makeResponsible()->detach($course->course_code);
        }
        return response([
            'message' => 'successfully ' . $process . ' course code ' . $course->course_code,
            'data' => $member->makeResponsible
        ], 200);
    }

    public function showAttachedMemberCourses(string $email)
    {

        $member = AcademicStaff::with(['makeResponsible'])->where('email', $email)->first();
        //check exists 
        if (!$member)
            response(['errorMessage' => 'staff member email ' . $email . ' doesn\'t exist'], 400);

        return new AcademicStaffResource($member);
    }
}
