<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserGroup;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the user groups.
     */
    public function index()
    {
        $permissions = Permission::orderBy('deskripsi')->get();
        return view('users.usergroup', compact('permissions'));
    }

    /**
 * Get user groups data for DataTables.
 */
public function getData(Request $request)
{
    try {
        \Log::info('DataTables Request received', ['request' => $request->all()]);
        
        $userGroups = UserGroup::withCount('users')->select('user_groups.*');

        return DataTables::eloquent($userGroups)  // GUNAKAN eloquent() BUKAN of()
            ->addIndexColumn()
            ->addColumn('users_count', function ($group) {
                return $group->users_count ?? 0;
            })
            ->addColumn('tanggal_dibuat', function ($group) {
                return $group->created_at ? $group->created_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('action', function ($group) {
                $actions = '';
                $actions .= '<button class="btn btn-sm btn-info me-1" onclick="viewUserGroup(' . $group->id . ')" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button class="btn btn-sm btn-secondary me-1" onclick="managePermissions(' . $group->id . ')" title="Permissions"><i class="fas fa-key"></i></button>';
                $actions .= '<button class="btn btn-sm btn-warning me-1" onclick="editUserGroup(' . $group->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                if ($group->id != 1) {
                    $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteUserGroup(' . $group->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                }
                return $actions;
            })
            ->filter(function ($query) use ($request) {
                // TAMBAHKAN FILTER UNTUK SERVER-SIDE PROCESSING
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);

    } catch (\Exception $e) {
        \Log::error('UserGroup DataTables Error: ' . $e->getMessage());
        
        return response()->json([
            'draw' => $request->get('draw', 0),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'Terjadi kesalahan saat memuat data'
        ], 500);
    }
}

    /**
     * Store a newly created user group in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:user_groups,name',
                'description' => 'nullable|string',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            DB::beginTransaction();

            $userGroup = UserGroup::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions') && !empty($request->permissions)) {
                $userGroup->permissions()->attach($request->permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil ditambahkan.',
                'data' => $userGroup
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user group.
     */
    /**
 * Display the specified user group.
 */
public function show($id)
{
    try {
        // GUNAKAN with() UNTUK LOAD RELATIONS
        $userGroup = UserGroup::with(['permissions', 'users'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $userGroup
        ]);

    } catch (\Exception $e) {
        \Log::error('UserGroup show error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}

    /**
     * Update the specified user group in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $userGroup = UserGroup::findOrFail($id);

            $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('user_groups', 'name')->ignore($userGroup->id)],
                'description' => 'nullable|string',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            // Prevent editing Admin group name
            if ($userGroup->id === 1 && $request->name !== 'Admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama grup Admin tidak dapat diubah'
                ], 403);
            }

            DB::beginTransaction();

            $userGroup->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $userGroup->permissions()->sync($request->permissions);
            } else {
                $userGroup->permissions()->detach();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil diupdate.',
                'data' => $userGroup->fresh(['permissions'])
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user group from storage.
     */
    public function destroy($id)
    {
        try {
            // Check if trying to delete Admin group
            if ($id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grup Admin tidak dapat dihapus.'
                ], 403);
            }

            $userGroup = UserGroup::findOrFail($id);
            
            // Check if group has users
            $usersCount = $userGroup->users()->count();
            if ($usersCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'User Group tidak dapat dihapus karena masih memiliki ' . $usersCount . ' user.'
                ], 400);
            }

            DB::beginTransaction();

            // Detach all permissions first
            $userGroup->permissions()->detach();
            
            // Delete the user group
            $deleted = $userGroup->delete();

            if (!$deleted) {
                throw new Exception('Gagal menghapus user group');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User Group berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get permissions for user group.
     */
    public function getPermissions($id)
    {
        try {
            $userGroup = UserGroup::with('permissions')->findOrFail($id);
            $allPermissions = Permission::orderBy('deskripsi')->get();
            $assignedPermissions = $userGroup->permissions->pluck('id')->toArray();

            // Group permissions by category
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
                'userGroup' => $userGroup,
                'allPermissions' => $allPermissions,
                'assignedPermissions' => $assignedPermissions,
                'groupedPermissions' => $groupedPermissions
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data permissions: ' . $e->getMessage()
            ], 500);
        }
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