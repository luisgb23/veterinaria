<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veterinaria Itapebí</title>
    <link rel="shortcut icon" href="../img/logo.webp" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!--Custom CSS-->
    <style>
        :root{
            --main-bg: #86A789;
        }
        .main-bg{
            background-color: var(--main-bg) !important;
        }
        input:focus,
        button:focus{
            border: 1px solid var(--main-bg) !important;
            box-shadow: none !important;
        }
        .card, .btn, input{
            border-radius: 0 !important;
        }
        .btn-ingreso{
            background-color: #6bc26b;
        }
        .btn-ingreso:hover{
            background-color: #529d52;
        }
    </style>
</head>
<body class="main-bg">
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-8 col-sm-8">
            <div class="card shadow">
                <div class="card-title text-center mt-2">
                    <img src="" alt="" srcset="img/logo.webp">
                </div>
                <div class="card-body">
                    <form action="login.php" method="post">
                        <div class="mb-4">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="txtUser" id="username" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="txtPwd" id="password" required>
                        </div>
                        <div class="d-grid">
                            <input type="submit" value="Iniciar sesión" class="btn btn-ingreso text-light">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>