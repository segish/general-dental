<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use App\Models\Admin;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AdminUserController extends Controller
{

    public function __construct(
        private Admin $user,
    ) {
        $this->middleware('checkAdminPermission:user.add-new,index')->only(['index']);
        $this->middleware('checkAdminPermission:user.list,list')->only(['list']);
    }


    public function store(Request $request)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:admins',
            'phone' => 'required|unique:admins',
            'department_id' => 'required|exists:departments,id',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create a new user

        try {
            $userData = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'department_id' => $request->department_id,
                'password' => bcrypt($request->password),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Handle signature upload
            if ($request->hasFile('signature')) {
                $signature = $request->file('signature');
                $signatureName = time() . '_' . $signature->getClientOriginalName();
                $signaturePath = $signature->storeAs('admin_signatures', $signatureName, 'public');
                $userData['signature'] = $signaturePath;
            }

            $user = $this->user->create($userData);

            $user->assignRole($request->input('roles'));

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function index()
    {

        $roles = Role::where('guard_name', 'admin')->get();
        $departments = Department::all();

        return view('admin-views.user.index', compact('roles', 'departments'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->user->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->user->latest();
        }
        $users = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.user.list', compact('users', 'search'));
    }


    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        // Find the user
        $user = Admin::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'roles' => 'required',
            'department_id' => 'required|exists:departments,id',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the user
        try {
            $updateData = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'status' => $request->status,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'updated_at' => now(),
            ];

            // Conditionally include 'password' if it is present in the request
            if ($request->password) {
                $updateData['password'] = bcrypt($request->password);
            }

            // Handle signature upload
            if ($request->hasFile('signature')) {
                // Delete old signature if exists
                if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                    Storage::disk('public')->delete($user->signature);
                }

                $signature = $request->file('signature');
                $signatureName = time() . '_' . $signature->getClientOriginalName();
                $signaturePath = $signature->storeAs('admin_signatures', $signatureName, 'public');
                $updateData['signature'] = $signaturePath;
            }

            $user->update($updateData);

            // Update roles
            $user->syncRoles($request->input('roles'));

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }



    public function destroy($id)
    {
        // Find the user
        $user = Admin::findOrFail($id);

        if ($user->nurse) {
            $user->nurse()->delete();
            $user->delete();
        } else if ($user->doctor) {
            $user->doctor()->delete();
            $user->delete();
        } else {
            $user->delete();
        }
        Toastr::success(translate('User removed!'));
        return back();
    }


    public function create()
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): Factory|View|Application
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $departments = Department::all();

        $user = $this->user->withoutGlobalScopes()->find($id);
        return view('admin-views.user.edit', compact('user', 'roles', 'departments'));
    }
}
