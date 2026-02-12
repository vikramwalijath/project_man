<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Employee Masters</h2>
        <a href="<?= base_url('employees/form'); ?>" class="btn btn-primary">+ Add New</a>
    </div>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $types = ['carpenter' => $carpenters, 'painter' => $painters, 'electrician' => $electricians];
            foreach($types as $label => $list): 
                foreach($list as $row): ?>
                <tr>
                    <td><?= $row->name ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($label) ?></span></td>
                    <td><?= $row->phone ?></td>
                    <td>
                        <a href="<?= base_url("employees/form/$label/$row->id"); ?>" class="btn btn-sm btn-warning">
                             Edit
                        </a>
                        <a href="<?= base_url("employees/delete/$label/$row->id"); ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Are you sure?')">
                             Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; endforeach; ?>
        </tbody>
    </table>
</div>