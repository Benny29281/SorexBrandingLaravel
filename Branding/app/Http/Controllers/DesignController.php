<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignController extends Controller
{
    // Di DesignController.php
public function index(Request $request)
{
    // Ambil dari request, jika kosong gunakan tahun sekarang
    $selectedYear = $request->get('year', date('Y'));

    return view('admin.dashboard_design', compact('selectedYear'));
}
}