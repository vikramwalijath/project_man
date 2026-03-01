<div class="container mt-4">
    <h4><?= isset($vendor) ? 'Edit Vendor' : 'Add New Vendor' ?></h4>
    <form action="<?= base_url('vendors/save'); ?>" method="POST">
        <input type="hidden" name="id" value="<?= isset($vendor) ? $vendor->id : '' ?>">

        <div class="mb-3">
            <label>Vendor Name</label>
            <input type="text" name="vendor_name" class="form-control"
                value="<?= isset($vendor) ? $vendor->vendor_name : '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Shop Address</label>
            <textarea name="shop_address" class="form-control"
                rows="3"><?= isset($vendor) ? $vendor->shop_address : '' ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Vendor</button>
        <a href="<?= base_url('vendors'); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>