<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | SIMPANG-BPS</title>
    @vite(['resources/css/app.css'])
</head>
<body style="margin:0;background:#f2f4f7;display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="text-align:center;max-width:480px;padding:32px;">
        <div style="font-size:80px;font-weight:700;color:#4680ff;line-height:1;">403</div>
        <h2 style="font-size:24px;font-weight:600;color:#1d2630;margin:12px 0 8px;">Akses Ditolak</h2>
        <p style="color:#5b6b79;font-size:15px;line-height:1.6;margin:0 0 28px;">
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>
        <a href="{{ url()->previous() }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#4680ff;color:#fff;border-radius:8px;text-decoration:none;font-size:14px;font-weight:500;margin-right:8px;">
            ← Kembali
        </a>
        <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#fff;color:#5b6b79;border:1px solid #e7eaee;border-radius:8px;text-decoration:none;font-size:14px;font-weight:500;">
            Dashboard
        </a>
        <p style="color:#adb5bd;font-size:12px;margin-top:32px;">SIMPANG-BPS — BPS Kabupaten Jepara</p>
    </div>
</body>
</html>
