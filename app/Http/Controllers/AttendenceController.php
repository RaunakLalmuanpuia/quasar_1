<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use App\Models\Attendence;

class AttendenceController extends Controller
{
    
    public function showQr()
    {
        // Get the current location (for demonstration purposes, assume fixed location)
        // Home lat long ['latitude' => 23.75502617646347, 'longitude' => 92.72983056645494];
        //  Msegs 23.724467856537363, 92.71939653576747
        $currentLocation = ['latitude' => 23.75502617646347, 'longitude' => 92.72983056645494]; // home
        $locationData = "Latitude: {$currentLocation['latitude']}, Longitude: {$currentLocation['longitude']}";

        // Prepare data (current date, name, and number)
        $currentDate = date('Y-m-d'); // Get the current date
        $name = auth()->user()->name; // Replace this with the desired name
        $number = '12345'; // Replace this with the desired number
        $password = auth()->user()->password; 
        // Combine the data into a single string
        $data = "Date: $currentDate\nName: $name\nNumber: $number\nLocation: $locationData";
       
        // Encrypt the data
        //  $encryptedData = Crypt::encrypt($data);
        
        // dd(QrCode::size(200)
        // ->backgroundColor(255, 255, 0)
        // ->color(0, 0, 255)
        // ->margin(1)
        // ->generate(
        //     $data ,
        // ));
       
         // Create Qrcode using SimpleSoftwareIO
        return QrCode::size(200)
        ->backgroundColor(255, 255, 0)
        ->color(0, 0, 255)
        ->margin(1)
        ->generate(
            $data ,
        );
    }

    public function scanQr()
    {
        $user_id = auth()->user()->id;

        // Check if attendance is already marked for the current date and user
        $currentDate = date('Y-m-d');
        $existingAttendance = Attendence::where('user_id', $user_id)
            ->where('date', $currentDate)
            ->exists();

        if ($existingAttendance) {
            // Attendance already marked for the current date
            return redirect()->route('dashboard')->with('message', 'Attendance already marked for today.');
        }

        // Render the QR code scanning page
        return Inertia::render('Qrcode/ScanQr');
    }

    public function markAttendence(Request $request)
    {
        // dd($request);
        $request->validate([
            'date' => 'required|string',
            'time' => 'required|string',     
        ]);
        $date = Carbon::parse($request->input('date'));
        $time = Carbon::parse($request->input('time'));
        $user_id = auth()->user()->id;
        $attendanceStatus = $this->calculateAttendanceStatus($date, $time);

        // dd($attendanceStatus);
        // Store the attendance information in your database or perform other actions
       
        $attendence = new Attendence([
            'user_id' => $user_id,
            'date' => $request->date,
            'time' => $request->time,
            'attendence' => $attendanceStatus,
            

        ]);
        $attendence->save();
        return redirect()->route('dashboard')->with('message', 'Attendance marked successfully');
    }

    private function calculateAttendanceStatus(Carbon $date, Carbon $time)
    {
        $nineThirtyAM = $date->copy()->setTime(9, 30, 0);
        $fivePM = $date->copy()->setTime(17, 0, 0); // 5 PM
    
        if ($time->lt($nineThirtyAM)) {
            return 'Present';
        } elseif ($time->lt($fivePM)) {
            return 'Half Day';
        } else {
            return 'Absent';
        }
    }

}
