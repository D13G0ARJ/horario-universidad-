<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Registro Docentes')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<header>
<div class="container-fluid">
        <div class="row align-items-center g-0">
            <div class="col-auto">
                <div class="logo">
                    <img src="{{ asset('images/logo.jpg') }}" alt="UNEFA" class="img-fluid">
                </div>
            </div>
            <div class="col">
                <div class="titulo">
                    <h1>Sistema de Horarios</h1>
                </div>
            </div>
        </div>
        
        <!-- Fila de fecha/hora (mantener igual) -->
        <div class="row py-1">
            <div class="col-12 text-end">
                <div class="fecha-hora small">
                    <span id="fecha-hora"></span>
                </div>
            </div>
        </div>
    </div>
</header>

<body>
    @yield('content')




    <!-- Footer -->
<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-section">
            <strong>Copyright © 2025 DR-CB-YA</strong>
        </div>
    </div>
</footer>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

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

</body>

</html>