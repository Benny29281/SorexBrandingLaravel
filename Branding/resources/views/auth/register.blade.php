<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sorex</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; }
        body, html { height: 100%; overflow-y: auto; } /* Allow scroll for register page */
        
        .bg-container {
            background-image: url("{{ asset('img/login_bg.jpg') }}");
            background-size: cover;
            background-position: center;
            min-height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background-color: #e0b2b2; 
            padding: 40px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 360px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px); 
        }

        .logo-img { max-width: 150px; margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto; }
        .tagline { font-size: 10px; color: #fff; letter-spacing: 1px; margin-bottom: 20px; font-weight: bold; text-transform: uppercase; text-shadow: 0px 1px 2px rgba(0,0,0,0.2); }
        
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-label { font-size: 12px; color: #fff; font-weight: bold; margin-bottom: 5px; display: block; text-shadow: 0 1px 1px rgba(0,0,0,0.2); }
        
        .form-input, .form-select {
            width: 100%; padding: 12px 15px; border: none; border-radius: 8px;
            background-color: #eef2f7; font-size: 14px; color: #333; outline: none;
        }
        .form-input:focus, .form-select:focus { background-color: #fff; box-shadow: 0 0 0 2px #d62d3e; }

        .btn-register {
            width: 100%; padding: 12px; background-color: #d61c36; color: white; border: none;
            border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;
        }
        .btn-register:hover { background-color: #b0152b; }

        .login-link { display: block; margin-top: 20px; font-size: 13px; color: #fff; text-decoration: underline; text-shadow: 0 1px 1px rgba(0,0,0,0.2); }
        .error-message { color: #8a0000; font-size: 11px; margin-top: 2px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="bg-container">
        <div class="login-card">
            <img src="{{ asset('img/logo5.png') }}" alt="Sorex Logo" class="logo-img">
            <div class="tagline">REGISTRASI AKUN BARU</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Regional</label>
                    <select name="regional" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Regional --</option>
                        
                        <option value="JB_JR">Regional 1 (JB & JR)</option>
                        
                        <option value="JT_DK_LP">Regional 2 (JT, DK & LP)</option>
                    </select>
                    @error('regional') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn-register">Daftar Sekarang</button>
            </form>

            <a href="{{ route('login') }}" class="login-link">Sudah punya akun? Login di sini</a>
        </div>
    </div>

</body>
</html>