<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DataTables;

class UserGroupController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('deskripsi')->get();
        return view('users.usergroup', compact('permissions'));
    }

    public function getData()
    {
        $userGroups = UserGroup::withCount('users')->get();
        
        return response()->json([
            'data' => $userGroups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'nama' => $group->name ?? $group->nama,
                    'description' => $group->description,
                    'users_count' => $group->users_count,
                    'created_at' => $group->created_at,
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $userGroup = UserGroup::create([
                'name' => $request->nama,
                'description' => $request->description ?? null
            ]);

            if ($request->has('permissions')) {
                $userGroup->permissions()->attach($request->permissions);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan User Group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $userGroup = UserGroup::with(['permissions', 'users'])->findOrFail($id);
        
        return response()->json([
            'userGroup' => [
                'id' => $userGroup->id,
                'nama' => $userGroup->name ?? $userGroup->nama,
                'description' => $userGroup->description,
                'created_at' => $userGroup->created_at,
            ],
            'permissions' => $userGroup->permissions,
            'users' => $userGroup->users
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $userGroup = UserGroup::findOrFail($id);
            
            // Prevent editing superadmin group name
            if ($userGroup->id === 1 && $request->nama !== 'Superadmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama grup Superadmin tidak dapat diubah'
                ], 403);
            }

            $userGroup->update([
                'name' => $request->nama,
                'description' => $request->description ?? $userGroup->description
            ]);

            if ($request->has('permissions')) {
                $userGroup->permissions()->sync($request->permissions);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui User Group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Grup Superadmin tidak dapat dihapus'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $userGroup = UserGroup::findOrFail($id);
            
            // Check if group has users
            if ($userGroup->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'User Group tidak dapat dihapus karena masih memiliki user'
                ], 400);
            }

            $userGroup->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus User Group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPermissions($id)
    {
        $userGroup = UserGroup::with('permissions')->findOrFail($id);
        $allPermissions = Permission::orderBy('deskripsi')->get();
        $assignedPermissions = $userGroup->permissions->pluck('id')->toArray();

        // Group permissions by category (optional)
        $groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            $category = $this->getPermissionCategory($permission->route_name);
            if (!isset($groupedPermissions[$category])) {
                $groupedPermissions[$category] = [];
            }
            $groupedPermissions[$category][] = $permission;
        }

        return response()->json([
            'success' => true,
            'userGroup' => [
                'id' => $userGroup->id,
                'nama' => $userGroup->name ?? $userGroup->nama
            ],
            'allPermissions' => $allPermissions,
            'assignedPermissions' => $assignedPermissions,
            'groupedPermissions' => $groupedPermissions
        ]);
    }

    private function getPermissionCategory($routeName)
    {
        if (str_contains($routeName, 'user.group')) {
            return 'User Group Management';
        } elseif (str_contains($routeName, 'user.')) {
            return 'User Management';
        } elseif (str_contains($routeName, 'permissions.')) {
            return 'Permission Management';
        } elseif (str_contains($routeName, 'profile')) {
            return 'Profile';
        } else {
            return 'General';
        }
    }
}