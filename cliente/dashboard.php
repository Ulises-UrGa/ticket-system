<?php
session_start();
include("../config/database.php");

/*
VALIDAR SESIÓN
*/

if(!isset($_SESSION['rol'])){

    header("Location: ../login.php");
    exit();

}

if($_SESSION['rol'] != 'cliente'){

    header("Location: ../login.php");
    exit();

}

$usuario_id = $_SESSION['id'];
$nombre = $_SESSION['nombre'];

/*
CONTADORES
*/

$totalTickets = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets
WHERE usuario_id='$usuario_id'"));

$abiertos = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets
WHERE usuario_id='$usuario_id'
AND estado='Abierto'"));

$proceso = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets
WHERE usuario_id='$usuario_id'
AND estado='En Proceso'"));

$resueltos = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets
WHERE usuario_id='$usuario_id'
AND estado='Resuelto'"));

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard Cliente</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="../css/dashboard.css">

<link rel="preconnect"
href="https://fonts.googleapis.com">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h3>Ticket System</h3>

<a href="#">
<i class="fa-solid fa-house"></i>
Dashboard
</a>

<a href="#">
<i class="fa-solid fa-ticket"></i>
Mis Tickets
</a>

<a href="#">
<i class="fa-solid fa-circle-plus"></i>
Crear Ticket
</a>

<a href="#">
<i class="fa-solid fa-user"></i>
Mi Perfil
</a>

<a href="../logout.php">
<i class="fa-solid fa-right-from-bracket"></i>
Cerrar sesión
</a>

</div>

<!-- MAIN -->

<div class="main-content">

<!-- TOPBAR -->

<div class="topbar
d-flex
justify-content-between
align-items-center">

<h4 class="mb-0">

Bienvenido,
<?php echo htmlspecialchars($nombre); ?>

</h4>

</div>

<!-- CARDS -->

<div class="row g-4">

<div class="col-md-3">

<div class="stat-card bg-primary-custom">

<h5>Total Tickets</h5>

<h2>
<?php echo $totalTickets; ?>
</h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-warning-custom">

<h5>Abiertos</h5>

<h2>
<?php echo $abiertos; ?>
</h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-success-custom">

<h5>En proceso</h5>

<h2>
<?php echo $proceso; ?>
</h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-danger-custom">

<h5>Resueltos</h5>

<h2>
<?php echo $resueltos; ?>
</h2>

</div>

</div>

</div>

<!-- FORMULARIO -->

<div class="table-container">

<h4 class="mb-4">

Crear Ticket

</h4>

<form
action="crear_ticket.php"
method="POST"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">
Asunto
</label>

<input
type="text"
name="asunto"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">
Categoría
</label>

<select
name="categoria_id"
class="form-control"
required>

<option value="">
Seleccione
</option>

<?php

$categorias =
mysqli_query($conn,
"SELECT * FROM categorias");

while($categoria =
mysqli_fetch_assoc($categorias)){

?>

<option
value="<?php echo $categoria['id']; ?>">

<?php echo $categoria['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="mb-3">

<label class="form-label">
Descripción
</label>

<textarea
name="descripcion"
class="form-control"
rows="5"
required></textarea>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">
Prioridad
</label>

<select
name="prioridad"
class="form-control"
required>

<option value="">
Seleccione
</option>

<option value="Baja">
Baja
</option>

<option value="Media">
Media
</option>

<option value="Alta">
Alta
</option>

<option value="Urgente">
Urgente
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">
Adjuntar archivo
</label>

<input
type="file"
name="archivo"
class="form-control">

</div>

</div>

<button
type="submit"
class="btn btn-primary">

<i class="fa-solid fa-paper-plane"></i>
Crear Ticket

</button>

</form>

</div>

<!-- TABLA -->

<div class="table-container">

<h4 class="mb-4">

Mis Tickets

</h4>

<table class="table table-hover">

<thead>

<tr>

<th>ID</th>
<th>Asunto</th>
<th>Categoría</th>
<th>Prioridad</th>
<th>Estado</th>
<th>Fecha</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php

$query = "
SELECT tickets.*,
categorias.nombre AS categoria
FROM tickets
INNER JOIN categorias
ON tickets.categoria_id = categorias.id
WHERE usuario_id='$usuario_id'
ORDER BY tickets.id DESC
";

$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td>
#<?php echo $row['id']; ?>
</td>

<td>
<?php echo htmlspecialchars($row['asunto']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['categoria']); ?>
</td>

<td>

<?php

if($row['prioridad'] == 'Urgente'){

echo "<span class='badge bg-dark'>
Urgente
</span>";

}

elseif($row['prioridad'] == 'Alta'){

echo "<span class='badge bg-danger'>
Alta
</span>";

}

elseif($row['prioridad'] == 'Media'){

echo "<span class='badge bg-warning text-dark'>
Media
</span>";

}

else{

echo "<span class='badge bg-success'>
Baja
</span>";

}

?>

</td>

<td>

<?php

if($row['estado'] == 'Abierto'){

echo "<span class='badge bg-primary'>
Abierto
</span>";

}

elseif($row['estado'] == 'En Proceso'){

echo "<span class='badge bg-warning text-dark'>
En Proceso
</span>";

}

elseif($row['estado'] == 'Pendiente'){

echo "<span class='badge bg-info'>
Pendiente
</span>";

}

elseif($row['estado'] == 'Resuelto'){

echo "<span class='badge bg-success'>
Resuelto
</span>";

}

else{

echo "<span class='badge bg-secondary'>
Cerrado
</span>";

}

?>

</td>

<td>
<?php echo $row['fecha_creacion']; ?>
</td>

<td>

<a
href="detalle_ticket.php?id=<?php echo $row['id']; ?>"
class="btn btn-primary btn-sm">

<i class="fa-solid fa-eye"></i>
Ver

</a>

</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>