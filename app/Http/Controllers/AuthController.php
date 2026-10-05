<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login terpusat dan aman:
     * - Hanya mencocokkan email atau username persis
     * - Hash::check wajib
     * - Tanpa backdoor password atau fallback alias
     * - Regenerasi sesi saat berhasil
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'nullable|string',
            'email' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim((string) ($request->input('login') ?? $request->input('email') ?? $request->input('username') ?? ''));

        if ($loginInput === '') {
            return back()
                ->withInput($request->only('login', 'email', 'username'))
                ->with('error', 'Username atau Email wajib diisi.');
        }

        $cleanLogin = strtolower($loginInput);

        // Hanya cari by email ATAU username persis
        $user = User::where('email', $cleanLogin)
            ->orWhere('username', $cleanLogin)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('login', 'email', 'username'))
                ->with('error', 'Username / Email atau password tidak valid.');
        }

        // Cek status aktivasi untuk peserta / user
        if ($user->isUser()) {
            if ($user->status_akun === 'inactive' || $user->status_akun === 'nonaktif' || is_null($user->email_verified_at)) {
                return back()
                    ->withInput($request->only('login', 'email', 'username'))
                    ->with('error', 'Akun Anda belum aktif. Silakan periksa inbox/spam email (' . $user->email . ') Anda untuk mengeklik tautan aktivasi akun.')
                    ->with('resend_email', $user->email);
            }

            if ($user->status_akun === 'diblokir') {
                return back()
                    ->withInput($request->only('login', 'email', 'username'))
                    ->with('error', 'Akun Anda telah dinonaktifkan / diblokir oleh administrator.');
            }
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->isKesbangpol() || $user->isAdmin()) {
            return redirect()->intended('/kesbangpol/dashboard');
        }

        if ($user->isDinas()) {
            return redirect()->intended('/dinas/dashboard');
        }

        if ($user->isBidang()) {
            return redirect()->intended('/bidang/dashboard');
        }

        return redirect()->intended('/');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    $tglLahir = $request->input('tanggal_lahir');
                    if (!$tglLahir) return;
                    try {
                        $age = Carbon::parse($tglLahir)->age;
                        if ($age >= 17 && empty($value)) {
                            $fail('NIK wajib diisi untuk pemohon berusia 17 tahun atau lebih.');
                        }
                    } catch (\Exception $e) {}
                }
            ],
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
            ->with('success', 'Registrasi akun berhasil! Email aktivasi telah dikirim ke ' . $user->email . '. Silakan periksa inbox/spam email Anda untuk melakukan aktivasi akun.');
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
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cari user HANYA dari email (bukan dari user_id)
        $user = User::where('email', $request->email)->first();

        if ($user && is_null($user->email_verified_at)) {
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
        }

        // Balasan generik agar tidak membocorkan keberadaan email
        return redirect()->route('login.form', ['mode' => 'login'])
            ->with('success', 'Jika email Anda terdaftar dan belum aktif, tautan aktivasi baru telah dikirimkan ke kotak masuk email Anda.');
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

        if ($user) {
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
        }

        // Respon generik agar tidak membocorkan keberadaan akun
        return redirect()->route('login.form', ['mode' => 'forgot'])
            ->with('success', 'Jika data NIK dan email cocok serta terdaftar dalam sistem, tautan reset password telah dikirim ke email Anda.');
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

        // Cek TTL token (default 60 menit)
        $ttlMinutes = config('lentera.reset_token_ttl_minutes', 60);
        if ($record->created_at && Carbon::parse($record->created_at)->addMinutes($ttlMinutes)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Tautan reset password telah kadaluarsa (lebih dari ' . $ttlMinutes . ' menit). Silakan ajukan ulang.');
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
