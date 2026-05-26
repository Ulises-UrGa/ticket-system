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

/*
VALIDAR ID
*/

if(!isset($_GET['id'])){

    header("Location: dashboard.php");
    exit();

}

$ticket_id = $_GET['id'];
$usuario_id = $_SESSION['id'];

/*
CONSULTAR TICKET
*/

$query = "
SELECT tickets.*,
categorias.nombre AS categoria
FROM tickets
INNER JOIN categorias
ON tickets.categoria_id = categorias.id
WHERE tickets.id='$ticket_id'
AND tickets.usuario_id='$usuario_id'
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){

    echo "Ticket no encontrado";
    exit();

}

$ticket = mysqli_fetch_assoc($result);

/*
CONSULTAR RESPUESTAS
*/

$queryRespuestas = "
SELECT respuestas.*,
usuarios.nombre
FROM respuestas
INNER JOIN usuarios
ON respuestas.usuario_id = usuarios.id
WHERE ticket_id='$ticket_id'
ORDER BY respuestas.id ASC
";

$respuestas = mysqli_query($conn,$queryRespuestas);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Detalle Ticket</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h3>Ticket System</h3>

<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
Dashboard
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

Detalle del Ticket #<?php echo $ticket['id']; ?>

</h4>

<a href="dashboard.php"
class="btn btn-primary">

<i class="fa-solid fa-arrow-left"></i>
Volver

</a>

</div>

<!-- INFORMACIÓN -->

<div class="table-container">

<h4 class="mb-4">

<?php echo htmlspecialchars($ticket['asunto']); ?>

</h4>

<div class="row">

<div class="col-md-6 mb-3">

<strong>Categoría:</strong>

<br>

<?php echo htmlspecialchars($ticket['categoria']); ?>

</div>

<div class="col-md-6 mb-3">

<strong>Prioridad:</strong>

<br>

<?php echo htmlspecialchars($ticket['prioridad']); ?>

</div>

<div class="col-md-6 mb-3">

<strong>Estado:</strong>

<br>

<?php echo htmlspecialchars($ticket['estado']); ?>

</div>

<div class="col-md-6 mb-3">

<strong>Fecha:</strong>

<br>

<?php echo $ticket['fecha_creacion']; ?>

</div>

</div>

<hr>

<h5>Descripción</h5>

<p>

<?php echo nl2br(htmlspecialchars($ticket['descripcion'])); ?>

</p>

<!-- ARCHIVO -->

<?php if(!empty($ticket['archivo'])){ ?>

<hr>

<h5>Archivo adjunto</h5>

<a
href="../<?php echo $ticket['archivo']; ?>"
target="_blank"
class="btn btn-dark">

<i class="fa-solid fa-paperclip"></i>
Ver archivo

</a>

<?php } ?>

</div>

<!-- RESPUESTAS -->

<div class="table-container">

<h4 class="mb-4">

Respuestas

</h4>

<?php

if(mysqli_num_rows($respuestas) > 0){

while($respuesta =
mysqli_fetch_assoc($respuestas)){

?>

<div class="card mb-3 shadow-sm">

<div class="card-body">

<h6>

<?php echo htmlspecialchars($respuesta['nombre']); ?>

</h6>

<p>

<?php

echo nl2br(
htmlspecialchars(
$respuesta['mensaje']
)
);

?>

</p>

<small class="text-muted">

<?php echo $respuesta['fecha_respuesta']; ?>

</small>

</div>

</div>

<?php

}

}else{

?>

<div class="alert alert-info">

Aún no hay respuestas.

</div>

<?php } ?>

</div>

</div>

</body>
</html>