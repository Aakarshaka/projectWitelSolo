<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - GIAT CORE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #10b981;
            text-align: center;
            padding: 20px;
            background: #f0fdf4;
            border: 2px dashed #10b981;
            border-radius: 10px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .content {
            margin-bottom: 30px;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">GIAT CORE</div>
            <h1>Email Verification</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for registering with GIAT CORE. To complete your registration, please use the following verification code:</p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <p>Enter this code on the registration page to verify your email address.</p>
            
            <div class="warning">
                <strong>Important:</strong> This verification code will expire in 5 minutes for security reasons. If you didn't request this code, please ignore this email.
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} GIAT CORE. All rights reserved.</p>
        </div>
    </div>
</body>
</html>