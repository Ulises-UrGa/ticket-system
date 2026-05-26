<?php
session_start();
include("../config/database.php");

if($_SESSION['rol'] != 'admin'){
    header("Location: ../login.php");
}

/* CREAR USUARIO */

if(isset($_POST['crear'])){

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $insert = "INSERT INTO usuarios(
        nombre,
        correo,
        password,
        telefono,
        rol
    ) VALUES(
        '$nombre',
        '$correo',
        '$password',
        '$telefono',
        '$rol'
    )";

    mysqli_query($conn,$insert);

    header("Location: usuarios.php");
}

/* ELIMINAR */

if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    mysqli_query($conn,
    "DELETE FROM usuarios WHERE id='$id'");

    header("Location: usuarios.php");
}

/* EDITAR */

if(isset($_POST['editar'])){

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    mysqli_query($conn,

    "UPDATE usuarios SET

    nombre='$nombre',
    correo='$correo',
    telefono='$telefono',
    rol='$rol'

    WHERE id='$id'
    ");

    header("Location: usuarios.php");
}

$usuarios = mysqli_query($conn,
"SELECT * FROM usuarios ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h3>Ticket System</h3>

<a href="dashboard.php">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="tickets.php">
<i class="fa-solid fa-ticket"></i> Tickets
</a>

<a href="usuarios.php">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="../logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
</a>

</div>

<!-- MAIN -->

<div class="main-content">

<div class="topbar d-flex justify-content-between align-items-center">

<h4>Gestión de Usuarios</h4>

<button
class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#crearModal">

<i class="fa-solid fa-plus"></i>
Nuevo Usuario

</button>

</div>

<!-- TABLA -->

<div class="table-container">

<table class="table table-hover align-middle">

<thead>

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Correo</th>
<th>Teléfono</th>
<th>Rol</th>
<th>Estado</th>
<th>Acciones</th>
</tr>

</thead>

<tbody>

<?php while($user = mysqli_fetch_assoc($usuarios)){ ?>

<tr>

<td>#<?php echo $user['id']; ?></td>

<td><?php echo $user['nombre']; ?></td>

<td><?php echo $user['correo']; ?></td>

<td><?php echo $user['telefono']; ?></td>

<td>

<?php

if($user['rol'] == 'admin'){
    echo "<span class='badge bg-danger'>Admin</span>";
}else{
    echo "<span class='badge bg-primary'>Cliente</span>";
}

?>

</td>

<td>

<?php

if($user['estado'] == 'activo'){
    echo "<span class='badge bg-success'>Activo</span>";
}else{
    echo "<span class='badge bg-secondary'>Inactivo</span>";
}

?>

</td>

<td>

<button
class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editar<?php echo $user['id']; ?>">

<i class="fa-solid fa-pen"></i>

</button>

<a
href="usuarios.php?eliminar=<?php echo $user['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('¿Eliminar usuario?')">

<i class="fa-solid fa-trash"></i>

</a>

</td>

</tr>

<!-- MODAL EDITAR -->

<div class="modal fade"
id="editar<?php echo $user['id']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5>Editar Usuario</h5>

<button
class="btn-close"
data-bs-dismiss="modal"></button>

</div>

<form method="POST">

<div class="modal-body">

<input
type="hidden"
name="id"
value="<?php echo $user['id']; ?>">

<div class="mb-3">

<label>Nombre</label>

<input
type="text"
name="nombre"
class="form-control"
value="<?php echo $user['nombre']; ?>"
required>

</div>

<div class="mb-3">

<label>Correo</label>

<input
type="email"
name="correo"
class="form-control"
value="<?php echo $user['correo']; ?>"
required>

</div>

<div class="mb-3">

<label>Teléfono</label>

<input
type="text"
name="telefono"
class="form-control"
value="<?php echo $user['telefono']; ?>">

</div>

<div class="mb-3">

<label>Rol</label>

<select
name="rol"
class="form-select">

<option value="admin"
<?php if($user['rol']=='admin') echo 'selected'; ?>>

Admin

</option>

<option value="cliente"
<?php if($user['rol']=='cliente') echo 'selected'; ?>>

Cliente

</option>

</select>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="editar"
class="btn btn-primary">

Guardar Cambios

</button>

</div>

</form>

</div>

</div>

</div>

<?php } ?>

</tbody>

</table>

</div>

</div>

<!-- MODAL CREAR -->

<div class="modal fade" id="crearModal">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5>Nuevo Usuario</h5>

<button
class="btn-close"
data-bs-dismiss="modal"></button>

</div>

<form method="POST">

<div class="modal-body">

<div class="mb-3">

<label>Nombre</label>

<input
type="text"
name="nombre"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Correo</label>

<input
type="email"
name="correo"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Contraseña</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Teléfono</label>

<input
type="text"
name="telefono"
class="form-control">

</div>

<div class="mb-3">

<label>Rol</label>

<select
name="rol"
class="form-select">

<option value="cliente">Cliente</option>
<option value="admin">Admin</option>

</select>

</div>

</div>

<div class="modal-footer">

<button
type="submit"
name="crear"
class="btn btn-primary">

Crear Usuario

</button>

</div>

</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>