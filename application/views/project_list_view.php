<div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-stack text-primary"></i> Project Portfolio</h3>
        <a href="<?= base_url('projects/form'); ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Create New Project
        </a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <table id="projectTable" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Project & Customer</th>
                        <th>Vendor</th>
                        <th>Worker Team</th>
                        <th>Total Value</th>
                        <th>Map</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($projects as $p): ?>
                    <tr>
                        <td data-sort="<?= $p->assigned_date ?>">
                            <span class="text-muted small"><?= date('d M, Y', strtotime($p->assigned_date)); ?></span>
                        </td>
                        <td>
                            <a href="<?= base_url('projects/view/'.$p->id); ?>"
                                class="fw-bold text-decoration-none text-primary">
                                <i class="bi bi-eye-fill me-1"></i> <?= $p->project_name ?>
                            </a>
                            <br>
                            <small class="text-muted">Client: <?= $p->customer_name ?></small>
                        </td>
                        <td>
                            <small class="fw-bold text-info"><?= $p->vendor_name ?: '---' ?></small>
                        </td>
                        <td>
                            <?php if(!empty($p->team)): ?>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach(explode(',', $p->team) as $member): ?>
                                <span class="badge bg-soft-warning text-warning border border-warning-subtle">
                                    <?= trim($member) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <span class="text-muted small">No team assigned</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-success">
                            ₹<?= number_format($p->total_amount, 2) ?>
                        </td>
                        <td>
                            <?php if($p->map_location): ?>
                            <a href="<?= $p->map_location ?>" target="_blank"
                                class="btn btn-link btn-sm p-0 text-decoration-none">
                                <i class="bi bi-geo-alt-fill"></i> Location
                            </a>
                            <?php else: ?>
                            <span class="text-muted small">---</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm">
                                <a href="<?= base_url('projects/form/'.$p->id); ?>" class="btn btn-sm btn-white border">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </a>
                                <a href="<?= base_url('projects/delete/'.$p->id); ?>"
                                    class="btn btn-sm btn-white border"
                                    onclick="return confirm('Archive this project?')">
                                    <i class="bi bi-trash3 text-danger"></i>
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

<style>
.bg-soft-primary {
    background-color: #e7f1ff;
}

.bg-soft-info {
    background-color: #e0f7fa;
}

.bg-soft-warning {
    background-color: #fff9db;
}

.x-small {
    font-size: 0.75rem;
}
</style>