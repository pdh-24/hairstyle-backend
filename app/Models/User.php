<?php

  namespace App\Models;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;
  use Laravel\Sanctum\HasApiTokens;
  use Tymon\JWTAuth\Contracts\JWTSubject;

  class User extends Authenticatable implements JWTSubject
  {
      use HasFactory, Notifiable;

      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */
      protected $fillable = [
          'username',
          'email',
          'password',
      ];

      /**
       * The attributes that should be hidden for arrays.
       *
       * @var array
       */
      protected $hidden = [
          'password',
          // 'remember_token',
      ];

      /**
       * The attributes that should be cast to native types.
       *
       * @var array
       */
      // protected $casts = [
      //     'email_verified_at' => 'datetime',
      // ];

      /**
       * Get the identifier that will be stored in the JWT claim.
       *
       * @return mixed
       */
      public function getJWTIdentifier()
      {
          return $this->getKey();
      }

      /**
       * Return custom claims for the JWT token.
       *
       * @return array
       */
      // public function getJWTCustomClaims()
      // {
      //     return [
      //         'role' => $this->role, // Assuming you have a 'role' field in your database
      //     ];
      // }
      public function getJWTCustomClaims()
      {
          return [];
      }
  }
?>