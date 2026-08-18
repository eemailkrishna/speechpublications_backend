<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your OTP for Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px 20px;
            text-align: center;
        }

        .content p {
            color: #333;
            line-height: 1.6;
            margin: 15px 0;
        }

        .otp-box {
            background-color: #f0f0f0;
            border: 2px solid #0d6efd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 5px;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Login Verification</h1>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p>You requested to login to your account. Please use the following One-Time Password (OTP) to verify your identity:</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p><strong>This OTP will expire in 5 minutes.</strong></p>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <p>If you did not request this OTP, please ignore this email. Do not share this OTP with anyone.</p>
            </div>

            <p>If you have any questions or need assistance, please contact our support team.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Speech Publications. All rights reserved.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
