<footer class="text-center py-3 mt-5">
    <small>
        &copy; 2026 Gaji Karyawan by raka
    </small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(function () {
        const alert = document.querySelector('.alert');

        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);
</script>
</body>
</html>