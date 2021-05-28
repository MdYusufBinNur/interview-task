<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResourceCollection;
use Bitfumes\Multiauth\Console\Commands\PermissionCommand;
use Bitfumes\Multiauth\Http\Controllers\AdminRoleController;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Permission;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Create Role
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
       $role = new Role();
       $checkRole = Role::query()
           ->where('name','=', $request->name)
           ->first();
       if (!empty($checkRole)){
           return response()->json(array('error' => $request->name. ' already exist'), 500);
       }
       $role->name = $request->name;
       if ($role->save()){
           $role->addPermission($request->permissions);
           return response()->json(array('error' => false, 'message' => 'New Role Added', 'data' => $role), 200);
       }
       return response()->json(array('error' => 'Something Went Wrong'), 500);
    }

    /**
     * Role List
     * Mode Public
     */
    public function lists()
    {
        $roles = Role::all();
        return new RoleResourceCollection($roles);
    }

    /**
     * Assign Role to am User
     * @param Request $request
     * @return JsonResponse
     */
    public function assignRole(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => ['required'],
            'role_id' => ['required'],
        ]);

        $admin_id = $request->admin_id;
        $role_id = $request->role_id;

        $role   = Role::findOrFail($role_id);
        $admin  = Admin::findOrFail($admin_id);

        $checkSameRoleExist = DB::table('admin_role')
            ->where('admin_id','=', $admin_id)
            ->first();
        if (empty($checkSameRoleExist)){
            $admin->roles()->attach($role->id);
            return response()->json(array('message' => 'New Role Assigned','error'=> false), 200);
        }


//        $permission->attach($admin_id, $role_id);
//        $permission = DB::table('admin_role')->insert([
//            'admin_id' => $admin_id,
//            'role_id' => $role_id
//        ]);

        return response()->json(array('message' => 'Something Went Wrong', 'error'=>true), 500);
    }

    /**
     * Get User with role
     */

    public function getUserWithRole()
    {
      return  $users = DB::table('admin_role')
            ->leftJoin('admins','admins.id','=','admin_role.admin_id')
            ->leftJoin('roles','roles.id','=','admin_role.role_id')
            ->select('admin_role.*','admins.name as admin','roles.name as role')
            ->get()
            ;

    }
    /**
     * Delete Assigned Role From an User
     * @param Request $request
     * @return JsonResponse
     */
    public function removeRole(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => ['required'],
            'role_id' => ['required'],
        ]);

        $admin_id = $request->admin_id;
        $role_id = $request->role_id;

        $role   = Role::findOrFail($role_id);
        $admin  = Admin::findOrFail($admin_id);

        $checkSameRoleExist = DB::table('admin_role')
            ->where('admin_id','=', $admin_id)
            ->first();
        if (!empty($checkSameRoleExist)){
            $admin->roles()->detach($role->id);
            return response()->json(array('message' => 'Role Detached Successfully','error'=> false), 200);
        }

        return response()->json(array('message' => 'Something Went Wrong', 'error'=>true), 500);
    }
}
