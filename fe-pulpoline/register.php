<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h1 class="card-title text-center">Registro</h1>
            <form id="registerForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
                <a href="Login.php" class="card-link mt-3">¿Ya tienes una cuenta? Inicia Sesión</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevenir el envío del formulario

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('http://localhost:3000/api/users/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username: email, password: password })
                });

                if (!response.ok) {
                    throw new Error('Error en el registro');
                }

                const data = await response.json();
                localStorage.setItem('token', data.token); // Guardar el token en localStorage
                localStorage.setItem('jwt', data.token); // Cambia 'token' por 'jwt'
                alert('Registro exitoso!'); // Manejo exitoso
                window.location.href = 'ListarToken.php'; // Redireccionar a otra página

            } catch (error) {
                alert('Error: ' + error.message); // Manejo de errores
            }
        });
    </script>
</body>

</html>