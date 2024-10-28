<!-- includes/header.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? 'Mi Proyecto'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="ListarToken.php">Mi Proyecto</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logoutBtn">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
        document.getElementById('logoutBtn').addEventListener('click', async function (event) {
            event.preventDefault(); // Evita la acción predeterminada del enlace

            try {
                const response = await fetch('http://localhost:3000/api/users/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                if (response.ok) {
                    // Limpia el local storage
                    localStorage.removeItem('token'); // Elimina el token
                    localStorage.removeItem('jwt'); // Elimina el jwt

                    console.log('Logout exitoso');
                    // Redirigir a la página de login
                    window.location.href = 'Login.php';
                } else {
                    console.error('Error en el logout');
                    alert('Error al cerrar sesión');
                }
            } catch (error) {
                console.error('Error de red:', error);
            }
        });
    </script>