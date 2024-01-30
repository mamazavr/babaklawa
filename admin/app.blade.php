<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Підключення CSS файлів -->
    <link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
</head>
<body>
<div id="app">
    <nav>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        </ul>
    </nav>
    <div id="content">
        @yield('content')
    </div>
</div>
<!-- Підключення JS файлів -->
<script src="{{ asset('admin/js/app.js') }}"></script>
</body>
</html>
