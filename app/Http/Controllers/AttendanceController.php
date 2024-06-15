<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Log;
use Carbon\Carbon;
use App\Events\AttendanceRecorded;
use App\Events\LogRecorded;

class AttendanceController extends Controller
{
    public function registerAttendance(Request $request)
    {
        $request->validate([
            'userType' => 'required',
            'studentEmployeeNumber' => 'nullable|string|max:20',
        ]);

        $schoolId = $request->studentEmployeeNumber;
        $userType = $request->userType;

        if ($userType == 'student') {
            $student = Student::where('school_id', $schoolId)->first();
            if (!$student) {
                $this->logActivity('Attendance Attempt', 'Student ID not found', true);
                return response()->json(['error' => 'Student ID not found.'], 404);
            }
            $name = $student->f_name . ' ' . $student->l_name;
            $idNumber = $student->id_number;
            $program = $student->program;
        } elseif ($userType == 'faculty') {
            $faculty = Faculty::where('school_id', $schoolId)->first();
            if (!$faculty) {
                $this->logActivity('Attendance Attempt', 'Faculty ID not found', true);
                return response()->json(['error' => 'Faculty ID not found.'], 404);
            }
            $name = $faculty->f_name . ' ' . $faculty->l_name;
            $idNumber = $faculty->id_number;
            $program = 'N/A';
        } else {
            $name = 'Guest';
            $idNumber = null;
            $program = 'N/A';
            $schoolId = 'guest_' . time();
        }

        // Check for recent attendance only if user is student or faculty
        if ($userType !== 'non-teaching') {
            $latestAttendance = Attendance::where('school_id', $schoolId)
                ->orderBy('timestamp', 'desc')
                ->first();

            if ($latestAttendance && $latestAttendance->timestamp >= Carbon::now()->subHours(3)) {
                $this->logActivity('Attendance Attempt', 'User attempted to register attendance within 3 hours', true);
                return response()->json(['error' => 'You have already timed in within the last 3 hours.'], 400);
            }
        }

        $attendance = new Attendance();
        $attendance->timestamp = Carbon::now();
        $attendance->school_id = $schoolId;
        $attendance->id_number = $idNumber;
        $attendance->role = $userType;
        $attendance->program = $program;
        $attendance->save();

        event(new AttendanceRecorded($attendance));

        $this->logActivity('Attendance Registered', 'User registered attendance successfully', false);

        return response()->json([
            'success' => true,
            'name' => $name,
            'id_number' => $idNumber,
            'school_id' => $schoolId,
            'time_in' => Carbon::now()->format('h:i A'),
        ]);
    }

    private function logActivity($action, $description, $error = false)
    {
        $log = Log::create([
            'timestamp' => Carbon::now(),
            'action' => $action,
            'description' => $description,
            'error' => $error
        ]);

        event(new LogRecorded($log));
    }

    public function rfidTimeIn(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string|max:20',
        ]);

        $idNumber = $request->rfid;

        $student = Student::where('id_number', $idNumber)->first();
        $faculty = Faculty::where('id_number', $idNumber)->first();

        if (!$student && !$faculty) {
            return response()->json(['error' => 'RFID not found.'], 404);
        }

        $user = $student ?? $faculty;
        $role = $student ? 'student' : 'faculty';

        // Check for recent attendance, similar to your existing logic
        $latestAttendance = Attendance::where('id_number', $idNumber)
            ->orderBy('timestamp', 'desc')
            ->first();

        if ($latestAttendance && $latestAttendance->timestamp >= Carbon::now()->subHours(3)) {
            return response()->json(['error' => 'You have already timed in within the last 3 hours.'], 400);
        }

        // Register attendance
        $attendance = new Attendance();
        $attendance->timestamp = Carbon::now();
        $attendance->id_number = $idNumber;
        $attendance->school_id = $user->school_id;
        $attendance->role = $role;
        $attendance->program = $user->program ?? 'N/A';
        $attendance->save();

        event(new AttendanceRecorded($attendance));

        return response()->json([
            'success' => true,
            'name' => $user->f_name . ' ' . $user->l_name,
            'id_number' => $idNumber,
            'school_id' => $user->school_id,
            'time_in' => Carbon::now()->format('h:i A'),
        ]);
    }
}