<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a Biblioteca Digital</title>
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
            background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 4px solid #d97706;
        }
        .role-badge {
            display: inline-block;
            background-color: #d97706;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4);
        }
        .info-section {
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
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #d97706, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📚 Biblioteca Digital</h1>
            <p>Has sido invitado a unirte a nuestra comunidad</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="welcome-box">
                <h2 style="margin-top: 0; color: #92400e;">¡Bienvenido a nuestra biblioteca!</h2>
                <p style="margin-bottom: 0;">
                    <strong>{{ $invitation->invitedBy->name }}</strong> te ha invitado a formar parte de nuestro sistema de biblioteca digital.
                </p>
                <div class="role-badge">
                    Rol asignado: {{ ucfirst($invitation->role->name) }}
                </div>
            </div>

            <h3 style="color: #92400e;">¿Qué puedes hacer con tu rol de {{ ucfirst($invitation->role->name) }}?</h3>

            @if($invitation->role->name === 'admin')
                <div class="info-section">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Acceso completo a todas las funciones del sistema</li>
                        <li>Invitar nuevos usuarios y asignar roles</li>
                        <li>Gestionar libros, préstamos y usuarios</li>
                        <li>Ver reportes y estadísticas completas</li>
                    </ul>
                </div>
            @elseif($invitation->role->name === 'bibliotecario')
                <div class="info-section">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Gestionar la colección de libros</li>
                        <li>Procesar préstamos y devoluciones</li>
                        <li>Ver estadísticas de la biblioteca</li>
                        <li>Administrar el inventario de libros</li>
                    </ul>
                </div>
            @else
                <div class="info-section">
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Explorar el catálogo completo de libros</li>
                        <li>Solicitar préstamos de libros disponibles</li>
                        <li>Ver tu historial personal de lecturas</li>
                        <li>Recibir notificaciones sobre vencimientos</li>
                    </ul>
                </div>
            @endif

            <div class="divider"></div>

            <div style="text-align: center;">
                <p style="font-size: 18px; margin-bottom: 10px;">
                    <strong>Para completar tu registro, haz clic en el siguiente enlace:</strong>
                </p>

                <a href="{{ $registerUrl }}" class="cta-button">
                    ✨ Completar Registro
                </a>

                <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
                    <strong>Importante:</strong> Este enlace expira el {{ $invitation->expires_at->format('d/m/Y \a \l\a\s H:i') }}
                </p>
            </div>

            <div class="info-section">
                <h4 style="margin-top: 0; color: #92400e;">Detalles de tu invitación:</h4>
                <p><strong>Email:</strong> {{ $invitation->email }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($invitation->role->name) }}</p>
                <p><strong>Invitado por:</strong> {{ $invitation->invitedBy->name }}</p>
                <p><strong>Fecha de invitación:</strong> {{ $invitation->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Si no solicitaste esta invitación, puedes ignorar este email de forma segura.
            </p>
            <p style="margin-top: 15px;">
                <strong>Biblioteca Digital</strong> - Tu puerta al conocimiento
            </p>
        </div>
    </div>
</body>
</html>
