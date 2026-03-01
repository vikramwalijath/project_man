<div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-receipt text-primary"></i> Invoice & Payments</h3>
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <label>Month</label>
            <select name="month" class="form-select">
                <option value="">All</option>
                <?php for($m=1;$m<=12;$m++): ?>
                <option value="<?= $m ?>" <?= ($this->input->get('month')==$m)?'selected':'' ?>>
                    <?= date('F', mktime(0,0,0,$m,1)) ?>
                </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label>Year</label>
            <select name="year" class="form-select">
                <option value="">All</option>
                <?php for($y=date('Y');$y>=2020;$y--): ?>
                <option value="<?= $y ?>" <?= ($this->input->get('year')==$y)?'selected':'' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Financial Year</label>
            <select name="fy" class="form-select">
                <option value="">All</option>
                <?php for($y=date('Y');$y>=2020;$y--): ?>
                <option value="<?= $y.'-'.($y+1) ?>" <?= ($this->input->get('fy')==$y.'-'.($y+1))?'selected':'' ?>>
                    <?= $y.'-'.($y+1) ?>
                </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Vendor</label>
            <select name="vendor_id" class="form-select">
                <option value="">All Vendors</option>
                <?php foreach($vendors as $v): ?>
                <option value="<?= $v->id ?>" <?= ($this->input->get('vendor_id')==$v->id)?'selected':'' ?>>
                    <?= $v->vendor_name ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <table id="invoiceTable" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Invoice No</th>
                        <th>Project</th>
                        <th>Customer</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoices as $i): ?>
                    <tr>
                        <td><?= date('d M, Y', strtotime($i->invoice_date)) ?></td>
                        <td><?= $i->invoice_no ?></td>
                        <td><?= $i->project_name ?></td>
                        <td><?= $i->customer_name ?></td>
                        <td><?= $i->vendor_name ?: '---' ?></td>
                        <td class="fw-bold text-success">₹<?= number_format($i->amount,2) ?></td>
                        <td>
                            <span class="badge <?= $i->is_received ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= $i->is_received ? 'Received' : 'Pending' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm">
                                <!-- Download Invoice -->
                                <a href="<?= base_url('invoices/download/'.$i->id); ?>"
                                    class="btn btn-sm btn-white border" title="Download Invoice">
                                    <i class="bi bi-download text-primary"></i>
                                </a>

                                <!-- View/Open Invoice PDF -->
                                <a href="<?= base_url('projects/invoice/'.$i->project_id.'/'.$i->id) ?>" target="_blank"
                                    class="btn btn-sm btn-outline-primary" title="View PDF">
                                    <i class="bi bi-eye"></i>
                                </a>

                            </div>
                        </td>


                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>