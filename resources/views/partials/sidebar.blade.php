<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <style>
        /* Ensure the body and html take full height */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
        }

        /* Sidebar styles */
        .main-sidebar {
            width: 250px; /* Fixed width */
            height: 100vh; /* Full height */
            position: fixed; /* Fixed position */
            overflow-y: auto; /* Allow scrolling */
            z-index: 1000; /* Ensure it stays on top */
        }

        /* Main content styles */
        .content-wrapper {
            margin-left: 250px; /* Match the sidebar width */
            padding: 20px; /* Add padding */
            height: 100vh; /* Full height */
            overflow-y: auto; /* Allow scrolling */
            transition: margin-left 0.3s; /* Smooth transition */
        }

        /* Media query for responsiveness */
        @media (max-width: 768px) {
            .main-sidebar {
                width: 200px; /* Adjust width for smaller screens */
            }
            .content-wrapper {
                margin-left: 200px; /* Adjust margin accordingly */
            }
        }
    </style>
</head>
<body>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Profile Section -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://via.placeholder.com/150" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">TMS</a> <!-- Dummy user name -->
            </div>
        </div>

        <!-- Search Form -->
        <form class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('data-barang') }}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Data Barang</p>
                    </a>
                </li>
                <!-- Add more menu items here -->
            </ul>
        </nav>
    </div>
</aside>

<div class="content-wrapper">
    <!-- Main content goes here -->
</div>
</body>
</html>
