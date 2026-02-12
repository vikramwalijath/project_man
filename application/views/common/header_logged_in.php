<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Optional: Makes the dark navbar pop a bit more */
        .navbar-dark .nav-link { color: rgba(255,255,255,0.8); }
        .navbar-dark .nav-link:hover { color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= base_url('dashboard'); ?>">
            <i class="bi bi-building"></i> ProjectManager
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('dashboard'); ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('projects'); ?>">
                        <i class="bi bi-journal-list"></i> My Projects
                    </a>
                </li>
               <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('employees'); ?>">
                        <i class="bi bi-people"></i> Employee Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('vendors'); ?>">
                        <i class="bi bi-shop"></i> Vendor Masters
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('payments'); ?>">
                        <i class="bi bi-currency-dollar"></i> Payments & Receipts
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    Welcome, <strong><?= $this->session->userdata('username'); ?></strong>
                </span>
                <a href="<?= base_url('auth/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>