<div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-shop text-primary"></i> Vendor Portfolio</h3>
        <a href="<?= base_url('vendors/form'); ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Add New Vendor
        </a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <table id="vendorTable" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Vendor Name</th>
                        <th>Shop Address</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($vendors as $v): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= $v->vendor_name ?></td>
                        <td><small class="text-muted"><?= $v->shop_address ?></small></td>
                        <td>
                            <span class="badge <?= $v->status == 1 ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $v->status == 1 ? 'Active' : 'Archived' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm">
                                <a href="<?= base_url('vendors/form/'.$v->id); ?>" class="btn btn-sm btn-white border">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-white border" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?= $v->id ?>">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal<?= $v->id ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirm Archive</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Archive vendor <strong><?= $v->vendor_name ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <a href="<?= base_url('vendors/delete/'.$v->id); ?>"
                                                class="btn btn-danger">Yes, Archive</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>