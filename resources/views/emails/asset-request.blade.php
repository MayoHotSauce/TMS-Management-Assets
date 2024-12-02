<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .approve { background-color: #4CAF50; }
        .decline { background-color: #f44336; }
        .details { background-color: #2196F3; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background-color: #f8f9fa; text-align: left; }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #333; text-align: center;">Asset ini butuh persetujuan Anda!</h2>
        
        <table>
            <tr>
                <th>Nama Asset</th>
                <td>{{ $assetRequest->name }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $assetRequest->category }}</td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>Rp {{ number_format($assetRequest->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $assetRequest->description ?? 'No description provided' }}</td>
            </tr>
            <tr>
                <th>Diminta Oleh</th>
                <td>{{ $assetRequest->requester_email }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url("/approval/{$assetRequest->id}/approve/{$assetRequest->approval_token}") }}" 
               class="button approve">
                Setuju
            </a>
            
            <a href="{{ url("/approval/{$assetRequest->id}/decline/{$assetRequest->approval_token}") }}" 
               class="button decline">
                Tolak
            </a>
        </div>

        <p style="text-align: center; color: #666; font-size: 12px; margin-top: 30px;">
            Ini adalah pesan otomatis. Tolong jangan Reply ke email ini.<br>
            Link approval cuma bisa Sekali Pakai.
        </p>
    </div>
</body>
</html>