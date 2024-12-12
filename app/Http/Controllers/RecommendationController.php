<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class RecommendationController extends Controller {

    public function generate(Request $request)
    {
        $request->validate([
            'scan_id' => 'required|exists:scan_histories,id',
            'face_shape' => 'required|string',
        ]);

        $faceShape = $request->input('face_shape');

        $haircuts = DB::table('haircuts')
            ->whereJsonContains('suitable_face_shapes', $faceShape)
            ->get();

        if ($haircuts->isEmpty()) {
            return response()->json(['message' => 'No recommendations found for this face shape'], 404);
        }

        $id = DB::table('recommendations')->insertGetId([
            'scan_id' => $request->input('scan_id'),
            'haircuts' => json_encode($haircuts->pluck('id')->toArray()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Recommendation generated', 'id' => $id]);
    }
    public function getRecommendationById(Request $request) {
      $validated = $request->validate([
        'id' => 'required|integer',
        // 'name' => 'required|string',
        // 'location' => 'required|string',
        // 'contact_info' => ''
      ]);

      try {
        $Recommendation = DB::table("recommendations")->find($validated['id']);
      } catch (\Throwable $th) {
        return response()->json([
          'success' => false,
          'message' => 'Item rekomendasi tidak ditemukan',
          'error' => $th->getMessage()
        ],);
      }
      return response()->json([
        'success' => true,
        'message' => 'Data item rekomendasi berhasil diperoleh',
        'data' => $Recommendation
      ], 200);
    }
    public function getRecommendation(/* Request $request */) {
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