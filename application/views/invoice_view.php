<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $project->customer_name ?></title>
    <style>
        /* Keep all your existing CSS here... I have omitted it for brevity in the response, 
           but you should paste your <style> block here exactly as it was. */
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #000; }
        .invoice-container { width: 800px; margin: auto; border: 1px solid #000; }
        /* ... paste rest of styles ... */
        .no-print { text-align:center; margin-bottom: 20px; }
        @media print { .no-print, .modal { display: none !important; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #2e75b6; color: white; border: none; border-radius: 4px; font-weight: bold;">
            Print / Download PDF
        </button>
        <button onclick="openModal()" style="padding: 10px 20px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; margin-left: 10px;">
            Edit Invoice Details
        </button>
        <a href="<?= base_url('projects/view/'.$project->id) ?>" style="padding: 10px 20px; text-decoration:none; background: #666; color: white; border-radius: 4px; margin-left: 10px;">Back to Project</a>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Update Invoice Details</div>
            <div class="modal-body">
                <label>Invoice No.</label>
                <input type="text" id="m-inv-no" value="SK/<?= date('y') ?>/001">
                <label>Invoice Date</label>
                <input type="text" id="m-inv-date" value="<?= date('d-m-Y') ?>">
                <label>Buyer's Order No. (PO)</label>
                <input type="text" id="m-po-no" placeholder="Enter PO Number">
                <label>Project Id</label>
                <input type="text" id="m-proj-id" value="<?= $project->id ?>">
                <label>Customer Name</label>
                <input type="text" id="m-cust-name" value="<?= $project->customer_name ?>">
                <hr>
                <label>Description of Work</label>
                <input type="text" id="m-desc" value="Interior Decoration Works">
                <label>HSN Code</label>
                <input type="text" id="m-hsn" value="995414">
                <label style="color: #d9534f;">Total Final Amount (Incl. GST)</label>
                <input type="number" id="m-total-amt" step="0.01" placeholder="Enter Amount">
            </div>
            <div class="modal-footer">
                <button class="btn-close" onclick="closeModal()">Cancel</button>
                <button class="btn-apply" onclick="applyModalChanges()">Apply Changes</button>
            </div>
        </div>
    </div>

    <div class="invoice-container">
        <div class="title-bar">TAX INVOICE</div>

        <div class="header-main">
            <div class="header-left">
                <div class="supplier-info">
                    <h2>SAVIKAN INTERIORS</h2>
                    <div class="supplier-details">
                        <strong>GSTIN: 27AYLPH4299R1ZC</strong><br>
                        <strong>PAN: AYLPH4299R</strong><br>
                        <strong>Address:</strong> 4th Floor, Madhav Sai Apartment,<br>
                        Pimple Gurav, Pune - 411061<br />
                        <strong>Mobile:</strong> +91 7875491137
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="logo-section">
                    <img src="<?= base_url('assets/images/Logo.jpeg') ?>" alt="Logo">
                </div>
                <table class="metadata-table">
                    <tr>
                        <td><span class="label-text">Invoice No.</span><input type="text" id="inv-no" value="SK/<?= date('y') ?>/001"></td>
                        <td><span class="label-text">Invoice Date</span><input type="text" id="inv-date" value="<?= date('d-m-Y') ?>"></td>
                    </tr>
                    <tr><td colspan="2"><span class="label-text">Buyer's Order No.</span><input type="text" id="po-no" value=""></td></tr>
                    <tr>
                        <td><span class="label-text">Project Id</span><input type="text" id="proj-id" value="<?= $project->id ?>"></td>
                        <td><span class="label-text">Customer Name</span><input type="text" id="cust-name" value="<?= $project->customer_name ?>"></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="buyer-section">
            <span class="label-text" style="font-weight: bold; color: #000;">Buyer Details:</span>
            <strong>LIVSPACE INDIA PRIVATE LIMITED</strong><br>
            <strong>GSTIN: 27AADCH4222R2Z8</strong><br>
            Final Plot No 405A, SAI CAPITAL, Pune, Maharashtra - 411004
        </div>

        <table>
            <thead>
                <tr style="background: #f2f2f2;">
                    <th width="5%">Sr.</th>
                    <th width="45%">Description</th>
                    <th width="15%">HSN</th>
                    <th width="35%">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><input type="text" id="desc-work" value="Interior Works"></td>
                    <td><input type="text" id="hsn-input" value="995414" oninput="updateHsnLabel()"></td>
                    <td><input type="text" id="base-amount" class="num-column" value="0.00" readonly></td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2"></td>
                    <td style="text-align: right; font-weight: bold;">CGST (9%)</td>
                    <td id="cgst-display" class="num-column">0.00</td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold;">SGST (9%)</td>
                    <td id="sgst-display" class="num-column">0.00</td>
                </tr>
                <tr style="font-weight:bold; background:#f2f2f2;">
                    <td colspan="3" style="text-align:right;">Grand Total (Incl. GST)</td>
                    <td><input type="text" id="grand-total" style="text-align: right; font-weight: bold;" value="0.00" oninput="updateByReverseTotal()"></td>
                </tr>
            </tbody>
        </table>

        <div style="padding:10px; border-bottom: 1px solid #000;">
            <strong>Amount in words:</strong> <span id="words-area" style="font-style: italic;">Zero Only</span>
        </div>

        <div class="signature-area">
             <img src="<?= base_url('assets/images/sign.jpeg') ?>" alt="Signature" class="sign-img"><br>
             <strong>Authorized Signatory</strong>
        </div>

    </div>

    
    <script>
        function openModal() {
            document.getElementById('m-inv-no').value = document.getElementById('inv-no').value;
            document.getElementById('m-inv-date').value = document.getElementById('inv-date').value;
            document.getElementById('m-po-no').value = document.getElementById('po-no').value;
            document.getElementById('m-po-date').value = document.getElementById('po-date').value;
            document.getElementById('m-proj-id').value = document.getElementById('proj-id').value;
            document.getElementById('m-order-id').value = document.getElementById('order-id').value;
            document.getElementById('m-cust-name').value = document.getElementById('cust-name').value;
            document.getElementById('m-desc').value = document.getElementById('desc-work').value;
            document.getElementById('m-hsn').value = document.getElementById('hsn-input').value;
            document.getElementById('m-total-amt').value = document.getElementById('grand-total').value;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function applyModalChanges() {
            document.getElementById('inv-no').value = document.getElementById('m-inv-no').value;
            document.getElementById('inv-date').value = document.getElementById('m-inv-date').value;
            document.getElementById('po-no').value = document.getElementById('m-po-no').value;
            document.getElementById('po-date').value = document.getElementById('m-po-date').value;
            document.getElementById('proj-id').value = document.getElementById('m-proj-id').value;
            document.getElementById('order-id').value = document.getElementById('m-order-id').value;
            document.getElementById('cust-name').value = document.getElementById('m-cust-name').value;
            document.getElementById('desc-work').value = document.getElementById('m-desc').value;
            document.getElementById('hsn-input').value = document.getElementById('m-hsn').value;

            // Set grand total and trigger reverse calculation
            document.getElementById('grand-total').value = document.getElementById('m-total-amt').value;

            updateHsnLabel();
            updateByReverseTotal();
            closeModal();
        }

        function updateHsnLabel() {
            document.getElementById('hsn-summary-val').innerText = document.getElementById('hsn-input').value;
        }

        function updateByCalculation() {
            const qty = parseFloat(document.getElementById('qty').value) || 0;
            const rate = parseFloat(document.getElementById('rate').value) || 0;
            const amount = qty * rate;
            document.getElementById('base-amount').value = amount.toFixed(2);
            calculateFinals(amount);
        }

        function updateByManualAmount() {
            const amount = parseFloat(document.getElementById('base-amount').value) || 0;
            calculateFinals(amount);
        }

        function updateByReverseTotal() {
            const total = parseFloat(document.getElementById('grand-total').value) || 0;
            const taxable = total / 1.18;
            document.getElementById('base-amount').value = taxable.toFixed(2);
            calculateFinals(taxable, true);
        }

        function calculateFinals(taxableValue, isReverse = false) {
            const cgst = taxableValue * 0.09;
            const sgst = taxableValue * 0.09;
            const total = taxableValue + cgst + sgst;

            document.getElementById('cgst-display').innerText = cgst.toFixed(2);
            document.getElementById('sgst-display').innerText = sgst.toFixed(2);

            if (!isReverse) {
                document.getElementById('grand-total').value = total.toFixed(2);
            }

            document.getElementById('taxable-summary-val').innerText = taxableValue.toFixed(2);
            document.getElementById('taxable-summary-total').innerText = taxableValue.toFixed(2);
            document.getElementById('central-tax-summary').innerText = cgst.toFixed(2);
            document.getElementById('central-tax-total').innerText = cgst.toFixed(2);
            document.getElementById('state-tax-summary').innerText = sgst.toFixed(2);
            document.getElementById('state-tax-total').innerText = sgst.toFixed(2);
            document.getElementById('total-tax-summary').innerText = (cgst + sgst).toFixed(2);
            document.getElementById('total-tax-grand').innerText = (cgst + sgst).toFixed(2);

            document.getElementById('words-area').innerText = numberToWords(Math.round(parseFloat(document.getElementById('grand-total').value))) + " Only";
        }

        function numberToWords(num) {
            var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
            var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            if ((num = num.toString()).length > 9) return 'overflow';
            n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return ''; var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
            return str;
        }
    </script>
</body>
</html>