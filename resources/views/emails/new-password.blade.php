<!DOCTYPE html>
<html>
<head>
    <title>{{ __('forgot-password.new_password_email.title') }}</title>
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-light: #dbeafe;
            --brand-dark: #1e3a8a;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            width: 100%;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f9fafb;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            background-color: white;
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .password-box {
            background: linear-gradient(135deg, var(--brand-light), #f3f4f6);
            padding: 25px 30px;
            border-radius: 12px;
            margin: 35px auto;
            display: inline-block;
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 4px;
            min-width: 280px;
            border: 3px solid var(--brand-primary);
            color: var(--brand-dark);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }
        .footer {
            margin-top: 50px;
            color: #6b7280;
            font-size: 14px;
            border-top: 2px solid var(--brand-light);
            padding-top: 25px;
        }
        .logo {
            margin-bottom: 35px;
        }
        h1 {
            color: var(--brand-primary);
            font-size: 28px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="logo">
                <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('img/logo_blue.svg'))) }}" alt="{{ config('app.name') }}" style="max-width: 180px; height: auto;">
            </div>
        
        <h1>{{ __('forgot-password.new_password_email.title') }}</h1>
        
        <p style="margin-bottom: 20px;">
            {{ __('forgot-password.new_password_email.intro') }}
        </p>

        <div class="password-box">
            {{ $newPassword }}
        </div>

        <p style="margin-top: 20px;">
            {{ __('forgot-password.new_password_email.security_note') }}
        </p>

        <p style="margin-top: 10px; font-style: italic;">
            {{ __('forgot-password.new_password_email.no_action_required') }}
        </p>

            <div class="footer">
                <p>{{ __('common.thanks') }},<br>{{ config('app.name') }}</p>
            </div>
        </div>
    </div>
</body>
</html>