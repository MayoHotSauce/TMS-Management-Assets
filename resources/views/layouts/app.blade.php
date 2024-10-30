<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS Dashboard</title>
    <!-- Include CSS files here -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jqvmap/dist/jqvmap.min.css">
</head>
<body>
    @include('partials.sidebar') <!-- Include the sidebar partial -->
    
    <div class="content">
        @yield('content') <!-- This is where the content from other views will be injected -->
    </div>

    <!-- Include JS files here -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jqvmap/dist/maps/jquery.vmap.usa.js"></script>
</body>
</html>
