<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-light: #2563eb20;
            --brand-dark: #1e40af;
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
        .code-box {
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
                @php
                    $config = getBrandingConfig();
                    $logoPath = $config ? public_path($config->logo_url) : public_path('img/logo_blue.svg');
                @endphp
                <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents($logoPath)) }}" alt="{{ getAppName() }}" style="max-width: 180px; height: auto;">
            </div>
        
            @yield('content')

            <div class="footer">
                <p>{{ __('common.thanks') }},<br>{{ getAppName() }}</p>
            </div>
        </div>
    </div>
</body>
</html>