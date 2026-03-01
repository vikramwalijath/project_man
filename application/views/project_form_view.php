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
                <div class="mb-3" <?= !isset($project) ? 'style="display:none;"' : '' ?>>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#teamModal">
                        <i class="bi bi-plus-circle"></i> Assign Team
                    </button>
                </div>

                <?php if(!empty($assigned_team)): ?>
                <div class="table-responsive">
                    <div id="assignedTeam">
                        <?php if(!empty($assigned_team)): ?>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($assigned_team as $member): ?>
                                <tr>
                                    <td><?= $member->type ?></td>
                                    <td><?= $member->name ?></td>
                                    <td>
                                        <a href="<?= base_url('projects/remove_team_assignment/'.$member->id.'/'.$project->id) ?>"
                                            class="btn btn-sm btn-danger removeMember">
                                            <i class="bi bi-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-muted">No team members assigned yet.</p>
                        <?php endif; ?>
                    </div>

                </div>
                <?php else: ?>
                <p class="text-muted">No team members assigned yet.</p>
                <?php endif; ?>



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

<!-- Team Assignment Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="teamForm" action="<?= base_url('projects/save_team_assignment') ?>" method="post">
                <input type="hidden" name="project_id" value="<?= isset($project) ? $project->id : '' ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamModalLabel">Assign Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Type</label>
                        <select id="employeeType" class="form-select" required>
                            <option value="">-- Choose Type --</option>
                            <?php foreach($employee_types as $et): ?>
                            <option value="<?= $et->type_name ?>"><?= $et->type_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Employee</label>
                        <select id="employeeList" name="employee_id" class="form-select" required>
                            <option value="">-- Choose Employee --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var employees = <?= json_encode($employees) ?>;

    // Debug: check if employees data is loaded
    console.log(employees);

    $('#employeeType').on('change', function() {
        var type = $(this).val();
        var $employeeList = $('#employeeList');

        // Reset dropdown
        $employeeList.empty().append('<option value="">-- Choose Employee1 --</option>');

        // Filter employees by type
        $.each(employees, function(index, emp) {
            if (emp.type === type) {
                $employeeList.append('<option value="' + emp.id + '">' + emp.name +
                    '</option>');
            }
        });
    });
});
$(document).ready(function() {
    // Handle team assignment form submit
    $('#teamForm').on('submit', function(e) {
        e.preventDefault(); // stop normal form submit

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Close modal
                $('#teamModal').modal('hide');

                // Refresh team list
                $('#assignedTeam').load(location.href + " #assignedTeam>*", "");
            },
            error: function() {
                alert('Error saving team assignment');
            }
        });
    });

    // Handle remove member links via AJAX
    $(document).on('click', '.removeMember', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'GET',
            success: function() {
                $('#assignedTeam').load(location.href + " #assignedTeam>*", "");
            },
            error: function() {
                alert('Error removing member');
            }
        });
    });
});
</script>