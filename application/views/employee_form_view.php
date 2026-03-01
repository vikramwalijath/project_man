<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= isset($employee) ? 'Edit Employee' : 'Add Employee' ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('employees/save'); ?>" method="POST">
                        <input type="hidden" name="id" value="<?= isset($employee) ? $employee->id : '' ?>">

                        <!-- Employee Type -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="type" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                <?php foreach($employee_types as $et): ?>
                                <option value="<?= $et->type_name ?>"
                                    <?= (isset($employee) && $employee->type == $et->type_name) ? 'selected' : '' ?>>
                                    <?= ucfirst($et->type_name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Employee Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= isset($employee) ? $employee->name : '' ?>" required>
                        </div>

                        <!-- Employee Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= isset($employee) ? $employee->phone : '' ?>">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('employees'); ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <?= isset($employee) ? 'Update Employee' : 'Save Employee' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>