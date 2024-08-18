<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Certificates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    //after completing a course call this api to save the certificate
    public function addCertificate (Request $request, $id) {
        $student = Auth::user();
        $course = Course::find($id);


        $certificateCode = rand(100000, 999999);
        $randomText = "djcdjcjSJHSJHAj77" + $certificateCode + '.pdf';
        $cert_file = $randomText;

        $certificate = Certificates::create([
            'course_id'=>$course->id,
            'student_id'=>$student->id,
            'cert_code'=>$certificateCode,
            'cert_file'=>$cert_file,
        ]);

        $data =[
            'status'=>201,
            'message'=>'Certificate Awarded',
            'certificate_details'=>$certificate,
        ];

        return response()->json($data, 201);
    }


    //django gets this data to generate the certificate when a user claims the certificate
    public function singleCertificate(Request $request, $id) {
        $auth_student = Auth::user();


        $student = User::find($auth_student->id);
        $course = Course::find($id);

        $data = [
            'status'=>200,
            'course'=>$course,
            'student'=>$student,
        ];

        return response()->json($data, 200);
    }

    public function allUserCertificates() {
        $student = Auth::user();

        $certificates = Certificates::where('student_id', $student->id)
                                    ->get();
        $courseIds = $certificates->pluck('course_id'); //pluck to get the array of course_id column from the certificates
        $courses = Course::whereIn('id', $courseIds)->get();
        
        $data = [
            'status'=>200,
            'courses'=>$courses,
            'student'=>$student,
            'certificate'=>$certificates,
        ];
        return response()->json($data, 200);
    }
}
