<?php

namespace App\Support;

use App\Models\Dinas;
use Illuminate\Support\Facades\Auth;

class CurrentDinas
{
    /**
     * Dapatkan ID dinas aktif untuk pengguna yang login.
     * Hanya role admin/superadmin yang dapat memanfaatkan switch-instansi via session.
     */
    public static function id(): ?int
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        if ($user->isAdmin()) {
            if (session()->has('superadmin_instansi_id') && !empty(session('superadmin_instansi_id'))) {
                return (int) session('superadmin_instansi_id');
            }
            return $user->dinas_id ? (int) $user->dinas_id : null;
        }

        return $user->dinas_id ? (int) $user->dinas_id : null;
    }

    /**
     * Dapatkan model Dinas aktif berdasarkan id() di atas.
     */
    public static function model(): ?Dinas
    {
        $id = self::id();
        return $id ? Dinas::find($id) : null;
    }
}
