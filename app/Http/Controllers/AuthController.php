<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'nim' => 'nullable|string|max:50',
            'alamat' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $activationToken = Str::random(60);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peserta',
            'status_akun' => 'inactive',
            'email_verified_at' => null,
            'activation_token' => $activationToken,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'asal_instansi' => $request->asal_instansi,
            'program_studi' => $request->program_studi,
            'nim' => $request->nim,
            'alamat' => $request->alamat,
        ]);

        $activationUrl = route('account.activate', ['token' => $activationToken]);

        // Send activation email
        try {
            Mail::send('emails.activation', [
                'name' => $user->name,
                'activationUrl' => $activationUrl,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Aktivasi Akun LENTERA Kabupaten Bogor');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send activation email: ' . $e->getMessage());
        }

        return redirect()->route('login.form', ['mode' => 'login'])
            ->with('success', 'Registrasi akun berhasil! Email aktivasi telah dikirim ke ' . $user->email . '. Silakan periksa inbox/spam email Anda untuk melakukan aktivasi akun.')
            ->with('activation_url', $activationUrl);
    }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (! $user) {
            return redirect()->route('login.form', ['mode' => 'login'])
                ->with('error', 'Tautan aktivasi tidak valid atau akun sudah diaktifkan sebelumnya.');
        }

        $user->update([
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
            'activation_token' => null,
        ]);

        return view('pelayanan.auth.activated', compact('user'));
    }

    public function resendActivation(Request $request)
    {
        $user = User::find($request->user_id) ?? User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        if ($user->email_verified_at && $user->status_akun === 'aktif') {
            return redirect()->route('login.form')->with('success', 'Akun Anda sudah aktif. Silakan masuk.');
        }

        $activationToken = Str::random(60);
        $user->update(['activation_token' => $activationToken]);

        $activationUrl = route('account.activate', ['token' => $activationToken]);

        try {
            Mail::send('emails.activation', [
                'name' => $user->name,
                'activationUrl' => $activationUrl,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Aktivasi Akun LENTERA Kabupaten Bogor');
            });
        } catch (\Exception $e) {
            Log::error('Failed to resend activation email: ' . $e->getMessage());
        }

        return redirect()->route('login.form', ['mode' => 'login'])
            ->with('success', 'Email aktivasi baru telah dikirimkan ke ' . $user->email . '.')
            ->with('activation_url', $activationUrl);
    }

    public function forgotPasswordVerify(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('nik', $request->nik)
            ->where('email', $request->email)
            ->first();

        if (! $user) {
            return back()->withInput()->with('error', 'Data NIK dan Email tidak cocok atau tidak terdaftar dalam sistem.');
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $user->email]);

        try {
            Mail::send('emails.reset_password', [
                'name' => $user->name,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Permintaan Reset Password Akun LENTERA');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send reset password email: ' . $e->getMessage());
        }

        return redirect()->route('login.form', ['mode' => 'forgot'])
            ->with('success', 'Tautan reset password telah dikirim ke email ' . $user->email . '. Silakan periksa inbox/spam email/Gmail Anda.')
            ->with('reset_url', $resetUrl);
    }

    public function showResetPasswordForm($token, Request $request)
    {
        return view('pelayanan.auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->with('error', 'Tautan reset password tidak valid atau telah kadaluarsa.');
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login.form', ['mode' => 'login'])
            ->with('success', 'Password akun Anda berhasil diperbarui! Silakan login dengan password baru.');
    }
}
