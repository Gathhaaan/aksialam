<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; }
        .header { background-color: #16a34a; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 20px; }
        .voucher-box { background-color: #f8fafc; border: 2px dashed #16a34a; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 2px; margin: 20px 0; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #16a34a; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">AksiAlam.</h1>
            <p style="margin:0;">Selamat! Reward Anda Berhasil Ditukar</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Terima kasih telah menukarkan <strong>{{ $reward->points_required }} XP</strong> Anda. Berikut adalah detail reward yang Anda klaim:</p>
            
            <h3 style="color:#16a34a; margin-bottom: 5px;">{{ $reward->name }}</h3>
            <p style="margin-top:0;">{{ $reward->description }}</p>

            <p>Kode Voucher Anda:</p>
            <div class="voucher-box">
                {{ strtoupper(Str::random(8)) }}
            </div>

            <p>Silakan tunjukkan kode voucher ini kepada mitra kami atau gunakan di website merchant yang bersangkutan.</p>
            
            <center>
                <a href="{{ route('landing') }}" class="btn">Kembali ke AksiAlam</a>
            </center>
        </div>
        <div class="footer">
            <p>Terima kasih atas kontribusi Anda dalam menjaga alam!</p>
            <p>&copy; {{ date('Y') }} AksiAlam. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
