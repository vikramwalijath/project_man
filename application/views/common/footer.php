<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $(document).ready(function() {
        $('#projectTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            searching: true
        });
    });
});

$(document).ready(function() {
    $('#vendorTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "ordering": true,
        "searching": true,
        "language": {
            "search": "Search Vendors:"
        }
    });
});
$(document).ready(function() {
    $('#paymentTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "ordering": true,
        "searching": true,
        "language": {
            "search": "Search Payments:"
        }
    });
});
$(document).ready(function() {
    $('#invoiceTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "ordering": true,
        "searching": true,
        "language": {
            "search": "Search Invoices:"
        }
    });
});
$(document).ready(function() {
    $('#employeeTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        ordering: true,
        searching: true,
        language: {
            search: "Filter records:"
        }
    });
});
</script>