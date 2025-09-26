<?php
// app/Http/Controllers/PpkController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Ppk;
use App\Models\PpkDetail;
use App\Models\PpkApprovalLog;
use App\Models\MasterUnit;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PpkController extends Controller
{
    /**
     * Display a listing of PPK
     */
    public function index()
    {
        return view('ppk.index');
    }

    /**
     * Get data for DataTables with role-based filtering
     */
    public function getData(Request $request)
    {
        $user = Auth::user();
        
        // Enhanced logging untuk debug
        \Log::info('PPK getData called', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_level' => $user->getApprovalLevel(),
            'user_divisi' => $user->divisi ?? 'No divisi',
            'is_admin' => $user->isSuperAdmin(),
            'is_approver' => $user->isApprover(),
            'request_filters' => [
                'status' => $request->status,
                'divisi' => $request->divisi,
                'periode' => $request->periode
            ]
        ]);
        
        // Get PPK data with enhanced user filtering
        $query = Ppk::with(['creator', 'details'])
                    ->forUser($user) // Menggunakan scope yang sudah diupdate
                    ->select('ppk.*');

        // Apply additional filters
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('divisi') && $request->divisi !== '') {
            $query->where('divisi', 'like', '%' . $request->divisi . '%');
        }

        if ($request->filled('periode') && $request->periode !== '') {
            $periode = $request->periode; // Format: YYYY-MM
            $query->whereRaw('DATE_FORMAT(diajukan_tanggal, "%Y-%m") = ?', [$periode]);
        }

        // Debug query
        \Log::info('PPK Query SQL', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        return DataTables::eloquent($query)
            ->addColumn('action', function ($ppk) use ($user) {
                return $this->generateActionButtons($ppk, $user);
            })
            ->addColumn('status_badge', function ($ppk) {
                return '<span class="badge badge-' . $ppk->status_color . '">' . $ppk->status_label . '</span>';
            })
            ->addColumn('total_formatted', function ($ppk) {
                return 'Rp ' . number_format($ppk->total_nilai, 0, ',', '.');
            })
            ->addColumn('creator_name', function ($ppk) {
                return $ppk->creator ? $ppk->creator->nama : '-';
            })
            ->addColumn('divisi_info', function ($ppk) use ($user) {
                $divisiInfo = $ppk->divisi ?? '-';
                // Highlight jika divisi sama dengan user (untuk approval1)
                if ($user->getApprovalLevel() === 'approval1' && $ppk->divisi === $user->divisi) {
                    $divisiInfo .= ' <small class="text-primary">(Divisi Anda)</small>';
                }
                return $divisiInfo;
            })
            ->addColumn('jumlah_aktivitas', function ($ppk) {
                return $ppk->details->count();
            })
            ->editColumn('diajukan_tanggal', function ($ppk) {
                return $ppk->diajukan_tanggal ? $ppk->diajukan_tanggal->format('d/m/Y') : '-';
            })
            ->editColumn('kembali_tanggal', function ($ppk) {
                return $ppk->kembali_tanggal ? $ppk->kembali_tanggal->format('d/m/Y') : '-';
            })
            ->editColumn('divisi', function ($ppk) use ($user) {
                return $this->formatDivisiForUser($ppk, $user);
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('no_dokumen', 'like', "%{$searchValue}%")
                          ->orWhere('divisi', 'like', "%{$searchValue}%")
                          ->orWhereHas('creator', function($creatorQuery) use ($searchValue) {
                              $creatorQuery->where('nama', 'like', "%{$searchValue}%");
                          });
                    });
                }
            })
            ->rawColumns(['action', 'status_badge', 'divisi_info'])
            ->make(true);
    }

     /**
     * Format divisi display berdasarkan user level
     */
    private function formatDivisiForUser($ppk, $user)
    {
        $divisi = $ppk->divisi ?? '-';
        
        // Highlight untuk approval1 jika divisi sama
        if ($user->getApprovalLevel() === 'approval1' && $ppk->divisi === $user->divisi) {
            return '<span class="text-primary font-weight-bold">' . $divisi . '</span> <small class="text-muted">(Divisi Anda)</small>';
        }
        
        return $divisi;
    }

    /**
     * Get divisi options for filter dropdown
     * Updated to use MasterUnit data
     */
    public function getDivisiOptions()
    {
        try {
            // Get divisi from MasterUnit (external database)
            $divisiList = MasterUnit::getDivisiList();
            
            // Convert to format expected by frontend
            $divisiOptions = collect($divisiList)
                            ->sort()
                            ->map(function($divisi) {
                                return [
                                    'value' => $divisi,
                                    'name' => $divisi
                                ];
                            })
                            ->values();

            return response()->json([
                'success' => true,
                'data' => $divisiOptions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data divisi: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Simplified action buttons - single edit button for both modes
     */
    private function generateActionButtons($ppk, $user)
    {
        $actions = [];
        
        // View button
        if ($user->canViewPpk($ppk)) {
            $actions[] = '<a href="' . route('ppk.show', $ppk->id) . '" class="btn btn-sm btn-info" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                          </a>';
        }

        // Single Edit button (handles both full edit and lampiran edit automatically)
        if (($ppk->canBeEdited() || $ppk->canEditLampiran($user)) && 
            ($ppk->dibuat_oleh === $user->id || $user->isSuperAdmin())) {
            $actions[] = '<a href="' . route('ppk.edit', $ppk->id) . '" class="btn btn-sm btn-warning" title="Edit PPK">
                            <i class="fas fa-edit"></i>
                          </a>';
        }

        // Submit button
        if ($ppk->status === 'draft' && $ppk->dibuat_oleh === $user->id) {
            $actions[] = '<button type="button" class="btn btn-sm btn-success submit-btn" data-id="' . $ppk->id . '" title="Submit">
                            <i class="fas fa-paper-plane"></i>
                          </button>';
        }

        // Delete button (only draft status)
        if ($ppk->canBeDeleted() && ($ppk->dibuat_oleh === $user->id || $user->isSuperAdmin())) {
            $actions[] = '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $ppk->id . '" title="Hapus">
                            <i class="fas fa-trash"></i>
                          </button>';
        }

        // PDF button
        if ($ppk->status === 'approved' && $user->canViewPpk($ppk)) {
            $actions[] = '<a href="' . route('ppk.pdf', $ppk->id) . '" target="_blank" class="btn btn-sm btn-secondary" title="PDF">
                            <i class="fas fa-file-pdf"></i>
                          </a>';
        }

        return '<div class="btn-group" role="group">' . implode('', $actions) . '</div>';
    }


    /**
     * Show the form for creating a new PPK
     */
    public function create()
    {
        // Only allow Pegawai and Admin to create PPK
        $user = Auth::user();
        if (!$user->isPegawai() && !$user->isSuperAdmin()) {
            return redirect()->route('ppk.index')
                           ->with('error', 'Hanya Pegawai yang dapat membuat PPK baru.');
        }

        return view('ppk.create');
    }

    /**
     * Store a newly created PPK with auto-generated document number
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only allow Pegawai and Admin to create PPK
        if (!$user->isPegawai() && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Pegawai yang dapat membuat PPK baru.'
            ], 403);
        }

        $request->validate([
            'divisi' => 'required|string|max:255',
            'kode_unit' => 'nullable|string|max:100',
            'kode_anggaran' => 'nullable|string|max:100',
            'diajukan_tanggal' => 'required|date',
            'kembali_tanggal' => 'required|date|after:diajukan_tanggal',
            'jangka_waktu' => 'nullable|string|max:100',
            'aktivitas' => 'required|array|min:1',
            'aktivitas.*.tanggal_aktivitas' => 'required|date',
            'aktivitas.*.aktivitas' => 'required|string',
            'aktivitas.*.rencana' => 'required|numeric|min:0',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'delete_files' => 'nullable|array',
            'delete_files.*' => 'string'
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor dokumen dengan lock untuk menghindari race condition
            $nomorDokumen = $this->generateNomorDokumen();
            
            // Handle file uploads first
            $uploadedFiles = [];
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('ppk/lampiran', $filename, 'public');
                    $uploadedFiles[] = $path;
                }
            }

            // Calculate total nilai from aktivitas
            $totalNilai = collect($request->aktivitas)->sum('rencana');

            // Create PPK with generated document number
            $ppk = Ppk::create([
                'no_dokumen' => $nomorDokumen,
                'divisi' => $request->divisi,
                'kode_unit' => $request->kode_unit,
                'kode_anggaran' => $request->kode_anggaran,
                'diajukan_tanggal' => $request->diajukan_tanggal,
                'kembali_tanggal' => $request->kembali_tanggal,
                'jangka_waktu' => $request->jangka_waktu,
                'total_nilai' => $totalNilai,
                'dibuat_oleh' => Auth::id(),
                'status' => Ppk::STATUS_DRAFT,
                'lampiran' => !empty($uploadedFiles) ? $uploadedFiles : null,
            ]);

            // Create PPK Details
            foreach ($request->aktivitas as $index => $aktivitas) {
                PpkDetail::create([
                    'ppk_id' => $ppk->id,
                    'no_aktivitas' => $index + 1,
                    'tanggal_aktivitas' => $aktivitas['tanggal_aktivitas'],
                    'aktivitas' => $aktivitas['aktivitas'],
                    'rencana' => $aktivitas['rencana'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PPK berhasil dibuat dengan nomor dokumen: ' . $nomorDokumen,
                'data' => [
                    'id' => $ppk->id,
                    'no_dokumen' => $nomorDokumen
                ],
                'redirect' => route('ppk.show', $ppk->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files if error occurs
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate nomor dokumen secara otomatis dengan format PPK-YYYY-NNNN
     */
    private function generateNomorDokumen()
    {
        $year = date('Y');
        $maxRetries = 5;
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                // Lock untuk mencegah race condition
                DB::beginTransaction();
                
                $lastPpk = Ppk::where('no_dokumen', 'LIKE', "PPK-{$year}-%")
                    ->lockForUpdate()
                    ->orderByRaw('CAST(SUBSTRING(no_dokumen, -4) AS UNSIGNED) DESC')
                    ->first();
                
                if ($lastPpk) {
                    // Extract nomor urut dari no_dokumen terakhir
                    $lastNumber = (int) substr($lastPpk->no_dokumen, -4);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }
                
                // Format nomor dokumen: PPK-YYYY-0001
                $nomorDokumen = "PPK-{$year}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                
                // Check if number already exists (double check)
                $exists = Ppk::where('no_dokumen', $nomorDokumen)->exists();
                
                if (!$exists) {
                    DB::commit();
                    return $nomorDokumen;
                }
                
                DB::rollback();
                $attempt++;
                
            } catch (\Exception $e) {
                DB::rollback();
                $attempt++;
                
                if ($attempt >= $maxRetries) {
                    throw new \Exception('Gagal generate nomor dokumen setelah ' . $maxRetries . ' percobaan: ' . $e->getMessage());
                }
                
                // Wait a bit before retry
                usleep(100000); // 0.1 second
            }
        }
        
        throw new \Exception('Gagal generate nomor dokumen setelah ' . $maxRetries . ' percobaan');
    }

  /**
     * Get next document number for preview (AJAX)
     */
    public function getNextNumber()
    {
        try {
            $year = date('Y');
            
            // Get last number without lock (for preview only)
            $lastPpk = Ppk::where('no_dokumen', 'LIKE', "PPK-{$year}-%")
                ->orderByRaw('CAST(SUBSTRING(no_dokumen, -4) AS UNSIGNED) DESC')
                ->first();
            
            if ($lastPpk) {
                $lastNumber = (int) substr($lastPpk->no_dokumen, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            
            // Format nomor dokumen: PPK-YYYY-0001
            $nomorDokumen = "PPK-{$year}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            return response()->json([
                'success' => true,
                'no_dokumen' => $nomorDokumen,
                'note' => 'Preview - nomor final akan di-generate saat data disimpan'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating document number preview',
                'fallback' => true
            ], 500);
        }
    }

    /**
     * Get Divisi List for AJAX (for create/edit forms)
     * Updated to use MasterUnit data
     */
    public function getDivisiList()
    {
        try {
            // Get divisi from MasterUnit (external database)
            $allDivisi = MasterUnit::getDivisiList();
            sort($allDivisi);
            
            return response()->json($allDivisi);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load divisi data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified PPK
     */
    public function show($id)
    {
        $ppk = Ppk::with(['creator', 'details', 'approvalLogs.user'])->findOrFail($id);
        
        // Check if user can view this PPK
        $user = Auth::user();
        if (!$user->canViewPpk($ppk)) {
            return redirect()->route('ppk.index')
                           ->with('error', 'Anda tidak memiliki akses untuk melihat PPK ini.');
        }
        
        return view('ppk.show', compact('ppk'));
    }

    /**
     * Show the form for editing PPK (unified - determines edit mode automatically)
     */
    public function edit($id)
    {
        $ppk = Ppk::with(['details'])->findOrFail($id);
        $user = Auth::user();
        
        // Check basic access
        if ($ppk->dibuat_oleh !== $user->id && !$user->isSuperAdmin()) {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'Anda tidak memiliki akses untuk mengedit PPK ini.');
        }

        // Determine what can be edited based on PPK status
        $editMode = $ppk->canBeEdited() ? 'full' : ($ppk->canEditLampiran($user) ? 'lampiran_only' : 'none');
        
        if ($editMode === 'none') {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'PPK ini tidak dapat diedit.');
        }

        return view('ppk.edit', compact('ppk', 'editMode'));
    }

    /**
     * Update PPK (handles both full edit and lampiran-only based on status)
     */
    public function update(Request $request, $id)
    {
        $ppk = Ppk::findOrFail($id);
        $user = Auth::user();
        
        // Check access
        if ($ppk->dibuat_oleh !== $user->id && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengedit PPK ini.'
            ], 403);
        }

        // Determine edit mode
        $canEditFull = $ppk->canBeEdited();
        $canEditLampiran = $ppk->canEditLampiran($user);
        
        if (!$canEditFull && !$canEditLampiran) {
            return response()->json([
                'success' => false,
                'message' => 'PPK ini tidak dapat diedit.'
            ], 403);
        }

        // Validation based on edit mode
        if ($canEditFull) {
            // Full edit validation
            $request->validate([
                'divisi' => 'required|string|max:255',
                'kode_unit' => 'nullable|string|max:100',
                'kode_anggaran' => 'nullable|string|max:100',
                'diajukan_tanggal' => 'required|date',
                'kembali_tanggal' => 'required|date|after:diajukan_tanggal',
                'jangka_waktu' => 'nullable|string|max:100',
                'aktivitas' => 'required|array|min:1',
                'aktivitas.*.tanggal_aktivitas' => 'required|date',
                'aktivitas.*.aktivitas' => 'required|string',
                'aktivitas.*.rencana' => 'required|numeric|min:0',
                'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
                'delete_files' => 'nullable|array',
            ]);
        } else {
            // Lampiran-only validation
            $request->validate([
                'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
                'delete_files' => 'nullable|array',
            ]);
        }

        DB::beginTransaction();
        try {
            $updateData = [];

            // Handle full edit
            if ($canEditFull) {
                $totalNilai = collect($request->aktivitas)->sum('rencana');
                
                $updateData = [
                    'divisi' => $request->divisi,
                    'kode_unit' => $request->kode_unit,
                    'kode_anggaran' => $request->kode_anggaran,
                    'diajukan_tanggal' => $request->diajukan_tanggal,
                    'kembali_tanggal' => $request->kembali_tanggal,
                    'jangka_waktu' => $request->jangka_waktu,
                    'total_nilai' => $totalNilai,
                ];
                
                // Update details
                $ppk->details()->delete();
                foreach ($request->aktivitas as $index => $aktivitas) {
                    PpkDetail::create([
                        'ppk_id' => $ppk->id,
                        'no_aktivitas' => $index + 1,
                        'tanggal_aktivitas' => $aktivitas['tanggal_aktivitas'],
                        'aktivitas' => $aktivitas['aktivitas'],
                        'rencana' => $aktivitas['rencana'],
                    ]);
                }
            }

            // Handle lampiran (both modes)
            $currentFiles = $ppk->lampiran ?? [];
            
            // Delete files
            if ($request->has('delete_files') && is_array($request->delete_files)) {
                foreach ($request->delete_files as $fileToDelete) {
                    $currentFiles = array_filter($currentFiles, function($file) use ($fileToDelete) {
                        return basename($file) !== basename($fileToDelete);
                    });
                    
                    if (Storage::disk('public')->exists($fileToDelete)) {
                        Storage::disk('public')->delete($fileToDelete);
                    }
                }
                $currentFiles = array_values($currentFiles);
            }
            
            // Upload new files
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('ppk/lampiran', $filename, 'public');
                    $currentFiles[] = $path;
                }
            }

            $updateData['lampiran'] = !empty($currentFiles) ? $currentFiles : null;

            // Update PPK
            $ppk->update($updateData);

            DB::commit();

            $message = $canEditFull ? 'PPK berhasil diupdate.' : 'Lampiran PPK berhasil diupdate.';
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('ppk.show', $ppk->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIXED: Show approval page for PPK
     */
    public function showApproval($id)
    {
        $ppk = Ppk::with(['creator', 'details', 'approvalLogs.user'])->findOrFail($id);
        
        // Check if user can approve this PPK
        $user = Auth::user();
        
        if (!$ppk->canBeApproved()) {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'PPK tidak dapat di-approve pada status saat ini.');
        }

        // Check user approval permission
        if (!$user->canApprovePpk($ppk)) {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'Anda tidak memiliki akses untuk approve PPK ini.');
        }

        // Prevent owner from approving their own PPK
        if ($ppk->dibuat_oleh === $user->id) {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'Anda tidak dapat approve PPK yang Anda buat sendiri.');
        }
        
        return view('ppk.approval', compact('ppk'));
    }

    /**
     * Submit PPK for approval
     */
    public function submit($id)
    {
        $ppk = Ppk::findOrFail($id);
        $user = Auth::user();
        
        // Check if user is owner
        if ($ppk->dibuat_oleh !== $user->id && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat submit PPK ini.'
            ], 403);
        }
        
        if ($ppk->status !== Ppk::STATUS_DRAFT) {
            return response()->json([
                'success' => false,
                'message' => 'PPK sudah disubmit sebelumnya.'
            ], 400);
        }

        // Validate PPK has at least one activity
        if ($ppk->details->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'PPK harus memiliki minimal satu aktivitas sebelum disubmit.'
            ], 400);
        }

        if ($ppk->submit()) {
            return response()->json([
                'success' => true,
                'message' => 'PPK berhasil disubmit untuk approval.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal submit PPK.'
        ], 500);
    }

    /**
     * FIXED: Approve PPK
     */
    public function approve(Request $request, $id)
    {
        $ppk = Ppk::findOrFail($id);
        $user = Auth::user();
        $currentLevel = $ppk->getCurrentApprovalLevel();

        // Basic validation
        $validation = [
            'catatan' => 'nullable|string'
        ];

        // Add disposisi validation for approval2 (Kepala Departemen)
        if ($currentLevel === 'approval2') {
            $validation = array_merge($validation, [
                'disposisi_dana_tersedia' => 'required|numeric|min:0',
                'disposisi_dana_terpakai' => 'required|numeric|min:0|lte:disposisi_dana_tersedia',
                'disposisi_sisa_anggaran' => 'required|numeric|min:0'
            ]);
        }

        $request->validate($validation);

        // Security checks
        if (!$ppk->canBeApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'PPK tidak dapat disetujui pada status saat ini.'
            ], 400);
        }

        if (!$currentLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Level approval tidak dapat ditentukan.'
            ], 400);
        }

        if (!$user->canApproveAtLevel($currentLevel)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk approval di level ini.'
            ], 403);
        }

        // Prevent owner from approving their own PPK
        if ($ppk->dibuat_oleh === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat approve PPK yang Anda buat sendiri.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Prepare disposisi data for approval2
            $disposisiData = null;
            if ($currentLevel === 'approval2') {
                $disposisiData = [
                    'dana_tersedia' => $request->disposisi_dana_tersedia,
                    'dana_terpakai' => $request->disposisi_dana_terpakai,
                    'sisa_anggaran' => $request->disposisi_sisa_anggaran
                ];
            }

            // Create approval log FIRST
            PpkApprovalLog::createApprovalLog(
                $ppk->id,
                $user->id,
                $currentLevel,
                PpkApprovalLog::AKSI_APPROVE,
                $request->catatan,
                $disposisiData
            );

            // Then update PPK status to next level
            if (!$ppk->approve()) {
                throw new \Exception('Gagal mengupdate status PPK');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PPK berhasil disetujui.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('PPK Approve Error: ' . $e->getMessage(), [
                'ppk_id' => $id,
                'user_id' => $user->id,
                'current_level' => $currentLevel,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FIXED: Reject PPK
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|min:10',
        ]);

        $ppk = Ppk::findOrFail($id);
        $user = Auth::user();
        $currentLevel = $ppk->getCurrentApprovalLevel();
        
        // Security checks
        if (!$ppk->canBeApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'PPK tidak dapat ditolak pada status saat ini.'
            ], 400);
        }

        if (!$currentLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Level approval tidak dapat ditentukan.'
            ], 400);
        }

        if (!$user->canApproveAtLevel($currentLevel)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk rejection di level ini.'
            ], 403);
        }

        // Prevent owner from rejecting their own PPK
        if ($ppk->dibuat_oleh === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat reject PPK yang Anda buat sendiri.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Create rejection log FIRST
            PpkApprovalLog::createApprovalLog(
                $ppk->id,
                $user->id,
                $currentLevel,
                PpkApprovalLog::AKSI_REJECT,
                $request->catatan
            );

            // Then update PPK status to rejected
            if (!$ppk->reject()) {
                throw new \Exception('Gagal mengupdate status PPK menjadi rejected');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PPK berhasil ditolak.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('PPK Reject Error: ' . $e->getMessage(), [
                'ppk_id' => $id,
                'user_id' => $user->id,
                'current_level' => $currentLevel,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete PPK (only for draft/rejected status and owner)
     */
    public function destroy($id)
    {
        $ppk = Ppk::findOrFail($id);
        $user = Auth::user();

        // Check if can be deleted
        if (!$ppk->canBeDeleted()) {
            return response()->json([
                'success' => false,
                'message' => 'PPK tidak dapat dihapus karena sudah masuk proses approval.'
            ], 403);
        }

        // Check if user is owner or admin
        if ($ppk->dibuat_oleh !== $user->id && !$user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus PPK ini.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Delete attached files
            if ($ppk->lampiran && is_array($ppk->lampiran)) {
                foreach ($ppk->lampiran as $filePath) {
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            // Delete PPK (details will be deleted automatically via cascade)
            $ppk->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PPK berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus PPK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redirect legacy editLampiran route to main edit
     */
    public function editLampiran($id)
    {
        return redirect()->route('ppk.edit', $id);
    }

    /**
     * Generate PDF
     */
    public function generatePdf($id)
    {
        $ppk = Ppk::with(['creator', 'details', 'approvalLogs.user'])->findOrFail($id);
        $user = Auth::user();

        // Check if PPK is approved
        if ($ppk->status !== Ppk::STATUS_APPROVED) {
            return redirect()->route('ppk.show', $id)
                           ->with('error', 'PDF hanya dapat digenerate untuk PPK yang sudah disetujui.');
        }

        // Check if user can access this PPK
        if (!$user->canViewPpk($ppk)) {
            return redirect()->route('ppk.index')
                           ->with('error', 'Anda tidak memiliki akses untuk melihat PPK ini.');
        }
        
        return view('ppk.pdf', compact('ppk'));
    }

     /**
     * Enhanced approval statistics based on user level
     */
    public function getApprovalStats()
    {
        try {
            $user = Auth::user();
            $stats = [];

            \Log::info('PPK getApprovalStats called', [
                'user_id' => $user->id,
                'user_level' => $user->getApprovalLevel(),
                'user_divisi' => $user->divisi ?? 'No divisi'
            ]);

            if ($user->isSuperAdmin()) {
                // Admin melihat stats global
                $stats = [
                    'draft' => Ppk::where('status', Ppk::STATUS_DRAFT)->count(),
                    'pending' => Ppk::where('status', 'like', 'pending_%')->count(),
                    'approved' => Ppk::where('status', Ppk::STATUS_APPROVED)->count(),
                    'rejected' => Ppk::where('status', Ppk::STATUS_REJECTED)->count(),
                ];
            } else {
                // Stats berdasarkan akses user
                $baseQuery = Ppk::forUserStats($user); // Menggunakan scope khusus untuk stats
                
                $stats = [
                    'draft' => (clone $baseQuery)->where('status', Ppk::STATUS_DRAFT)->count(),
                    'pending' => (clone $baseQuery)->where('status', 'like', 'pending_%')->count(),
                    'approved' => (clone $baseQuery)->where('status', Ppk::STATUS_APPROVED)->count(),
                    'rejected' => (clone $baseQuery)->where('status', Ppk::STATUS_REJECTED)->count(),
                ];
            }

            // Get PPK yang butuh approval dari user ini
            $userLevel = $user->getApprovalLevel();
            $pendingForUser = 0;

            if ($userLevel && $userLevel !== 'pegawai') {
                $statusToCheck = 'pending_' . $userLevel;
                
                if ($userLevel === 'approval1') {
                    // Approval1: hitung yang di divisinya saja
                    $pendingForUser = Ppk::where('status', $statusToCheck)
                                        ->where('divisi', $user->divisi)
                                        ->where('dibuat_oleh', '!=', $user->id)
                                        ->count();
                } else {
                    // Approval2-6: hitung semua di levelnya
                    $pendingForUser = Ppk::where('status', $statusToCheck)
                                        ->where('dibuat_oleh', '!=', $user->id)
                                        ->count();
                }
            }

            $stats['pending_for_user'] = $pendingForUser;
            
            // Stats tambahan berdasarkan user level
            if ($userLevel === 'approval1') {
                $stats['divisi_info'] = [
                    'nama' => $user->divisi ?? 'Tidak ada divisi',
                    'total_ppk_divisi' => Ppk::where('divisi', $user->divisi)->count()
                ];
            }

            \Log::info('PPK Stats Result', $stats);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            \Log::error('PPK getApprovalStats Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik approval',
                'data' => []
            ], 500);
        }
    }

    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities()
    {
        try {
            $user = Auth::user();
            
            // Get recent PPK activities based on user access
            $recentPpk = Ppk::with(['creator'])
                           ->forUser($user)
                           ->orderBy('updated_at', 'desc')
                           ->limit(10)
                           ->get()
                           ->map(function($ppk) {
                               return [
                                   'id' => $ppk->id,
                                   'no_dokumen' => $ppk->no_dokumen,
                                   'creator' => $ppk->creator->nama ?? 'Unknown',
                                   'status' => $ppk->status_label,
                                   'status_color' => $ppk->status_color,
                                   'updated_at' => $ppk->updated_at->diffForHumans(),
                                   'total_nilai' => 'Rp ' . number_format($ppk->total_nilai, 0, ',', '.')
                               ];
                           });

            return response()->json([
                'success' => true,
                'data' => $recentPpk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat aktivitas terbaru',
                'data' => []
            ], 500);
        }
    }
}