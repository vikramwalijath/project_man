<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5><?= isset($employee) ? 'Edit' : 'Add' ?> Employee</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('employees/save'); ?>" method="POST">
                        <input type="hidden" name="id" value="<?= isset($employee) ? $employee->id : '' ?>">

                        <div class="mb-3">
                            <label>Category</label>
                            <select name="emp_type" class="form-select" <?= isset($employee) ? 'disabled' : 'required' ?>>
                                <option value="carpenter" <?= (isset($type) && $type == 'carpenter') ? 'selected' : '' ?>>Carpenter</option>
                                <option value="painter" <?= (isset($type) && $type == 'painter') ? 'selected' : '' ?>>Painter</option>
                                <option value="electrician" <?= (isset($type) && $type == 'electrician') ? 'selected' : '' ?>>Electrician</option>
                            </select>
                            <?php if(isset($employee)): ?>
                                <input type="hidden" name="emp_type" value="<?= $type ?>">
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= isset($employee) ? $employee->name : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= isset($employee) ? $employee->phone : '' ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('employees'); ?>" class="btn btn-light">Back</a>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>