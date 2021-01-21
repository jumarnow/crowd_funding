<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendOtpCodeMail;
use Mail;

class SendEmailOtpCode implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->condition == 'register'){
            $pesan = 'Silahkan konfirmasi akun kamu, Kode Otp kamu : ';
        }elseif($event->condition == 'regenerate'){
            $pesan = 'Regenerate Otp berhasil, Kode Otp kamu : ';
        }

        Mail::to($event->user)->send(new SendOtpCodeMail($event->user, $pesan));
    }
}
