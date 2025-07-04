<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | GIAT CORE</title>
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

    .register-card {
      background: #fff;
      border-radius: 16px;
      padding: 40px 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      max-width: 450px;
      width: 100%;
      text-align: center;
    }

    .register-card h2 {
      margin-bottom: 20px;
      color: #8B0000;
      font-weight: 700;
    }

    .register-card p {
      color: #666;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px 14px;
    }

    .btn-register {
      background: linear-gradient(135deg, #FDD835 0%, #FFC107 100%);
      color: #4A0E4E;
      border: none;
      font-weight: 600;
      padding: 12px;
      border-radius: 8px;
      transition: background 0.3s ease;
      width: 100%;
    }

    .btn-register:hover {
      background: linear-gradient(135deg, #FFC107 0%, #FDD835 100%);
      color: #4A0E4E;
    }

    .register-footer {
      margin-top: 20px;
      font-size: 13px;
    }

    .register-footer a {
      color: #8B0000;
      text-decoration: none;
      font-weight: 500;
    }

    .register-footer a:hover {
      text-decoration: underline;
    }

    .register-logo {
      font-size: 32px;
      color: #FDD835;
      font-weight: 900;
      margin-bottom: 10px;
    }

  </style>
</head>
<body>

  <div class="register-card">
    <div class="register-logo">GIAT<span style="color:#8B0000;">CORE</span></div>
    <p>Please fill in the form to register</p>

    <form>
      <div class="mb-3">
        <input type="text" class="form-control" placeholder="Nama" required>
      </div>

      <div class="mb-3">
        <input type="email" class="form-control" placeholder="Email" required>
      </div>

      <div class="mb-3">
        <input type="password" class="form-control" placeholder="Password" required>
      </div>

      <button type="submit" class="btn btn-register">Register</button>
    </form>

    <div class="register-footer">
      <p>Sudah punya akun? <a href="login.html">Login di sini</a></p>
    </div>
  </div>

</body>
</html>
