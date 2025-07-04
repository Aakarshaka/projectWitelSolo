<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | GIAT CORE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #8B0000 0%, #4A0E4E 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }

    .login-card {
      background: #fff;
      border-radius: 16px;
      padding: 50px 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .login-logo {
      font-size: 36px;
      color: #FDD835;
      font-weight: 900;
      margin-bottom: 20px;
    }

    .login-card p {
      color: #555;
      margin-bottom: 35px;
      font-size: 15px;
    }

    .btn-google {
      background: #fff;
      color: #444;
      border: 1px solid #ddd;
      font-weight: 600;
      padding: 14px;
      border-radius: 10px;
      transition: background 0.3s ease;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 16px;
      text-decoration: none;
    }

    .btn-google img {
      width: 24px;
      height: 24px;
    }

    .btn-google:hover {
      background: #f8f8f8;
    }

    .login-footer {
      margin-top: 30px;
      font-size: 13px;
      color: #999;
    }

  </style>
</head>
<body>

  <div class="login-card">
    <div class="login-logo">GIAT<span style="color:#8B0000;">CORE</span></div>
    <p>Login menggunakan akun perusahaan Anda</p>

    <a href="{{ url('/dashboard') }}" class="btn-google">
      <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Icon">
      Login with Google
    </a>

    <div class="login-footer">
      <p>Powered by <strong>GIAT CORE</strong></p>
    </div>
  </div>

</body>
</html>
