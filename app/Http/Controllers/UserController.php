<?php

  namespace App\Http\Controllers;

  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Http\Request;
  use Hidehalo\Nanoid\Client;
  use Illuminate\Support\Facades\Mail;
  use Carbon\Carbon;

  class UserController extends Controller {
    private function generateOtp($charTypes = '1234567890', $charNum = 1) {
      $client = new Client();
      $otp = $client->formattedId($charTypes, $charNum);
  
      // Simpan OTP di database atau kirim ke client
      return $otp;
    }
    public function getUserByUsername($username)
    {
        // Retrieve user by username
        $user = DB::table('users')->where('username', $username)->first();

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function registrasi(Request $request) {
      $validated = $request->validate([
        'username' => 'required|string',
        'email' => 'required|string',
        'password' => 'required|string',
      ]);

      // Hash password untuk keamanan
      // $validated['password'] = Hash::make($validated['password']);
      $validated['password'] = bcrypt($validated['password']);

      // Simpan data ke database
      try {
        DB::table('users')->insert($validated);
      } catch (\Exception $e) {
          // Jika terjadi error saat insert
          return response()->json([
              'success' => false,
              'message' => 'Failed to save user data',
              'error' => $e->getMessage()
          ], 500);
      }

      // Kembalikan response sukses
      return response()->json([
          'success' => true,
          'message' => 'User registered successfully'
      ], 201);
    }
    public function login(Request $request) {
      // Validasi input
      $validated = $request->validate([
        'email' => 'required|string|max:50',
        'password' => 'required|string',
      ]);

      // Ambil user berdasarkan email
      $user = DB::table('users')->where('email', $validated['email'])->first();

      // Periksa apakah user ditemukan
      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'User not found'
        ], 404);
      }

      // Periksa kecocokan password
      if (!Hash::check($validated['password'], $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid credentials'
        ], 401);
      }

      // Login berhasil
      return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
        ]
      ], 200);
    }
    public function updateUser(Request $request) {
      $validated = $request->validate([
        'username' => 'required|string',
        'email' => 'required|string',
        'password' => 'required|string',
      ]);

      // Hash password untuk keamanan
      $validated['password'] = Hash::make($validated['password']);
      
      // Simpan data ke database
      try {
        DB::table('users')
          ->where('username', $validated['username'])
          ->update($validated);
      } catch (\Exception $e) {
        // Jika terjadi error saat insert
        return response()->json([
          'success' => false,
          'message' => 'Gagal memperbarui data pengguna',
          'error' => $e->getMessage()
        ], 500);
      }

      return response()->json([
        'success' => true,
        'message' => 'Data pengguna berhasil diperbarui',
      ], 200);
    }
    public function lupaPass(Request $request) {
      // Validasi input
      $validated = $request->validate([
        'username' => 'required|string',
      ]);
  
      // Cari user berdasarkan username
      $user = DB::table('users')
                ->where('username', $validated['username'])
                ->orWhere('email', $validated['username'])
                ->first();
  
      if (!$user) {
        return response()->json(['success' => false, 'message' => 'Username/email not found'], 404);
      }
  
      // Buat kode OTP
      $otp = $this->generateOtp('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4); // OTP 6 digit
  
      // Simpan OTP di database (gunakan model atau query builder)
      DB::table('users')->updateOrInsert(
          ['username' => $user->username],
          [
            'otp' => $otp,
            // 'created_at' => Carbon::now(),
            // 'expires_at' => Carbon::now()->addMinutes(5) // OTP berlaku 5 menit
          ]
      );
  
      // Kirim OTP (contoh: via email atau SMS)
      // Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
      Mail::raw("Halo {$user->username}.
      Your OTP code is: {$otp}
      Jika Anda tidak merasa mendaftar, maka Anda dapat mengabaikan surel ini.", 
      function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Kode OTP - Hairstyle recommendation app');
    });
      return response()->json(['success' => true, 'message' => 'OTP sent to registered email']);
    }
    public function verifyOtp(Request $request) {
      // Validasi input
      $validated = $request->validate([
        'username' => 'required|string',
        'otp' => 'required|integer',
      ]);
  
      // Cari user berdasarkan username
      $user = DB::table('users')
                ->where('username', $validated['username'])
                ->select('username', 'otp')
                ->first();
      
      // Check if user with the specified username exists
      if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
      }
  
      // Ambil OTP dari database
      // $otpRecord = DB::table('users')
      //                ->where('username', $user->username)->select('otp')->first();
      $otpRecord = $user->otp;

      // if (!$otpRecord) {
      //   return response()->json(['success' => false, 'message' => 'OTP not found'], 404);
      // }
  
      // Periksa validitas OTP
      if ($otpRecord != $validated['otp']) {
        return response()->json([
          'success' => false, 
          'message' => 'Invalid OTP'
        ], 
        401);
      }
  
      // if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
      //   return response()->json(['success' => false, 'message' => 'OTP expired'], 410);
      // }
  
      // Hapus OTP setelah verifikasi
      DB::table('users')->where('username', $user->username)->update(['otp' => '']);
  
      return response()->json([
        'success' => true, 
        'message' => 'OTP verified successfully'
      ],
      200);
    }  
    public function logout(Request $request) {
    }
  }
?>