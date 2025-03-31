<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Horarios')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body class="login-layout">
    <div class="container-fluid h-100 px-0"> <!-- px-0 elimina padding horizontal -->
        <div class="row g-0 h-100"> <!-- g-0 elimina gutters de Bootstrap -->
            <!-- Columna izquierda (Imagen) -->
            <div class="col-lg-6 d-none d-lg-block login-image"></div>

            <!-- Columna derecha (Formulario) -->
            <div class="col-lg-6 d-flex align-items-center">
                <div class="w-100 px-4 px-lg-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer delgado -->
    <footer class="login-footer">
    <div class="container-fluid">
        <div class="footer-grid">
            <!-- Espacio izquierdo vacío -->
            <div class="empty-space"></div>
            
            <!-- Copyright centrado -->
            <strong class="copyright">Copyright © 2026 DR-CB-YA</strong>
            
            <!-- Fecha/hora alineada a la derecha -->
            <div class="fecha-hora">
                <span id="fecha-hora"></span>
            </div>
        </div>
    </div>
</footer>

<!-- Mantén el script de fecha/hora -->
<script>
    function actualizarFechaHora() {
        const opciones = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        const fechaHora = new Date().toLocaleDateString('es-ES', opciones);
        document.getElementById('fecha-hora').textContent = fechaHora;
    }
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de fecha/hora (opcional) -->
    <script>
        // (Mantén tu script actual si lo necesitas)
    </script>
</body>

</html>