<script>
    {{-- Refresh Token setiap 15 Menit (900.000 ms) --}}
    setInterval(function() {
        fetch("{{ route('refresh.csrf') }}")
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('meta[name="csrf-token"]').forEach(tag => tag.setAttribute('content', data.csrf_token));
                document.querySelectorAll('input[name="_token"]').forEach(input => input.value = data.csrf_token);
                console.log("Session Keep-Alive: " + new Date().toLocaleTimeString());
            })
            .catch(error => console.error('Gagal refresh token:', error));
    }, 900000); 
</script>