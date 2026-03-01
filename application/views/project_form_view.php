<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between mb-3">
        <h4><?= isset($project) ? 'Edit' : 'Add New' ?> Project</h4>
        <a href="<?= base_url('projects'); ?>" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card shadow border-0">
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
        <div class="card-body">
            <form action="<?= base_url('projects/save'); ?>" method="POST">
                <input type="hidden" name="id" value="<?= isset($project) ? $project->id : '' ?>">

                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Client Details</h6>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label>Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">Select Vendor</option>
                            <?php foreach($vendors as $v): ?>
                            <option value="<?= $v->id ?>"
                                <?= (isset($project) && $project->vendor_id == $v->id) ? 'selected' : '' ?>>
                                <?= $v->vendor_name ?> (<?= $v->shop_address ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Project Name</label>
                        <input type="text" name="project_name" class="form-control"
                            value="<?= isset($project) ? $project->project_name : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Customer Name</label>
                        <input type="text" name="customer_name" class="form-control"
                            value="<?= isset($project) ? $project->customer_name : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" name="assigned_date" class="form-control"
                            value="<?= isset($project) ? $project->assigned_date : date('Y-m-d') ?>">
                    </div>
                </div>


                <h6 class="text-success fw-bold mb-3 border-bottom pb-2">Order Reference (for Invoicing)</h6>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label>HSN/SAC Code</label>
                        <input type="text" name="hsn_code" class="form-control"
                            value="<?= isset($project) ? $project->hsn_code : '' ?>" placeholder="e.g. 9954">
                    </div>
                    <div class="col-md-3">
                        <label>Buyer's Order No. (PO)</label>
                        <input type="text" name="buyers_order_no" class="form-control"
                            value="<?= isset($project) ? $project->buyers_order_no : 'PO-LIV-11269-2025-2026/96897' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>PO Date</label>
                        <input type="date" name="po_date" class="form-control"
                            value="<?= isset($project) ? $project->po_date : date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Ext. Project Id</label>
                        <input type="text" name="project_external_id" class="form-control"
                            value="<?= isset($project) ? $project->project_external_id : '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Order Id</label>
                        <input type="text" name="order_id" class="form-control"
                            value="<?= isset($project) ? $project->order_id : '' ?>">
                    </div>
                </div>

                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Team Assignment</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label>Carpenter</label>
                        <select name="carpenter_id" class="form-select">
                            <option value="">Select Carpenter</option>
                            <?php foreach($carpenters as $c): ?><option value="<?= $c->id ?>"
                                <?= (isset($project) && $project->carpenter_id == $c->id) ? 'selected' : '' ?>>
                                <?= $c->name ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Painter</label>
                        <select name="painter_id" class="form-select">
                            <option value="">Select Painter</option>
                            <?php foreach($painters as $p): ?><option value="<?= $p->id ?>"
                                <?= (isset($project) && $project->painter_id == $p->id) ? 'selected' : '' ?>>
                                <?= $p->name ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Electrician</label>
                        <select name="electrician_id" class="form-select">
                            <option value="">Select Electrician</option>
                            <?php foreach($electricians as $e): ?><option value="<?= $e->id ?>"
                                <?= (isset($project) && $project->electrician_id == $e->id) ? 'selected' : '' ?>>
                                <?= $e->name ?></option><?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Financials & Location</h6>
                <div class="row mb-4">
                    <div class="col-md-2">
                        <label>Amount</label>
                        <input type="number" id="amt" name="amount" class="form-control"
                            value="<?= isset($project) ? $project->amount : '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Tax %</label>
                        <input type="number" id="tax" name="tax_percent" class="form-control"
                            value="<?= isset($project) ? $project->tax_percent : '0' ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Total Value</label>
                        <input type="number" id="total" name="total_amount" class="form-control bg-light"
                            value="<?= isset($project) ? $project->total_amount : '' ?>" readonly>
                    </div>
                    <div class="col-md-5">
                        <label>Map Link</label>
                        <input type="text" name="map_location" class="form-control"
                            value="<?= isset($project) ? $project->map_location : '' ?>" placeholder="Google Maps URL">
                    </div>
                </div>

                <div class="mb-4">
                    <label>Full Site Address</label>
                    <textarea name="address" class="form-control"
                        rows="3"><?= isset($project) ? $project->address : '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary px-5">Save Project</button>
            </form>
        </div>
    </div>
</div>

<script>
const a = document.getElementById('amt'),
    t = document.getElementById('tax'),
    r = document.getElementById('total');

function c() {
    r.value = ((parseFloat(a.value) || 0) * (1 + (parseFloat(t.value) || 0) / 100)).toFixed(2);
}
a.oninput = t.oninput = c;
</script>