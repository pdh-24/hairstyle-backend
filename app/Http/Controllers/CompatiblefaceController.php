<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class CompatiblefaceController extends Controller {
    public function getCompatibleface(Request $request) {
      // $validated = $request->validate([
      //   'id' => 'required|integer',
      //   'name' => 'required|string',
      //   'location' => 'required|string',
      //   'contact_info' =>
      // ]);

      try {
        $CompatiblefaceList = DB::table("compatible_faces")->get();
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar wajah kompatibel ',
          'error' => $th->getMessage()
        ], 500);
      }
      return response()->json([
        'success' => true,
        'message' => 'Data semua wajah kompatibel berhasil didapatkan',
        'data' => $CompatiblefaceList
      ], 200);
    }
  }
?>