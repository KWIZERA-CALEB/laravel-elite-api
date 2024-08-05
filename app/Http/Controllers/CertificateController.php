<?php

namespace App\Http\Controllers;

use App\Models\Certificates;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    //after completing a course call this api to save the certificate
    public function addCertificate (Request $request, $id) {
        // temporarly data
        //$student_id = 1;

        $student = auth()->user();
        $course = Course::find($id);


        $certificateCode = rand(100000, 999999);

        $certificate = Certificates::create([
            'course_id'=>$course->id,
            'student_id'=>$student->id,
            'cert_code'=>$certificateCode,
        ]);

        $data =[
            'status'=>201,
            'message'=>'Certificate Created',
            'certificate_details'=>$certificate,
        ];

        return response()->json($data, 201);
    }

    //update certificate after django has finished creating it
    public function updateCertificate(Request $request, $id) {
        $certificate = Certificates::find($id);
        $certificate->cert_file = $request->cert_file;

        //save the certificate
        $certificate->save();

        $data = [
            'status'=>200,
            'message'=>'Certificate Awarded',
        ];
        return response()->json($data, 200);
    }

    //django gets this data to generate the certificate when a user claims the certificate
    public function singleCertificate(Request $request, $id) {
        //temporally data
        $auth_student = auth()->user();


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
        $student = auth()->user();

        $certificates = Certificates::where('student_id', $student->id)
                                    ->get();
        $data = [
            'status'=>200,
            'certificate'=>$certificates,
        ];
        return response()->json($data, 200);
    }
}
