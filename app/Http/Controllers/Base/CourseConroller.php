<?php

namespace App\Http\Controllers\Base;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseConroller extends Controller
{
    public function addCourse(Request $request) {
        $validate = Validator::make($request->all(), [
            'title'=>'required',
            'course_thumbnail'=>'required',
            'description'=>'required',
            'price'=>'required',
            'category'=>'required',
        ],[
            'title.required'=>'Title is required',
            'course_thumbnail.required'=>'Thumbnail is required',
            'description.required'=>'Description is required',
            'price.required'=>'Price is required',
            'category.required'=>'Category is required',
        ]);

        if($validate->fails()) {
            $data = [
                'status'=>422,
                'error'=>$validate->messages()
            ];
            return response()->json($data, 422);
        }else {
            //Get loggined user
            //$user = auth()->user();

            //temporarly user
            //$user = 4;

            $user = Auth::user();

            // Add Course
            Course::create([
                'title'=>$request->title,
                'course_thumbnail'=>$request->course_thumbnail,
                'description'=>$request->description,
                'price'=>$request->price,
                'category'=>$request->category,
                'user_id'=>$user->id,
                //'user_id'=>$user,
            ]);

            $data = [
                'status'=>201,
                'message'=>'Course Added'
            ];
            return response()->json($data, 201);
        }
    }

    //Return courses for a specific teacher
    public function allTeacherCourses() {
        $teacher = Auth::user();

        $courses = Course::where('user_id', $teacher->id)
                            ->get();

        $data = [
            'status'=>200,
            'courses'=>$courses,
        ];

        return response()->json($data, 200);
    }

    public function allCourses() {
        $courses = Course::all();
        $data = [
            'status'=>200,
            'courses'=>$courses,
        ];
        return response()->json($data, 200);
    }

    public function editCourse(Request $request, $id) {
        $validate = Validator::make($request->all(), [
            'title'=>'required',
            'course_thumbnail'=>'required',
            'description'=>'required',
            'price'=>'required',
            'category'=>'required',
        ],[
            'title.required'=>'Title is required',
            'course_thumbnail.required'=>'Thumbnail is required',
            'description.required'=>'Description is required',
            'price.required'=>'Price is required',
            'category.required'=>'Category is required',
        ]);

        if($validate->fails()) {
            $data = [
                'status'=>422,
                'error'=>$validate->messages(),
            ];
            return response()->json($data, 422);
        }else {
            //handle edit course
            $course = Course::find($id);

            $course->title = $request->title;
            $course->course_thumbnail = $request->course_thumbnail;
            $course->description = $request->description;
            $course->price = $request->price;
            $course->category = $request->category;

            $course->save();

            $data = [
                'status'=>200,
                'message'=>'Course Updated',
            ];
            return response()->json($data, 200);
        }
    }

    public function singleCourse($id) {
        $course = Course::find($id);
        $data = [
            'status'=>200,
            'course'=>$course,
        ];

        return response()->json($data, 200);
    }

    public function deleteCourse($id) {
        $course = Course::find($id);
        $course->delete();

        $data = [
            'status'=>200,
            'course'=>'Course deleted',
        ];

        return response()->json($data, 200);
    }

    public function searchCourse(Request $request) {
        $search_query = $request->input('query');
        $courses = Course::where('title', 'LIKE', "%{$search_query}%")
                        ->orWhere('description', 'LIKE', "%{$search_query}%")
                        ->orWhere('category', 'LIKE', "%{$search_query}%")
                        ->get();

        $data = [
            'status'=>200,
            'courses'=>$courses,
        ];

        return response()->json($data, 200);
    }
}


    