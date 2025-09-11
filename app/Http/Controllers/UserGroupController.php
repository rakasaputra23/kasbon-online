<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserGroup;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the user groups.
     */
    public function index()
    {
        return view('users.usergroup');
    }

    /**
     * Get user groups data for DataTables.
     */
    public function getData(Request $request)
    {
        $userGroups = UserGroup::withCount('users');

        return DataTables::of($userGroups)
            ->addIndexColumn()
            ->addColumn('total_users', function ($userGroup) {
                return $userGroup->users_count;
            })
            ->addColumn('tanggal_dibuat', function ($userGroup) {
                return $userGroup->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($userGroup) {
                $actions = '';
                $actions .= '<button class="btn btn-sm btn-info me-1" onclick="viewUserGroup(' . $userGroup->id . ')" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button class="btn btn-sm btn-warning me-1" onclick="editUserGroup(' . $userGroup->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteUserGroup(' . $userGroup->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created user group in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:user_groups,name',
            'description' => 'nullable|string',
        ]);

        $userGroup = UserGroup::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil ditambahkan.',
            'data' => $userGroup
        ]);
    }

    /**
     * Display the specified user group.
     */
    public function show($id)
    {
        $userGroup = UserGroup::withCount('users')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $userGroup
        ]);
    }

    /**
     * Update the specified user group in storage.
     */
    public function update(Request $request, $id)
    {
        $userGroup = UserGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:user_groups,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $userGroup->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil diupdate.',
            'data' => $userGroup
        ]);
    }

    /**
     * Remove the specified user group from storage.
     */
    public function destroy($id)
    {
        $userGroup = UserGroup::findOrFail($id);
        
        // Check if user group has users
        if ($userGroup->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'User Group tidak dapat dihapus karena masih memiliki user.'
            ], 403);
        }

        $userGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'User Group berhasil dihapus.'
        ]);
    }

    /**
     * Get permissions for user group.
     */
    public function getPermissions($id)
    {
        $userGroup = UserGroup::findOrFail($id);
        
        // For demo purposes, return empty array
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}