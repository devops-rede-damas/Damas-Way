<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #1b365d 0%, #2a9d8f 100%);
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 22px;
            margin: 0;
        }
        .email-body {
            padding: 30px;
        }
        .email-body p {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 16px;
        }
        .email-body .btn-reset {
            display: inline-block;
            background: #1b365d;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin: 16px 0;
        }
        .email-body .btn-reset:hover {
            background: #142a4a;
        }
        .email-footer {
            padding: 20px 30px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .email-footer p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Damas Way</h1>
        </div>
        <div class="email-body">
            <p>Olá, <strong>{{ $nome }}</strong>!</p>
            <p>Recebemos uma solicitação para redefinir a senha da sua conta. Clique no botão abaixo para criar uma nova senha:</p>

            <div class="text-center">
                <a href="{{ $url }}" class="btn-reset">Redefinir Senha</a>
            </div>

            <p>Este link é válido por <strong>60 minutos</strong>. Após esse período, será necessário solicitar um novo link.</p>
            <p>Se você não solicitou a redefinição de senha, ignore este e-mail. Sua senha permanecerá inalterada.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Damas Way — Rede Damas Educacional</p>
        </div>
    </div>
</body>
</html>
