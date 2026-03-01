<div class="container-fluid mt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-0">Welcome, <?= $user_full_name ?></h3>
            <p class="text-muted">Business Performance Summary</p>
        </div>
        <div class="text-end">
            <button class="btn btn-outline-primary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print
                Report</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body">
                    <small class="opacity-75">Total Project Value</small>
                    <h3 class="fw-bold">₹<?= number_format($total_revenue) ?></h3>
                    <div class="small"><i class="bi bi-briefcase"></i> <?= $total_projects ?> Active Projects</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white border-0 shadow-sm">
                <div class="card-body">
                    <small class="opacity-75">Total Invoiced (Revenue)</small>
                    <h3 class="fw-bold">₹<?= number_format($total_invoiced) ?></h3>
                    <div class="small"><i class="bi bi-check-circle"></i> Billed to Clients</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark border-0 shadow-sm">
                <div class="card-body">
                    <small class="opacity-75">Pending to Invoice</small>
                    <h3 class="fw-bold">₹<?= number_format($pending_bill) ?></h3>
                    <div class="small"><i class="bi bi-clock"></i> Unbilled Work</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card <?= ($net_profit >= 0) ? 'bg-info' : 'bg-danger' ?> text-white border-0 shadow-sm">
                <div class="card-body">
                    <small class="opacity-75">Current Net Profit</small>
                    <h3 class="fw-bold">₹<?= number_format($net_profit) ?></h3>
                    <div class="small"><i class="bi bi-graph-up"></i> Collected - Paid</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Team Resources</div>
                <div class="card-body d-flex align-items-center justify-content-around text-center">
                    <div>
                        <h4 class="mb-0 text-primary fw-bold"><?= $total_staff ?></h4>
                        <small class="text-muted">Total Labor</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <h4 class="mb-0 text-success fw-bold"><?= $total_suppliers ?></h4>
                        <small class="text-muted">Suppliers</small>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="<?= base_url('projects') ?>" class="btn btn-sm btn-light w-100 border text-primary">Assign
                        Team to Projects</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between">
                    <span>Expense Breakdown (Actual Payouts)</span>
                    <span class="text-danger small">Total: ₹<?= number_format($total_expenses) ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Suppliers & Materials</label>
                            <div class="d-flex align-items-center mb-3">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-info"
                                        style="width: <?= ($total_expenses > 0) ? ($total_sup_paid/$total_expenses)*100 : 0 ?>%">
                                    </div>
                                </div>
                                <span class="ms-3 small fw-bold">₹<?= number_format($total_sup_paid) ?></span>
                            </div>

                            <label class="small text-muted mb-1">Carpentry Team</label>
                            <div class="d-flex align-items-center mb-3">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-primary"
                                        style="width: <?= ($total_expenses > 0) ? ($total_carp_paid/$total_expenses)*100 : 0 ?>%">
                                    </div>
                                </div>
                                <span class="ms-3 small fw-bold">₹<?= number_format($total_carp_paid) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Painting Team</label>
                            <div class="d-flex align-items-center mb-3">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-success"
                                        style="width: <?= ($total_expenses > 0) ? ($total_paint_paid/$total_expenses)*100 : 0 ?>%">
                                    </div>
                                </div>
                                <span class="ms-3 small fw-bold">₹<?= number_format($total_paint_paid) ?></span>
                            </div>

                            <label class="small text-muted mb-1">Electrical Team</label>
                            <div class="d-flex align-items-center mb-3">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-warning"
                                        style="width: <?= ($total_expenses > 0) ? ($total_elec_paid/$total_expenses)*100 : 0 ?>%">
                                    </div>
                                </div>
                                <span class="ms-3 small fw-bold">₹<?= number_format($total_elec_paid) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>