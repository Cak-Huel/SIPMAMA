<!DOCTYPE html>
<html>
<head><title>Update Status</title></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2563EB;">Halo, {{ $data['nama'] }}</h2>
    
    <p>Ada pembaruan status pada pendaftaran magang Anda di BPS Kabupaten Lamongan.</p>
    
    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p>Status Terbaru: <strong style="text-transform: uppercase;">{{ str_replace('_', ' ', $data['status']) }}</strong></p>
        
        @if($data['catatan'])
            <p><strong>Catatan Admin:</strong><br>
            {{ $data['catatan'] }}</p>
        @endif
    </div>

    <p>Silakan login ke dashboard SIPMAMA untuk melihat detail atau melakukan aksi lebih lanjut.</p>
    
    <a href="{{ route('login') }}" style="background: #2563EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login Dashboard</a>
    
    <p style="margin-top: 30px; font-size: 12px; color: #777;">Email ini dikirim otomatis. Mohon tidak membalas email ini.</p>
</body>
</html>