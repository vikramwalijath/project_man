<div class="container-fluid mt-4 mb-5">
    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?= $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><?= $project->project_name ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-primary">Client: <?= $project->customer_name ?></li>
                    <li class="breadcrumb-item active">Project ID:
                        PRJ-<?= str_pad($project->id, 3, '0', STR_PAD_LEFT) ?></li>
                </ol>
            </nav>
        </div>
        <a href="<?= base_url('projects'); ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
    </div>

    <div class="row g-3 mb-4 text-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white py-3">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Contract Value</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($project->total_amount, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white py-3">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Received (Invoices)</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($received, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white py-3">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Expenses</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($sup_exp + $emp_exp, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?php $balance = $received - ($sup_exp + $emp_exp); ?>
            <div
                class="card border-0 shadow-sm <?= ($balance >= 0) ? 'bg-dark' : 'bg-warning text-dark' ?> text-white py-3">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Current Profit/Loss</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($balance, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-receipt text-success me-2"></i>Generate Invoice</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('finance/add_invoice') ?>" method="POST">
                        <input type="hidden" name="project_id" value="<?= $project->id ?>">
                        <div class="mb-3">
                            <label class="small fw-bold">Amount to Bill (₹)</label>
                            <input type="number" name="amount" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Invoice Date</label>
                            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Record Income</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-truck text-danger me-2"></i>Pay Supplier</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('finance/add_supplier_payment') ?>" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="project_id" value="<?= $project->id ?>">

                        <div class="mb-2">
                            <label class="small fw-bold">Select Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Choose --</option>
                                <?php foreach($suppliers as $s): ?>
                                <option value="<?= $s->id ?>"><?= $s->supplier_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Bill Reference</label>
                            <input type="text" name="invoice_name" class="form-control" placeholder="e.g. Bill #405"
                                required>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Amount Paid</label>
                            <input type="number" name="amount_sent" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Paid By</label>
                            <select name="supplier_paid_by" class="form-select" required>
                                <option value="">-- Select Who Paid --</option>
                                <?php foreach($all_users as $user): ?>
                                <option value="<?= $user->id ?>"
                                    <?= ($this->session->userdata('user_id') == $user->id) ? 'selected' : '' ?>>
                                    <?= $user->username ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Date</label>
                            <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-primary"><i class="bi bi-paperclip"></i> Attach Supplier
                                Bill</label>
                            <input type="file" name="attachment" class="form-control form-control-sm">
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Record Material Expense</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge text-warning me-2"></i>Pay Employee</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('finance/add_employee_payment') ?>" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="project_id" value="<?= $project->id ?>">

                        <div class="mb-2">
                            <label class="small fw-bold">Select Worker</label>
                            <select name="worker_info" class="form-select" required>
                                <option value="">-- Choose Worker --</option>
                                <?php if($project->carpenter_id): ?>
                                <option value="carpenter|<?= $project->carpenter_id ?>">Carpenter
                                    (<?= $project->carpenter_name ?>)</option>
                                <?php endif; ?>
                                <?php if($project->painter_id): ?>
                                <option value="painter|<?= $project->painter_id ?>">Painter
                                    (<?= $project->painter_name ?>)</option>
                                <?php endif; ?>
                                <?php if($project->electrician_id): ?>
                                <option value="electrician|<?= $project->electrician_id ?>">Electrician
                                    (<?= $project->electrician_name ?>)</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Paid By</label>
                            <select name="paid_by" class="form-select" required>
                                <option value="">-- Select Who Paid --</option>
                                <?php foreach($all_users as $user): ?>
                                <option value="<?= $user->id ?>"
                                    <?= ($this->session->userdata('user_id') == $user->id) ? 'selected' : '' ?>>
                                    <?= $user->username ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Amount Paid</label>
                            <input type="number" name="amount_paid" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="small fw-bold">Remarks / Date</label>
                            <div class="input-group mb-2">
                                <input type="text" name="remarks" class="form-control" placeholder="Advance? Full?">
                                <input type="date" name="payment_date" class="form-control"
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-primary"><i class="bi bi-paperclip"></i> Attach
                                Proof</label>
                            <input type="file" name="attachment" class="form-control form-control-sm">
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold">Record Labor Expense</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold border-0 pt-3">Supplier Ledger</div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Supplier / Payer</th>
                                <th>Bill Name</th>
                                <th class="text-end pe-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sup_payments as $sp): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="small fw-bold"><?= date('d-M-y', strtotime($sp->payment_date)) ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold small"><?= $sp->supplier_name ?></div>
                                    <small class="text-muted badge bg-light text-dark border font-monospace">Paid By:
                                        <?= $sp->payer_name ?: 'System' ?></small>
                                </td>
                                <td class="text-muted small">
                                    <?= $sp->invoice_name ?>
                                    <?php if($sp->file_attachment): ?>
                                    <br><a href="<?= base_url('uploads/payments/'.$sp->file_attachment) ?>"
                                        target="_blank" class="text-info x-small text-decoration-none">
                                        <i class="bi bi-file-earmark-text"></i> View Bill
                                    </a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3 fw-bold text-danger">
                                    ₹<?= number_format($sp->amount_sent, 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold border-0 pt-3">Employee Ledger</div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Worker / Type</th>
                                <th>Paid By</th>
                                <th class="text-end pe-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($emp_payments as $ep): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="small fw-bold"><?= date('d-M-y', strtotime($ep->payment_date)) ?></div>
                                </td>
                                <td>
                                    <span class="fw-bold"><?= $ep->worker_name ?></span><br>
                                    <small class="text-muted"><?= ucfirst($ep->employee_type) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace"
                                        style="font-size: 0.75rem;">
                                        <?= $ep->payer_name ?: 'System' ?>
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="fw-bold">₹<?= number_format($ep->amount_paid, 2) ?></div>
                                    <?php if($ep->file_attachment): ?>
                                    <a href="<?= base_url('uploads/payments/'.$ep->file_attachment) ?>" target="_blank"
                                        class="text-info small text-decoration-none">
                                        <i class="bi bi-paperclip"></i> View Proof
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Invoices & Payment Tracking</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                $total_paid = 0;
                foreach($invoices as $inv): 
                    if($inv->is_received) $total_paid += $inv->amount;
                ?>
                            <tr>
                                <td><?= $inv->invoice_no ?></td>
                                <td><?= date('d-M-Y', strtotime($inv->invoice_date)) ?></td>
                                <td>₹ <?= number_format($inv->amount, 2) ?></td>
                                <td>
                                    <?php if($inv->is_received): ?>
                                    <span class="badge bg-success">Received</span>
                                    <?php else: ?>
                                    <a href="<?= base_url('projects/mark_as_paid/'.$inv->id.'/'.$project->id) ?>"
                                        class="badge bg-warning text-dark text-decoration-none">Pending (Click to
                                        Pay)</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('projects/invoice/'.$project->id.'/'.$inv->id) ?>"
                                            target="_blank" class="btn btn-sm btn-outline-primary" title="View PDF">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if($inv->is_received == 0): ?>
                                        <a href="<?= base_url('projects/delete_invoice/'.$inv->id.'/'.$project->id) ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')"
                                            title="Delete Invoice">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <small class="text-muted d-block">Total Project Value</small>
                                <h5 class="fw-bold">₹ <?= number_format($project->total_amount, 2) ?></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-success text-white">
                                <small class="d-block">Total Received</small>
                                <h5 class="fw-bold">₹ <?= number_format($total_paid, 2) ?></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-danger text-white">
                                <small class="d-block">Balance Pending</small>
                                <h5 class="fw-bold">₹ <?= number_format($project->total_amount - $total_paid, 2) ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>