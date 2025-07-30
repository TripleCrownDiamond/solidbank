<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        @php
            $config = getBrandingConfig();
            $brandPrimary = $config && $config->brand_color ? $config->brand_color : '#2563eb';
            $brandPrimaryHover = $config && $config->brand_primary_hover ? $config->brand_primary_hover : '#1d4ed8';
            $brandPrimaryLight = $config && $config->brand_primary_light ? $config->brand_primary_light : '#dbeafe';
            $brandPrimaryDark = $config && $config->brand_primary_dark ? $config->brand_primary_dark : '#1e40af';
        @endphp
        :root {
            --brand-primary: {{ $brandPrimary }};
            --brand-primary-hover: {{ $brandPrimaryHover }};
            --brand-light: {{ $brandPrimaryLight }};
            --brand-dark: {{ $brandPrimaryDark }};
            --text-primary: #1f2937;
            --bg-light: #f9fafb;
            --border-light: #e5e7eb;
            --text-muted: #6b7280;
            --text-secondary: #6b7280;
            --bg-secondary: #f3f4f6;
            --primary-color: {{ $brandPrimary }};
            --success-bg: #f0fdf4;
            --success-border: #22c55e;
            --success-text: #15803d;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            width: 100%;
        }
        .email-wrapper {
            width: 100%;
            background-color: var(--bg-light);
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
            border: 1px solid var(--border-light);
        }
        .code-box {
            background: linear-gradient(135deg, var(--brand-light), var(--bg-secondary));
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
            color: var(--text-muted);
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
        .button {
            display: inline-block;
            background-color: var(--brand-primary);
            color: white !important;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .button:hover {
            background-color: var(--brand-primary-hover);
        }
        .transaction-details {
            background: linear-gradient(135deg, var(--success-bg), var(--bg-secondary));
            padding: 25px;
            border-radius: 12px;
            margin: 25px auto;
            border: 2px solid var(--success-border);
            text-align: center;
            max-width: 500px;
        }
        .transaction-details h3 {
            color: var(--success-text);
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-light);
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: var(--success-text);
        }
        .detail-value {
            color: var(--success-text);
        }
        .amount-value {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--success-text);
        }
        .message-success {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
            color: var(--text-primary);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="logo">
                @php
                    $config = getBrandingConfig();
                    $iconUrl = $config && $config->icon_url ? $config->icon_url : 'img/logo_blue.svg';
                    $iconPath = public_path($iconUrl);
                    $appName = getAppName();
                @endphp
                @if(file_exists($iconPath))
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents($iconPath)) }}" alt="{{ $appName }}" style="max-width: 80px; height: auto;">
                @else
                    <img src="{{ asset('img/logo_blue.svg') }}" alt="{{ $appName }}" style="max-width: 80px; height: auto;">
                @endif
            </div>
        
            @yield('content')

            <div class="footer">
                <p>{{ __('common.thanks') }},<br>{{ getAppName() }}</p>
                @php
                    $bankConfig = \App\Helpers\BankConfigHelper::getConfig();
                @endphp
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                    <p style="margin: 0; font-size: 12px; color: var(--text-muted); line-height: 1.4;">
                        <strong>{{ $bankConfig->bank_name ?? config('app.name') }} - Services Bancaires Numériques</strong><br>
                        {{ $bankConfig->bank_address ?? '29 Rue du Faubourg, Paris, France' }}<br>
                        Email: {{ $bankConfig->bank_email ?? bank_config('bank_email', 'contact@dbqic.com') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>