<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sorex</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body, html {
            height: 100%;
            overflow: hidden;
        }

        .bg-container {
            /* Menggunakan gambar background yang Anda berikan */
            background-image: url("{{ asset('img/login_bg.jpg') }}");
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Biar form tetap tengah */
            align-items: center;
            text-align: center;
            padding-top: 70px; 
        }

        .login-card {
            /* Warna latar kartu: Putih agak kemerahan transparan */
            background-color: #e0b2b2; 
            padding: 40px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 360px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px); 
        }

        .logo-img {
            /* Mengatur ukuran logo SOREX */
            max-width: 180px;
            margin-bottom: 5px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .tagline {
            font-size: 10px;
            color: #fff; /* Tulisan tagline putih agar kontras */
            letter-spacing: 1px;
            margin-bottom: 25px;
            font-weight: bold;
            text-transform: uppercase;
            text-shadow: 0px 1px 2px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            background-color: #eef2f7; /* Warna input abu-abu muda/biru muda */
            font-size: 14px;
            color: #333;
            outline: none;
        }

        .form-input::placeholder {
            color: #7a7a7a;
        }

        .form-input:focus {
            background-color: #fff;
            box-shadow: 0 0 0 2px #d62d3e;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #d61c36; /* Merah Sorex */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .btn-login:hover {
            background-color: #b0152b;
        }

        /* Styling tombol Register (Kotak Merah di bawah Login) */
        .btn-register-block {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #d61c36; /* Sama dengan tombol login */
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-register-block:hover {
            background-color: #b0152b;
        }

        .error-message {
            color: #8a0000;
            font-size: 12px;
            text-align: left;
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="bg-container">
        <div class="login-card">
            <img src="{{ asset('img/logooo.png') }}" alt="Sorex Logo" class="logo-img">
            
            <div class="tagline"></div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="Email" required autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-input" placeholder="Password" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            {{-- <a href="{{ route('register') }}" class="btn-register-block">
                Belum punya akun? Daftar di sini
            </a> --}}

        </div>
    </div>

</body>
</html>