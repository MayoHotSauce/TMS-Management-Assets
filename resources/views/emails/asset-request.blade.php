<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }
        .approve { background-color: #4CAF50; }
        .decline { background-color: #f44336; }
        .details { background-color: #2196F3; }
    </style>
</head>
<body>
    <h2>Asset Request Needs Your Approval</h2>
    
    <p>A new asset request requires your review:</p>
    
    <table style="border-collapse: collapse; width: 100%; margin: 20px 0;">
        <tr>
            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Asset Name</th>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $assetRequest->name }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Category</th>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $assetRequest->category }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Price</th>
            <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($assetRequest->price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Description</th>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $assetRequest->description ?? 'No description provided' }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 8px; border: 1px solid #ddd;">Requested By</th>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $assetRequest->requester_email }}</td>
        </tr>
    </table>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('pengajuan.approve', ['id' => $assetRequest->id, 'token' => $assetRequest->approval_token]) }}" 
           class="button approve" style="background-color: #4CAF50;">
            Approve Request
        </a>
        
        <a href="{{ route('pengajuan.decline', ['id' => $assetRequest->id, 'token' => $assetRequest->approval_token]) }}" 
           class="button decline" style="background-color: #f44336;">
            Decline Request
        </a>
    </div>

    <p style="text-align: center;">
        <a href="{{ route('pengajuan.show', $assetRequest->id) }}" 
           class="button details" style="background-color: #2196F3;">
            View Complete Details
        </a>
    </p>

    <p style="color: #666; font-size: 12px; margin-top: 30px;">
        This is an automated message from the TMS Asset Management System. Please do not reply to this email.
    </p>
</body>
</html> 