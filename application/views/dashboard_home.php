<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5 fw-bold">Welcome, <?= $this->session->userdata('username'); ?>!</h1>
            <p class="text-muted">Here is the overview of your construction projects.</p>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-md-12">
            <div class="p-5 bg-white border rounded-3 shadow-sm">
                <div class="mb-4">
                    <i class="bi bi-graph-up-arrow text-primary" style="font-size: 4rem;"></i>
                </div>
                <h2 class="fw-bold text-dark">Dashboard Charts Coming Soon</h2>
                <p class="lead text-secondary">We are building a powerful analytics engine to track your project expenses, worker payments, and material costs in real-time.</p>
                <hr class="my-4" style="width: 20%; margin: auto;">
                <p>In the meantime, you can manage your workers using the <strong>Employee Management</strong> menu.</p>
                <a href="<?= base_url('employees'); ?>" class="btn btn-primary btn-lg px-4">Go to Employee Management</a>
            </div>
        </div>
    </div>

    <div class="row mt-5 opacity-50">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Projects</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">--</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Expenses</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₹ 0.00</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Tasks</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">--</div>
                </div>
            </div>
        </div>
    </div>
</div>