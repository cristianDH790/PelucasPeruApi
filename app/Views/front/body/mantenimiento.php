<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>pelucas perú</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #E0D2C1;
            font-family: 'Raleway', sans-serif;
            color: #3b3b3b;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .icon {
            font-size: 60px;
            color: #8B6A4F;
            margin-bottom: 20px;
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #5e5e5e;
        }

        /* Loader styles */
        .loader {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: #8B6A4F;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .dot:nth-child(3) {
            animation-delay: 0;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            } 
            40% {
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media screen and (max-width: 600px) {
            h1 { font-size: 32px; }
            p { font-size: 16px; }
            .icon { font-size: 48px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://pelucasperu.com/demo/public/template/images/milislens-logo.png" alt="Milislens Logo" class="logo">
        <div class="icon">🛠️</div>
        <h1>Estamos en mantenimiento</h1>
        <p>Estamos trabajando para mejorar tu experiencia.<br>Regresa pronto.</p>
        <div class="loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <footer>
            &copy; 2025 Milislens. Todos los derechos reservados.
        </footer>
    </div>
</body>
</html>
