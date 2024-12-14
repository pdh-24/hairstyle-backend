<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class BarbershopController extends Controller {
    public function getBarbershop(/* Request $request */) {
      // $validated = $request->validate([
      //   'id' => 'required|integer',
      //   'name' => 'required|string',
      //   'location' => 'required|string',
      //   'contact_info' =>
      // ]);

      try {
        $BarbershopList = DB::table("barbershops")->get();
        $BarbershopImages = DB::table('barbershop_images')->get(); 
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar barber shop',
          'error' => $th->getMessage()
        ], 500);
      }
      
      // Decode JSON menjadi array PHP
      $barbershoplistArr = json_decode($BarbershopList, true);
      $barbershopimagesArr = json_decode($BarbershopImages, true);

      // Merging products dengan images
      foreach ($barbershoplistArr as &$barbershop) {
        // Filter gambar berdasarkan product_id
        $barbershop['images'] = array_filter($barbershopimagesArr, function($image) use ($barbershop) {
            return $image['barber_shop_id'] == $barbershop['id'];
        });

        // Hapus kolom barber_shop_id dalam setiap gambar
        foreach ($barbershop['images'] as &$image) {
            unset($image['barber_shop_id']);
        }

        // Reindex array 'images' untuk mendapatkan index yang berurutan
        $barbershop['images'] = array_values($barbershop['images']);
      }

      // // Output hasil merge
      // // echo json_encode($productlistArr, JSON_PRETTY_PRINT);
      return response()->json([
        'success' => true,
        'message' => 'Data semua barber shop berhasil didapatkan',
        'data' => $BarbershopList
      ], 200);
    }
  }
?>