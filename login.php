<?php
session_start();
include("config/database.php");

if(isset($_POST['login'])){

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios
              WHERE correo='$correo'
              AND password='$password'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        if($user['rol'] == 'admin'){
            header("Location: admin/dashboard.php");
        }else{
            header("Location: cliente/dashboard.php");
        }

    }else{
        $error = "Datos incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    background: #f4f6f9;
    font-family: 'Poppins', sans-serif;
}

.login-container{
    height: 100vh;
}

.left-panel{
    background: linear-gradient(135deg,#0d6efd,#0b3d91);
    color: white;
}

.login-card{
    border: none;
    border-radius: 20px;
}

.form-control{
    border-radius: 10px;
    padding: 12px;
}

.btn-primary{
    border-radius: 10px;
    padding: 10px;
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="row login-container">

<div class="col-md-6 d-none d-md-flex align-items-center justify-content-center left-panel">

<div class="text-center">

<h1 class="fw-bold">Ticket System</h1>

<p>Sistema de soporte técnico</p>

</div>

</div>

<div class="col-md-6 d-flex align-items-center justify-content-center">

<div class="card shadow p-4 login-card" style="width:400px;">

<h2 class="text-center mb-4">Iniciar Sesión</h2>

<?php if(isset($error)){ ?>

<div class="alert alert-danger">
<?php echo $error; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Correo</label>
<input type="email" name="correo" class="form-control" required>
</div>

<div class="mb-3">
<label>Contraseña</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" name="login" class="btn btn-primary w-100">
Ingresar
</button>

</form>

</div>

</div>

</div>

</div>

</body>
</html>