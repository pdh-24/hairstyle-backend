<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class BarbershopController extends Controller {
    public function getBarbershop(Request $request) {
      // $validated = $request->validate([
      //   'id' => 'required|integer',
      //   'name' => 'required|string',
      //   'location' => 'required|string',
      //   'contact_info' =>
      // ]);

      try {
        $BarbershopList = DB::table("barbershops")->get();
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar barber shop',
          'error' => $th->getMessage()
        ], 500);
      }
      return response()->json([
        'success' => true,
        'message' => 'Data semua barber shop berhasil didapatkan',
        'data' => $BarbershopList
      ], 200);
    }
  }
?>