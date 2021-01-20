<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OtpCode;
use App\User;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otp_code = OtpCode::where('otp', $request->otp)->first();

        if (!$otp_code){
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Otp tidak ditemukan',
            ], 200);
        }

        $now = Carbon::now();

        if ($now > $otp_code->valid_until) {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Kode Otp sudah tidak berlaku, silahkan generate ulang',
            ], 200);
        }

        //Update User
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = Carbon::now();
        $user->save();

        //Delete Otp
        $otp_code->delete();

        $data['user'] = $user;

        return response()->json([
            'response_code' => '00',
            'response_message' => 'User berhasil diverifikasi',
            'data' => $data
        ], 200);
    }
}
