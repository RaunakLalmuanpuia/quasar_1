<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;


class AttendenceController extends Controller
{
    
    public function showQr()
    {
        // Get the current location (for demonstration purposes, assume fixed location)
        // Home lat long ['latitude' => 23.75502617646347, 'longitude' => 92.72983056645494];
        //  Msegs 23.724467856537363, 92.71939653576747
        $currentLocation = ['latitude' => 23.724467856537363, 'longitude' => 92.71939653576747];
        $locationData = "Latitude: {$currentLocation['latitude']}, Longitude: {$currentLocation['longitude']}";

        // Prepare data (current date, name, and number)
        $currentDate = date('Y-m-d'); // Get the current date
        $name = 'John Doe'; // Replace this with the desired name
        $number = '12345'; // Replace this with the desired number

        // Combine the data into a single string
        $data = "Date: $currentDate\nName: $name\nNumber: $number\nLocation: $locationData";
       
        // Encrypt the data
        //  $encryptedData = Crypt::encrypt($data);
        // Encrypt the data
        $encryptedData = Crypt::encryptString($data);
       
         // Create Qrcode using SimpleSoftwareIO
        return QrCode::size(200)
        ->backgroundColor(255, 255, 0)
        ->color(0, 0, 255)
        ->margin(1)
        ->generate(
            $encryptedData ,
        );
    }

    public function scanQr(){
        return Inertia::render('Qrcode/ScanQr');
    }
    // 
    public function decryptData(Request $request)
    {
        $encryptedData = $request->input('encryptedData', [
            'appKey' => config('app.key'), // Pass the APP_KEY
        ]);

        try {
            // Decrypt the received encrypted data
            $decryptedData = Crypt::decrypt($encryptedData);

            return response()->json(['decryptedData' => $decryptedData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decrypt data'], 500);
        }
    }
}
