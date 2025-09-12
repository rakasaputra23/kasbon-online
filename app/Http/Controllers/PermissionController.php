<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        return view('users.permission');
    }

    public function getData()
    {
        $permissions = Permission::query();
        
        return DataTables::of($permissions)
            ->addColumn('action', function($permission) {
                $buttons = '<div class="btn-group" role="group">';
                
                if (Auth::user()->hasPermission('permissions.show')) {
                    $buttons .= '<button type="button" class="btn btn-sm btn-info" onclick="viewPermission('.$permission->id.')" title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>';
                }
                
                if (Auth::user()->hasPermission('permissions.update')) {
                    $buttons .= '<button type="button" class="btn btn-sm btn-warning" onclick="editPermission('.$permission->id.')" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>';
                }
                
                if (Auth::user()->hasPermission('permissions.destroy')) {
                    $buttons .= '<button type="button" class="btn btn-sm btn-danger" onclick="deletePermission('.$permission->id.')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>';
                }
                
                $buttons .= '</div>';
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_name' => 'required|string|unique:permissions',
            'deskripsi' => 'required|string|max:255'
        ]);

        try {
            Permission::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Permission: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'route_name' => 'required|string|unique:permissions,route_name,'.$id,
            'deskripsi' => 'required|string|max:255'
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Permission: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Permission: ' . $e->getMessage()
            ], 500);
        }
    }
}