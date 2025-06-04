<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Generate token for verification at register and forgot password
     *
     * @param boolean $status
     * @return void
     */
    public function generateToken($status = true)
    {
        $code = Str::random(40);
        if ($status == true) {
            $verificationCode = DB::table('users')->where('verification_code', '=', $code)->first();
        } else {
            $verificationCode = DB::table('password_reset_tokens')->where('verification_code', '=', $code)->first();
        }

        if (!$verificationCode) {
            return $code;
        } else {
            return $this->generateToken($status);
        }
    }
}
