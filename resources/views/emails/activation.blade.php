<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun LENTERA Kabupaten Bogor</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }
        .email-card {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #115cb9 0%, #1d4ed8 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .email-body {
            padding: 32px 28px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .btn-activate {
            display: inline-block;
            background: linear-gradient(135deg, #115cb9 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 15px;
            margin: 24px 0;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            text-align: center;
        }
        .url-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 12px;
            border-radius: 8px;
            word-break: break-all;
            font-size: 12px;
            color: #475569;
            margin-top: 16px;
        }
        .email-footer {
            background: #f8fafc;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="email-header">
            <h1>LENTERA Kab. Bogor</h1>
            <p>Layanan Integrasi Izin Riset dan Magang Kabupaten Bogor</p>
        </div>
        <div class="email-body">
            <div class="greeting">Halo, {{ $name }}! 👋</div>
            <p>Terima kasih telah mendaftar di sistem pelayanan <strong>LENTERA Kabupaten Bogor</strong>.</p>
            <p>Untuk memastikan keaslian email Anda dan mengaktifkan akun peserta magang/riset Anda, silakan klik tombol aktivasi di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ $activationUrl }}" class="btn-activate">Aktifkan Akun Saya</a>
            </div>

            <p style="font-size: 13px; color: #64748b;">Apabila tombol di atas tidak dapat diklik, salin dan tempelkan tautan berikut ke browser Anda:</p>
            <div class="url-box">{{ $activationUrl }}</div>
            
            <p style="margin-top: 24px; font-size: 13px; color: #64748b;">Abaikan pesan ini jika Anda merasa tidak pernah melakukan pendaftaran akun di LENTERA.</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Pemerintah Kabupaten Bogor. Seluruh hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
