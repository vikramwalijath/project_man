<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savikan Interiors - Invoice</title>
    <style>
    /* [I am keeping all your CSS exactly as you provided it] */
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        margin: 20px;
        color: #000;
    }

    .invoice-container {
        width: 800px;
        margin: auto;
        border: 1px solid #000;
    }

    .header-main {
        display: flex;
        border-bottom: 1px solid #000;
    }

    .header-left {
        width: 50%;
        padding: 12px;
        border-right: none;
    }

    .header-right {
        width: 50%;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .title-bar {
        text-align: center;
        font-size: 22px;
        color: #2e75b6;
        border-bottom: 1px solid #000;
        padding: 8px;
        font-weight: bold;
        background-color: #fcfcfc;
    }

    .supplier-info h2 {
        margin: 0 0 8px 0;
        font-size: 20px;
        color: #000;
    }

    .supplier-details {
        line-height: 1.5;
        font-size: 11px;
    }

    .metadata-table {
        width: 100%;
        border-collapse: collapse;
        height: 100%;
    }

    .metadata-table td {
        border: none;
        border-bottom: 1px solid #000;
        border-left: 1px solid #000;
        padding: 6px 8px;
        width: 50%;
    }

    .metadata-table tr:last-child td {
        border-bottom: none;
    }

    .label-text {
        display: block;
        font-size: 10px;
        color: #444;
        margin-bottom: 2px;
    }

    .value-text {
        font-weight: bold;
        font-size: 11px;
    }

    .logo-section {
        padding: 10px;
        text-align: right;
        border-bottom: 1px solid #000;
        border-left: 1px solid #000;
    }

    .logo-section img {
        max-width: 140px;
        height: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: top;
    }

    .buyer-section {
        padding: 12px;
        border-bottom: 1px solid #000;
        line-height: 1.6;
        background-color: #fafafa;
    }

    input {
        border: none;
        font-family: inherit;
        font-size: inherit;
        font-weight: bold;
        width: 100%;
        background: transparent;
    }

    input:focus {
        background: #fffde7;
        outline: none;
    }

    .num-column {
        text-align: right;
    }

    .hsn-summary th {
        background: #f2f2f2;
        font-size: 10px;
    }

    .bank-details {
        display: flex;
        border-top: 1px solid #000;
    }

    .bank-info {
        width: 70%;
        padding: 10px;
        line-height: 1.5;
    }

    .signature-area {
        width: 30%;
        text-align: center;
        padding: 10px;
    }

    .sign-img {
        height: 75px;
        margin: 5px 0;
    }

    @media print {
        @page {
            size: A4;
            margin: 10mm;
        }

        .no-print {
            display: none;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width: 98% !important;
            margin: 0 !important;
            border: 1px solid #000;
        }
    }
    </style>
</head>

<body onload="updateByReverseTotal()">

    <div class="no-print" style="text-align:center; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; cursor: pointer; background: #2e75b6; color: white; border: none; border-radius: 4px; font-weight: bold;">
            Print / Download PDF
        </button>
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
                        <strong>Address:</strong> 4th Floor, S No 14/1, F No 403, Madhav Sai Apartment,<br>
                        Lane Number 2, Shivneri Colony, Pimple Gurav,<br>
                        Pimpri Chinchwad, Pune, Maharashtra,<br>
                        Pin Code: 411061<br />
                        <strong>Mobile:</strong> +91 7875491137<br>
                        <strong>Email:</strong> savikaninteriors@gmail.com
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="logo-section">
                    <img src="<?= base_url('assets/images/Logo.jpeg') ?>" alt="Logo">
                </div>
                <table class="metadata-table">
                    <tr>
                        <td>
                            <span class="label-text">Invoice No.</span>
                            <span class="value-text"><input type="text"
                                    value="<?= $saved_invoice->invoice_no ?>"></span>
                        </td>
                        <td>
                            <span class="label-text">Invoice Date</span>
                            <span class="value-text"><input type="text"
                                    value="<?= date('d-m-Y', strtotime($saved_invoice->invoice_date)) ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="label-text" style="font-weight: bold; color: #000;">Supplier's Reference</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="label-text">Buyer's Order No.</span>
                            <span class="value-text"><input type="text"
                                    value="<?= $project->buyers_order_no  ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="label-text">PO Confirmed Date</span>
                            <span class="value-text"><input type="text" value="<?= $project->po_date ?>"></span>
                        </td>
                        <td>
                            <span class="label-text">Project Id</span>
                            <span class="value-text"><input type="text"
                                    value="<?= $project->project_external_id ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="label-text">Order Id</span>
                            <span class="value-text"><input type="text"
                                    value="<?= $project->order_id ?? '178378' ?>"></span>
                        </td>
                        <td>
                            <span class="label-text">Customer Name</span>
                            <span class="value-text"><input type="text" value="<?= $project->customer_name ?>"></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="buyer-section">
            <span class="label-text" style="font-weight: bold; color: #000;">Buyer Details:</span>
            <strong>LIVSPACE INDIA PRIVATE LIMITED</strong><br>
            <strong>GSTIN: 27AADCH4222R2Z8</strong><br>
            <span style="font-size: 10px; color: #555;">[Formerly- Home Interior Designs ECommerce Private
                Limited]</span><br>
            Final Plot No 405A, SAI CAPITAL, CTS NO 984A TPS NO 1, Sai Spacecon, Senapati Bapat Road,<br>
            Pune, Maharashtra, India - Pin: 411004
        </div>

        <table>
            <thead>
                <tr style="background: #f2f2f2;">
                    <th width="5%">Sr.</th>
                    <th width="35%">Description</th>
                    <th width="10%">HSN</th>
                    <th width="5%">Qty</th>
                    <th width="12%">Rate</th>
                    <th width="16%">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><?= !empty($saved_invoice->description) ? $saved_invoice->description : 'Interior Works' ?></td>
                    <td><input type="text" id="hsn-input" value="<?= $project->hsn_code ?>" oninput="updateHsnLabel()"
                            readonly></td>
                    <td><input type="number" id="qty" value="1" oninput="updateByCalculation()" readonly></td>
                    <td><input type="text" id="rate" value="0" oninput="updateByCalculation()"></td>
                    <td><input type="text" id="base-amount" class="num-column" value="0.00" readonly
                            oninput="updateByManualAmount()"></td>
                </tr>
                <tr>
                    <td colspan="4" rowspan="2" style="border-bottom: none;"></td>
                    <td style="text-align: right; font-weight: bold;">CGST (9%)</td>
                    <td id="cgst-display" class="num-column">0.00</td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold;">SGST (9%)</td>
                    <td id="sgst-display" class="num-column">0.00</td>
                </tr>
                <tr style="font-weight:bold; background:#f2f2f2;">
                    <td colspan="5" style="text-align:right;">Total</td>
                    <td class="num-column">
                        <input type="text" id="grand-total" style="text-align: right; font-weight: bold;"
                            value="<?= $saved_invoice->amount ?>" step="0.01" oninput="updateByReverseTotal()" readonly>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="padding:10px; border-bottom: 1px solid #000;">
            <strong>Amount Chargeable (in words):</strong> <span id="words-area"
                style="font-style: italic; text-transform: capitalize;">Zero Only</span>
        </div>

        <table class="hsn-summary">
            <thead>
                <tr>
                    <th rowspan="2">HSN Code</th>
                    <th rowspan="2">Taxable Value</th>
                    <th colspan="2">Central Tax</th>
                    <th colspan="2">State Tax</th>
                    <th rowspan="2">Total Tax</th>
                </tr>
                <tr>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="hsn-summary-val"><?= $project->hsn_code ?></td>
                    <td id="taxable-summary-val">0.00</td>
                    <td>9%</td>
                    <td id="central-tax-summary">0.00</td>
                    <td>9%</td>
                    <td id="state-tax-summary">0.00</td>
                    <td id="total-tax-summary">0.00</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td>Total</td>
                    <td id="taxable-summary-total">0.00</td>
                    <td></td>
                    <td id="central-tax-total">0.00</td>
                    <td></td>
                    <td id="state-tax-total">0.00</td>
                    <td id="total-tax-grand">0.00</td>
                </tr>
            </tbody>
        </table>

        <div class="bank-details">
            <div class="bank-info">
                <strong>Bank Details:</strong><br>
                Bank Name: <strong>HDFC BANK</strong><br>
                A/c Holder: <strong>SAVIKAN INTERIORS</strong><br>
                Account No: <strong>50200110407875</strong><br>
                IFSC Code: <strong>HDFC0000900</strong> <br>
                Branch: <strong>Kawade Nagar, New Sangavi, Pune</strong>
            </div>
            <div class="signature-area">
                <p style="margin:0; font-size: 10px;"><em>From: SAVIKAN INTERIORS</em></p>
                <img src="<?= base_url('assets/images/sign.jpeg') ?>" alt="Signature" class="sign-img"><br>
                <strong>Authorized Signatory</strong>
            </div>
        </div>
    </div>

    <script>
    // Your existing JS functions [No changes made to logic]
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
        document.getElementById('rate').value = taxable.toFixed(2);
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

        document.getElementById('words-area').innerText = numberToWords(Math.round(total)) + " Only";
    }

    function numberToWords(num) {
        var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ',
            'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '
        ];
        var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return '';
        var str = '';
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