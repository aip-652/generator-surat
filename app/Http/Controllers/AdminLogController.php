<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
  public function index(Request $request)
  {
    // Ambil parameter sorting dari URL, tentukan nilai default
    $orderBy = $request->input('order_by', 'created_at'); // Default diurutkan berdasarkan waktu
    $sort = $request->input('sort', 'desc');         // Default dari yang terbaru

    // Buat query dengan eager loading dan sorting
    $query = AdminLog::with('user')->orderBy($orderBy, $sort);

    // Ambil data dengan pagination
    $logs = $query->paginate(20);

    // Kirim data dan parameter sorting ke view
    return view('logs.index', compact('logs', 'orderBy', 'sort'));
  }
}
