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
        return response()->json([
          'success' => true,
          'message' => 'Gagal menambahkan gaya rambut'
        ], 500);
      }

      return response()->json([
        'success' => true,
        'message' => 'Gaya rambut berhasil ditambahkan'
      ], 201);
    }

    public function getHaircut(/* Request $request */) {
      
      try {
        $HaircutList = DB::table('haircut')->get();
        $HaircutImages = DB::table('haircut_images')->get(); 
      } catch (\Throwable $th) {
        // Jika terjadi error saat get haircutlist
        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan daftar gaya rambut',
            'error' => $th->getMessage()
        ], 500);
      }
      // Decode JSON menjadi array PHP
      $haircutlistArr = json_decode($HaircutList, true);
      $haircutimagesArr = json_decode($HaircutImages, true);

      // Merging products dengan images
      foreach ($haircutlistArr as &$haircut) {
        // Filter gambar berdasarkan product_id
        $haircut['images'] = array_filter($haircutimagesArr, function($image) use ($haircut) {
            return $image['hair_style_id'] == $haircut['id'];
        });

        // Hapus kolom hair_style_id dalam setiap gambar
        foreach ($haircut['images'] as &$image) {
            unset($image['hair_style_id']);
        }

        // Reindex array 'images' untuk mendapatkan index yang berurutan
        $haircut['images'] = array_values($haircut['images']);
      }

      // // Output hasil merge
      // // echo json_encode($productlistArr, JSON_PRETTY_PRINT);

      return response()->json(data: [
        'success' => true,
        'message' => 'Data semua gaya rambut berhasil didapatkan',
        'data' => $haircutlistArr
      ], status: 200);
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