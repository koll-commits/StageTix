<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageTix Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar {
            width: 240px;
            background-color: #2d326a;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding: 20px 0;
        }
        .sidebar a {
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 24px;
            display: block;
        }
        .sidebar a.active, .sidebar a:hover {
            color: #ffffff;
            font-weight: bold;
        }
        .content {
            margin-left: 240px;
            padding: 30px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Standar Sesuai Mockup -->
    <div class="sidebar">
        <h4 class="text-white px-4 mb-4 fw-bold">StageTix</h4>
        <a href="#" class="{{ request()->is('tenant/dashboard*') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('events.index') }}" class="{{ request()->is('tenant/events*') ? 'active' : '' }}">Event-Saya</a>
        <a href="#">Checkin</a>
        <a href="#">Laporan</a>
        <a href="#">Keuangan</a>
    </div>

    <!-- Area Konten Utama -->
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>