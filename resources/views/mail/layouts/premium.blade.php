<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.08);
        }
        .header {
            background-color: #101827;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .brand-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(90deg, #00d4ff 0%, #e11cc8 100%);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 900;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }
        .content {
            padding: 40px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            background-color: #f1f5f9;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(90deg, #00d4ff 0%, #e11cc8 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 25px;
            box-shadow: 0 10px 20px -5px rgba(0, 212, 255, 0.3);
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background-color: #f1f5f9;
            color: #475569;
            margin-bottom: 20px;
        }
        .highlight-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-logo">NX</div>
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            © {{ date('Y') }} NEXO-PRO - PREMIUM PROPTECH PLATFORM<br>
            TRANSFORMANDO LA VIDA EN COMUNIDAD
        </div>
    </div>
</body>
</html>
