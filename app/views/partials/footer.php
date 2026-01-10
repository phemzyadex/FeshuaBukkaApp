</div> <!-- container -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('searchName');
    const statusSelect = document.getElementById('filterStatus');
    const dateInput = document.getElementById('filterDate');

    const rows = document.querySelectorAll('#ordersTable tbody tr[data-name]');

    function filterOrders() {
        const nameVal = nameInput.value.toLowerCase();
        const statusVal = statusSelect.value;
        const dateVal = dateInput.value;

        rows.forEach(row => {
            const matchName = row.dataset.name.includes(nameVal);
            const matchStatus = !statusVal || row.dataset.status === statusVal;
            const matchDate = !dateVal || row.dataset.date === dateVal;

            row.style.display = (matchName && matchStatus && matchDate)
                ? ''
                : 'none';
        });
    }

    nameInput.addEventListener('input', filterOrders);
    statusSelect.addEventListener('change', filterOrders);
    dateInput.addEventListener('change', filterOrders);
});
</script>
</body>
</html>
