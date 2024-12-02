<!DOCTYPE html>
<html>
<head>
    <title>Stock Confirmation</title>
</head>
<body>
    <h1>Stock Confirmation</h1>
    <p>Date: {{ date('Y-m-d') }}</p>

    @if (!empty($checkedAssets) && is_array($checkedAssets))
        <ul>
            @foreach ($checkedAssets as $assetId)
                <li>Asset ID: {{ $assetId }}</li> <!-- You can fetch the asset name based on ID if needed -->
            @endforeach
        </ul>
    @else
        <p>No assets were selected for confirmation.</p>
    @endif
</body>
</html>
