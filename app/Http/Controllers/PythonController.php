<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonController extends Controller
{
    //
    // public function testPythonScript()
    // {
    //     $process = new Process(['C:\\Python311\\python.exe', '/python/script.py']);
    //     $process->run();
        
    //     if (!$process->isSuccessful()) {
    //         throw new ProcessFailedException($process);
    //     }

    //     $data = $process->getOutput();

    //     dd($data);
    // }
    public function testPythonScript()
    {
        // Specify the full path to the Python executable
        $pythonExecutable = 'C:\\Python311\\python.exe'; // Example: 'C:\\Python39\\python.exe'

        // Specify the relative path to the Python script
        $pythonScript = base_path('python/script.py'); // Relative path to the Python script

        // Check if the script file exists
        if (!file_exists($pythonScript)) {
            throw new \Exception("Python script file '$pythonScript' not found.");
        }

        // Create a new Symfony Process instance
        $process = new Process([$pythonExecutable, $pythonScript]);

        // Run the process
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Get the output of the process
        $data = $process->getOutput();

        // Dump and die the output for debugging
        dd($data);
    }
}
