<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\AdminResource;
use App\Http\Resources\AdminResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Bitfumes\Multiauth\Model\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AdminResourceCollection
     */
    public function index()
    {
        $admins = Admin::with('roles')->get();
        return new AdminResourceCollection($admins);
    }

    /**
     * Display the specified resource.
     *
     * @param Admin $admin
     * @return AdminResource
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin->load('roles'));
    }

    /**
     * Registration for new User
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => "nullable|email|max:255|unique:admins,email",
            'password' => 'required|min:6',
        ]);
        $admin = new Admin();
        $admin->name = request('name');
        $admin->email = request('email');
        $admin->password = bcrypt(request('password'));
         $admin->active = 1;
        ;
        if ($admin->save()){
            return \response()->json(array('error' => false,'success'=> "New User Created"),200);
        }
        return \response()->json(array('error'=> "Something went wrong !!"),400);


    }

    /**
     * Mobile Application Authentication Login.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => "required",
            'password' => 'required|min:6'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response([
                'message' => ['The provided credentials are incorrect.'],
            ],200);
        }

        $token = $admin->createToken('authToken')->accessToken;

        // All Admin Roles
        $user_roles = $admin->roles()->pluck('name')->toArray();
        // If The Admin Is A Doctor
//        if (in_array('doctor', $user_roles)) {
//            $admin = $admin->load('doctor');
//        } elseif (in_array('assistant', $user_roles)) {
//            $admin = $admin->load('assistant');
//        } elseif (in_array('patient', $user_roles)) {
//            $admin = $admin->load('patient');
//        } else {
//        }

        $response = [
            'error' => false,
            'user' => $admin,
            'token' => $token
        ];

        return response($response, 200);
    }

    /**
     * Mobile Application Authentication Logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => "required",
            'device_name' => 'required'
        ]);

        $admin = Admin::find($request->admin_id);

        $admin->tokens()->where('name', $request->device_name)->delete();

        return response()->json(null, 204);
    }

    /**
     * User List
     * Mode : Public
     */
    public function lists()
    {
        $lists = Admin::all();
        return new AdminResourceCollection($lists);
    }

    /**
     * Get Current User Profile
     */

    public function profile()
    {
        if (auth()->user()){
            $admin_ad = auth()->user()->id;
            $data = Admin::with('roles')->find($admin_ad);
            return new AdminResource($data);
        }
        return response()->json(array('message' => 'Something Went Wrong', 'error'=>true), 500);

    }
}
