<div class="container mt-4">
    <h3 class="fw-bold">
        <i class="bi bi-cash-stack text-success"></i> Payments for <?= ucfirst($employee->name) ?>
    </h3>
    <p class="text-muted">Phone: <?= $employee->phone ?></p>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <table id="paymentTable" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Customer</th>
                        <th>Vendor</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($payments as $p): ?>
                    <tr>
                        <td><?= $p->payment_date ? date('d M, Y', strtotime($p->payment_date)) : '---' ?></td>
                        <td><?= $p->project_name ?: '---' ?></td>
                        <td><?= $p->customer_name ?: '---' ?></td>
                        <td><?= $p->vendor_name ?: '---' ?></td>
                        <td class="fw-bold text-success"><?= $p->amount ? '₹'.number_format($p->amount,2) : '---' ?>
                        </td>
                        <td><?= $p->remarks ?: '---' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>