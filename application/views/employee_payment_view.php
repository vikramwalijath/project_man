<div class="container mt-4">
    <h4 class="fw-bold">
        <i class="bi bi-cash-stack text-success"></i> Payments to
        <span class="badge bg-soft-warning text-warning border border-warning-subtle">
            <?= ucfirst($employee->name) ?>
        </span>

    </h4>
    <p class="text-muted">
        Category: <?= ucfirst($employee->type) ?> | Phone: <?= $employee->phone ?: '---' ?>
    </p>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <table id="paymentTable" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Amount Paid</th>
                        <th>Paid By</th>
                        <th>Remarks</th>
                        <th>Attachment</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($payments as $p): ?>
                    <tr>
                        <td><?= $p->payment_date ? date('d M, Y', strtotime($p->payment_date)) : '---' ?></td>
                        <td><?= $p->project_name ?? '---' ?></td>
                        <td class="fw-bold text-success">
                            <?= $p->amount_paid ? '₹'.number_format($p->amount_paid,2) : '---' ?>
                        </td>
                        <td><?= $p->paid_by_name ?: '---' ?></td>
                        <td><?= $p->remarks ?: '---' ?></td>
                        <td>
                            <?php if($p->file_attachment): ?>
                            <a href="<?= base_url('uploads/payments/'.$p->file_attachment) ?>" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-paperclip"></i> View
                            </a>
                            <?php else: ?>
                            ---
                            <?php endif; ?>
                        </td>
                        <td><?= $p->created_by_name ?: '---' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-secondary fw-bold">
                        <td colspan="2">Total</td>
                        <td class="text-success">
                            ₹<?= number_format(array_sum(array_map(function($p) { return (float)$p->amount_paid; }, $payments)), 2) ?>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>