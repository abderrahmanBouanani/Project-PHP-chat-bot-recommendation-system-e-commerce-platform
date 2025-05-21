<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #3b5d50;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3b5d50;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ShopAll</h1>
        </div>
        <div class="content">
            <h2>Bonjour {{ $user->prenom }},</h2>
            <p>Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.</p>
            <p>Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Réinitialiser le mot de passe</a>
            </p>
            <p>Ce lien de réinitialisation de mot de passe expirera dans 60 minutes.</p>
            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise.</p>
            <p>Cordialement,<br>L'équipe ShopAll</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ShopAll. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
