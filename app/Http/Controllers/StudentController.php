<?php

namespace App\Http\Controllers;

use App\Http\Traits\UtilityTrait;
use App\Student;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{

    use UtilityTrait;

    public function indexApi()
    {
        $students = Student::latest()->get();
        return response()->json($students);

    }

    public function index()
    {
        $students = Student::latest()->get();
        return view('admin.students.index', compact('students'));
    }


    public function storeApi(Request $request)
    {
        $validator= \validator()->make($request->all(),[
            'name' => 'required|string',
            'phoneNumber' => 'required|string',
            'card' => 'required|string',
            'pin' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response['response'] = $validator->messages();
            return response()->json($response, 400);
        }

        // check if phone number exists
        $student = Student::where('phoneNumber', $request->phoneNumber)->first();

        if ($student) {
            $response['response'] = "Phone number already exists";
            return response()->json($response, 401);
        }

        // check if card exists
        $student = Student::where('card', $request->card)->first();

        if ($student) {
            $response['response'] = "Card already exists";
            return response()->json($response, 403);
        }

        $pin = $request->pin;

        $student = Student::create([
            'name' => $request['name'],
            'phoneNumber' => $request['phoneNumber'],
            'pin' => $pin,
            'card' => $request['card'],
        ]);

        return response()->json($student);
    }

    public function opayPaymentResponse(Request $request)
    {
        Log::info("CALLBACK RESPONSE", $request->all());
        $refId = $request->data['ref'];
        $status = $request->data['status'];
        $event_kind = $request->event_kind;

        if($event_kind != "transaction:processed"){
            return response(["message" => "Ok"]);
        }

        $trans = Transaction::where("transaction_id", $refId)->first();

        $status = strtolower($status);

        if ($status == "pending") {
            return response(["message" => "Transaction pending"]);
        }

        if ($status != "successful") {
            $trans->status = "FAILED";
            $trans->save();
            return response(["message" => "Transaction failed"]);
        }
        $trans->status = "SUCCESS";
        $student = Student::find($trans->student_id);
        $student->balance = $student->balance + $trans->amount;
        $trans->save();
        $student->save();
        $this->sendSMS($student->phoneNumber,
            $student->name . " your transaction of " . $trans->amount . " Rwf was successful. Your new balance is " . $student->balance . " Rwf");

        return response(["message" => "Ok"]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
