<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Faculty;
use Carbon\Carbon;
use App\Events\AttendanceRecorded;

class AttendanceController extends Controller
{
    public function registerAttendance(Request $request)
    {
        $request->validate([
            'userType' => 'required',
            'studentEmployeeNumber' => 'nullable|string|max:20',
        ]);

        $idNumber = $request->studentEmployeeNumber;
        $userType = $request->userType;

        if ($userType !== 'non-teaching') {
            $latestAttendance = Attendance::where('id_number', $idNumber)
                ->orderBy('timestamp', 'desc')
                ->first();

            if ($latestAttendance && $latestAttendance->timestamp >= Carbon::now()->subHours(3)) {
                return response()->json(['error' => 'You have already timed in within the last 3 hours.'], 400);
            }
        }

        if ($userType == 'student') {
            $student = Student::where('id_number', $idNumber)->first();
            if (!$student) {
                return response()->json(['error' => 'Student ID not found.'], 404);
            }
            $name = $student->f_name . ' ' . $student->l_name;
            $schoolId = $student->school_id;
            $program = $student->program;
        } elseif ($userType == 'faculty') {
            $faculty = Faculty::where('id_number', $idNumber)->first();
            if (!$faculty) {
                return response()->json(['error' => 'Faculty ID not found.'], 404);
            }
            $name = $faculty->f_name . ' ' . $faculty->l_name;
            $schoolId = $faculty->school_id;
            $program = 'N/A';
        } else {
            $name = 'Guest';
            $schoolId = null;
            $program = 'N/A';
            $idNumber = 'guest_' . time();
        }

        $attendance = new Attendance();
        $attendance->timestamp = Carbon::now();
        $attendance->id_number = $idNumber;
        $attendance->school_id = $schoolId;
        $attendance->role = $userType;
        $attendance->program = $program;
        $attendance->save();

        event(new AttendanceRecorded($attendance));

        return response()->json([
            'success' => true,
            'name' => $name,
            'id_number' => $idNumber,
            'time_in' => Carbon::now()->format('H:i'),
        ]);
    }
}