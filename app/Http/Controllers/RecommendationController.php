<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class RecommendationController extends Controller {
    public function getRecommendation(Request $request) {
      // $validated = $request->validate([
      //   'id' => 'required|integer',
      //   'name' => 'required|string',
      //   'location' => 'required|string',
      //   'contact_info' =>
      // ]);

      try {
        $RecommendationList = DB::table("recommendations")->get();
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar rekomendasi',
          'error' => $th->getMessage()
        ], 500);
      }
      return response()->json([
        'success' => true,
        'message' => 'Data semua rekomendasi berhasil didapatkan',
        'data' => $RecommendationList
      ], 200);
    }
  }
?>