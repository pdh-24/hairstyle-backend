<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class ScanhistoryController extends Controller {
    public function getScanhistory(Request $request) {
      // $validated = $request->validate([
      //   'id' => 'required|integer',
      //   'name' => 'required|string',
      //   'location' => 'required|string',
      //   'contact_info' =>
      // ]);

      try {
        $ScanhistoryList = DB::table("scan_history")->get();
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar riwayat scan wajah',
          'error' => $th->getMessage()
        ], 500);
      }
      return response()->json([
        'success' => true,
        'message' => 'Data semua riwayat scan wajah berhasil didapatkan',
        'data' => $ScanhistoryList
      ], 200);
    }
  }
?>