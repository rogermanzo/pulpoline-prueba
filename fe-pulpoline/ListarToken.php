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

    <h1 class="card-title">Dashboard</h1>

    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4>Listado de Tokens</h4>
            </div>
            <a href="CrearToken.php" class="btn btn-primary">Crear Token</a>
        </div>

        <ul class="list-group mb-3 mt-3" id="tokenList"></ul>

        <script>
            async function fetchTokens() {
                const token = 'Bearer ' + localStorage.getItem('jwt'); // Obtener el token JWT almacenado

                try {
                    const response = await fetch('http://localhost:3000/api/users/list-tokens', {
                        method: 'GET',
                        headers: {
                            'Authorization': token,
                            'Content-Type': 'application/json'
                        }
                    });

                    const tokens = await response.json();
                    const tokenList = document.getElementById('tokenList');

                    if (response.ok) {
                        if (tokens.length > 0) {
                            tokens.forEach(token => {
                                const listItem = document.createElement('li');
                                listItem.className = 'list-group-item';
                                listItem.textContent = `${token.name} (${token.symbol}): ${token.initialSupply}`;
                                tokenList.appendChild(listItem);
                            });
                        } else {
                            tokenList.innerHTML = '<li class="list-group-item">No se encontraron tokens.</li>';
                        }
                    } else {
                        tokenList.innerHTML = '<li class="list-group-item">Error al cargar los tokens.</li>';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    const tokenList = document.getElementById('tokenList');
                    tokenList.innerHTML = '<li class="list-group-item">Error al conectar con el servidor.</li>';
                }
            }
            // Llama a la función cuando la página cargue
            fetchTokens();
        </script>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>