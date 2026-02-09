<!DOCTYPE html>
<html>
<head>
    <title>Page Expired</title>
    <script>
        // Redirect otomatis ke halaman login
        window.location.href = "{{ route('login') }}"; 
    </script>
</head>
<body>
    <p>Sesi Anda telah berakhir. Mengalihkan ke halaman login...</p>
</body>
</html>