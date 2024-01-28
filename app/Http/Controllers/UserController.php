<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $DOMAIN_NAME;

    public function __construct() {
        $this->DOMAIN_NAME = env('MY_DOMAIN_NAME');
    } 

    public function index()
    {
        $users = User::with('roles')->select('id','name', 'email')->paginate(10);
        $roles = Role::pluck('name', 'id');
        return Inertia::render('User/Index',[
            "users" => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create the user
        $name = Auth()->user()->name;
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
            'role' => 'array'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->syncRoles($request->role);

         //SMS
         $phone = "7640876052";
         $message = 'Chibai '.$user->name.', '.$this->DOMAIN_NAME.'.mizoram.gov.in ah in Department Nodal Officer in a register che tih hriattir I ni e. EGOV-MZ';
         $templateId ='1407170608242353930';
        //  $message = 'Hello '.$user->name.', you have been registered to '.$this->DOMAIN_NAME.'.mizoram.gov.in by your Department Nodal Officer, '.$name.'. EGOV-MZ';
        //  $templateId ='1407168966069639802';
        try {
            // Call the function and provide necessary parameters
            $this->sendSMS($phone, $message, $templateId);

            // If the function is executed successfully, you can include additional logic here
            Log::info('SMS sent successfully.');

            return redirect()->route('users.index')->with('message', 'user succesfully created');
        } catch (\Exception $e) {
            // Handle the exception (log, notify, etc.)
            Log::error('Error sending SMS: ' . $e->getMessage());

            return redirect()->route('users.index')->with('message', 'Erroe sendig SMS');
        }
        //  $this->sendSMS($phone,$message,$templateId);

        // return redirect()->route('users.index')->with('message', 'user succesfully created');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // dd($user);

        // $validatedData = $request->validate([
        //     'name' => 'sometimes|string|max:255',
        //     'email' => [
        //         'sometimes',
        //         'email',
        //         Rule::unique('users')->ignore($user->id),
        //     ],
        //     'password' => 'sometimes|string|min:8',
        //     'role' => 'sometimes|array',
        // ]);

        // dd("role"+$validatedData['role']);
        // // Only update non-empty values
        // $user->fill(array_filter($validatedData));

        // if (!empty($validatedData['password'])) {
        //     $user->password = Hash::make($validatedData['password']);
        // }
        // $user->syncRoles($request->role);
        // // $user->syncRoles($validatedData['role'] ?? []);
        // $user->update();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->syncRoles($request->role);
        $user->update();

        
        //SMS
        // $resetBy = Auth()->user()->name;

        // $phone = "7640876052";
        // $message = 'Chibai '.$user->name.', Mipui Aw a I password '.$resetBy.' in a thlak a. I password thar chu '.$request->password.' a ni. A rang thei ang ber a '.$this->DOMAIN_NAME.'.mizoram.gov.in ah lutin I password thlak ang che. EGOV-M';
        // $templateId ='1407170608250496376';

        // try {
        //     // Call the function and provide necessary parameters
        //     $this->sendSMS($phone, $message, $templateId);
        //     // If the function is executed successfully, you can include additional logic here
        //     Log::info('SMS sent successfully.');
        //     return redirect()->route('users.index')->with('message', 'user succesfully Updated');
        // } catch (\Exception $e) {
        //     // Handle the exception (log, notify, etc.)
        //     Log::error('Error sending SMS: ' . $e->getMessage());
        //     return redirect()->route('users.index')->with('message', 'Erroe sendig SMS');
        // }
        //SMS

        $phone = "7640876052";
        $message = 'Chibai '.$user->name.', '.$this->DOMAIN_NAME.'.mizoram.gov.in ah in Department Nodal Officer in a register che tih hriattir I ni e. EGOV-MZ';
        $templateId ='1407170608242353930';
        //  $message = 'Hello '.$user->name.', you have been registered to '.$this->DOMAIN_NAME.'.mizoram.gov.in by your Department Nodal Officer, '.$name.'. EGOV-MZ';
        //  $templateId ='1407168966069639802';
        try {
        // Call the function and provide necessary parameters
        $this->sendSMS($phone, $message, $templateId);

        // If the function is executed successfully, you can include additional logic here
        Log::info('SMS sent successfully.');

        return redirect()->route('users.index')->with('message', 'user succesfully updated');
        } catch (\Exception $e) {
        // Handle the exception (log, notify, etc.)
        Log::error('Error sending SMS: ' . $e->getMessage());

        return redirect()->route('users.index')->with('message', 'Erroe sendig SMS');
        }

        // return redirect()->route('users.index')->with('message', 'user succesfully Edited');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // dd($user);
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('users.index')->with('message', 'user succesfully Deleted');
    }

    // Function to send SMS
    public function sendSMS($phone, $message, $templateId, $otp=false){
        // dd($phone);
        if(is_array($phone)){
            $mPhones=implode(",",$phone);
        }else{
            $mPhones=$phone;
        }
         if($otp){

            $response = Http::withHeaders([
                'Authorization' => "Bearer 162|" . env('SMS_TOKEN'),
             ])->get("https://sms.msegs.in/api/send-otp",[
                'template_id' => $templateId,
                'message' => $message,
                'recipient'=>$mPhones
             ]);
         }else{
                $response = Http::withHeaders([
                    'Authorization' => "Bearer 162|" . env('SMS_TOKEN'),
                 ])->get("https://sms.msegs.in/api/send-sms",[
                    'template_id' => $templateId,
                    'message' => $message,
                    'recipient'=>$mPhones
                 ]);            
         
         
            }
        return $response;
    }
}
