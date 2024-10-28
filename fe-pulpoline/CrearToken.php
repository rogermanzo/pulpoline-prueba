<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column align-items-center">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Crear Nuevo Token</h1>

        <form action="http://localhost:3000/api/users/create-token-hedera" method="post" id="createTokenForm">
            <div class="mb-3">
                <label for="token_Name" class="form-label">Nombre del Token</label>
                <input type="text" class="form-control" id="token_Name" name="token_name" required>
            </div>
            <div class="mb-3">
                <label for="token_Simbolo" class="form-label">Símbolo del Token</label>
                <input type="text" class="form-control" id="token_Simbolo" name="token_simbolo" required>
            </div>
            <div class="mb-3">
                <label for="suministro" class="form-label">Suministro Inicial</label>
                <input type="number" class="form-control" id="suministro" name="suministro_inicial" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Token</button>
            <a href="ListarToken.php" class="btn btn-primary">Atrás</a>
        </form>

    </div>
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
<script>
    document.getElementById('createTokenForm').addEventListener('submit', async function (event) {
        event.preventDefault(); // Evita el envío normal del formulario

        const token = 'Bearer ' + localStorage.getItem('jwt'); // Obtener el token JWT almacenado

        const formData = new FormData(this);
        const data = {
            name: formData.get('token_name'),
            symbol: formData.get('token_simbolo'),
            initialSupply: parseInt(formData.get('suministro_inicial'))
        };

        try {
            const response = await fetch('http://localhost:3000/api/users/create-token-hedera', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert('Token creado exitosamente: ' + result.tokenId);
                window.location.href = 'ListarToken.php';
            } else {
                alert('Error al crear el token: ' + result.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        }
    });

</script>