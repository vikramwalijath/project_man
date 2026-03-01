<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Employee Masters</h2>
        <a href="<?= base_url('employees/form'); ?>" class="btn btn-primary">+ Add New</a>
    </div>

    <table id="employeeTable" class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($employees as $emp): ?>
            <tr>
                <td><?= $emp->name ?></td>
                <td><span class="badge bg-secondary"><?= ucfirst($emp->type_name) ?></span></td>
                <td><?= $emp->phone ?></td>
                <td>
                    <a href="<?= base_url('employees/payments/'.$emp->id); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-cash-stack"></i> Payments
                    </a>
                    <a href="<?= base_url('employees/form/'.$emp->id); ?>" class="btn btn-sm btn-warning">
                        Edit
                    </a>
                    <a href="<?= base_url('employees/delete/'.$emp->id); ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this employee?');">
                        Delete
                    </a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>