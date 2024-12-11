<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class HaircutController extends Controller {  
    public function setHaircut(Request $request) {
      $validated = $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
      ]);

      try {
        DB::table('haircut')->insert($validated);
      } catch (\Exception $e) {
        
      }

      return response()->json([
        'success' => true,
        'message' => 'Gaya rambut berhasil ditambahkan'
      ], 201);
    }

    public function getHaircut(Request $request) {
      
      try {
        $haircutlist = DB::table('haircut')->get(); 
      } catch (\Throwable $th) {
        // Jika terjadi error saat get haircutlist
        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan daftar gaya rambut',
            'error' => $th->getMessage()
        ], 500);
      }

      return response()->json([
        'success' => true,
        'message' => 'Data semua gaya rambut berhasil didapatkan',
        'data' => $haircutlist
      ], 200);
    }

    public function updateHaircut(Request $request) {
      $validated = $request->validate([
        'id' => 'int',
        'name' => 'required|string',
        'description' => 'required|string',
      ]);

      // Simpan data ke database
      try {
        DB::table('haircut')
        ->where('id', $validated['id'])
        ->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
      } catch (\Exception $e) {
        // Jika terjadi error saat insert
        return response()->json([
          'success' => false,
          'message' => 'Gagal memperbarui data gaya rambut',
          'error' => $e->getMessage()
        ], 500);
      }

      return response()->json([
        'success' => true,
        'message' => 'Data gaya rambut berhasil diperbarui',
      ], 200);
    }
    public function destroyHaircut(Request $request) {

    }
  }
?>