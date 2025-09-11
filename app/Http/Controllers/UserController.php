<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\UserGroup;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $userGroups = UserGroup::all();
        return view('users.user', compact('userGroups'));
    }

    /**
     * Get users data for DataTables.
     */
    public function getData(Request $request)
    {
        $users = User::with('userGroup')->select('users.*');

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('user_group', function ($user) {
                return $user->userGroup ? $user->userGroup->name : '-';
            })
            ->addColumn('tanggal_dibuat', function ($user) {
                return $user->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($user) {
                $actions = '';
                $actions .= '<button class="btn btn-sm btn-info me-1" onclick="viewUser(' . $user->id . ')" title="View"><i class="fas fa-eye"></i></button>';
                $actions .= '<button class="btn btn-sm btn-warning me-1" onclick="editUser(' . $user->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:users,nip',
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'user_group_id' => 'required|exists:user_groups,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'user_group_id' => $request->user_group_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan.',
            'data' => $user
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with('userGroup')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip' => ['required', 'string', Rule::unique('users', 'nip')->ignore($user->id)],
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'user_group_id' => 'required|exists:user_groups,id',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'user_group_id' => $request->user_group_id,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate.',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting current user
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Show edit profile form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('users.edit-profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('profile')->with('success', 'Profile berhasil diupdate.');
    }
}