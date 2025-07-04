<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Biblioteca Digital</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #fef7ed;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #059669;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        .features {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 ¡Bienvenido!</h1>
            <p>Tu cuenta ha sido creada exitosamente</p>
        </div>

        <div class="content">
            <div class="welcome-box">
                <h2 style="margin-top: 0; color: #065f46;">¡Hola {{ $user->name }}!</h2>
                <p>
                    Tu cuenta en la <strong>Biblioteca Digital</strong> ha sido creada exitosamente.
                    Ahora puedes acceder a todos los recursos disponibles según tu rol de
                    <strong>{{ ucfirst($user->role->name) }}</strong>.
                </p>
            </div>

            <h3 style="color: #065f46;">¿Qué puedes hacer ahora?</h3>

            @if($user->role->name === 'admin')
                <div class="features">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Gestionar todos los aspectos del sistema</li>
                        <li>Invitar nuevos usuarios</li>
                        <li>Ver reportes completos</li>
                        <li>Administrar libros y préstamos</li>
                    </ul>
                </div>
            @elseif($user->role->name === 'bibliotecario')
                <div class="features">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Gestionar la colección de libros</li>
                        <li>Procesar préstamos y devoluciones</li>
                        <li>Ver estadísticas de la biblioteca</li>
                        <li>Administrar inventario</li>
                    </ul>
                </div>
            @else
                <div class="features">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Explorar el catálogo de libros</li>
                        <li>Solicitar préstamos</li>
                        <li>Ver tu historial de lecturas</li>
                        <li>Recibir notificaciones</li>
                    </ul>
                </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $dashboardUrl }}" class="cta-button">
                    🚀 Ir al Dashboard
                </a>
            </div>

            <div class="features">
                <h4 style="margin-top: 0; color: #065f46;">Información de tu cuenta:</h4>
                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($user->role->name) }}</p>
                <p><strong>Fecha de registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>
                Si tienes alguna pregunta, no dudes en contactar al administrador.
            </p>
            <p style="margin-top: 15px;">
                <strong>Biblioteca Digital</strong> - Tu puerta al conocimiento
            </p>
        </div>
    </div>
</body>
</html>
