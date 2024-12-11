<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;

  class ProductController extends Controller {
    public function getProduct(Request $request) {
      try {
        $ProductList = DB::table("products")->get();
        $ProductImages = DB::table("product_images")->get();
      } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
          'success' => false,
          'message' => 'Gagal mendapatkan daftar produk',
          'error' => $th->getMessage()
        ], 500);
      }
      
      // Decode JSON menjadi array PHP
      $productlistArr = json_decode($ProductList, true);
      $productimagesArr = json_decode($ProductImages, true);

      // Merging products dengan images
      foreach ($productlistArr as &$product) {
        // Filter gambar berdasarkan product_id
        $product['images'] = array_filter($productimagesArr, function($image) use ($product) {
            return $image['product_id'] == $product['id'];
        });

        // Hapus kolom product_id dalam setiap gambar
        foreach ($product['images'] as &$image) {
            unset($image['product_id']);
        }

        // Reindex array 'images' untuk mendapatkan index yang berurutan
        $product['images'] = array_values($product['images']);
      }

      // // Output hasil merge
      // // echo json_encode($productlistArr, JSON_PRETTY_PRINT);

      return response()->json([
        'success' => true,
        'message' => 'Data semua produk berhasil didapatkan',
        // 'data_produk' => $ProductList,
        // 'data_gambar' => $ProductImages
        // 'data' => json_encode($productlistArr, JSON_PRETTY_PRINT)
        'data' => $productlistArr
        // 'data' => $ProductList
      ], 200);
      // return $ProductImages;
    }
  }
?>