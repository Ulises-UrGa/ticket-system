<?php
session_start();
include("../config/database.php");

if($_SESSION['rol'] != 'admin'){
    header("Location: ../login.php");
}

$id = $_GET['id'];

$query = "SELECT
tickets.*,
usuarios.nombre AS cliente,
usuarios.correo,
categorias.nombre AS categoria

FROM tickets

INNER JOIN usuarios
ON tickets.usuario_id = usuarios.id

INNER JOIN categorias
ON tickets.categoria_id = categorias.id

WHERE tickets.id='$id'";

$result = mysqli_query($conn,$query);

$ticket = mysqli_fetch_assoc($result);

if(isset($_POST['responder'])){

    $mensaje = $_POST['mensaje'];

    $insert = "INSERT INTO respuestas(
        ticket_id,
        usuario_id,
        mensaje
    ) VALUES(
        '$id',
        '".$_SESSION['id']."',
        '$mensaje'
    )";

    mysqli_query($conn,$insert);

    header("Location: ver-ticket.php?id=".$id);
}

if(isset($_POST['estado'])){

    $estado = $_POST['nuevo_estado'];

    mysqli_query($conn,
    "UPDATE tickets
    SET estado='$estado'
    WHERE id='$id'");

    header("Location: ver-ticket.php?id=".$id);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detalle Ticket</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.ticket-card{
    background: white;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.chat-box{
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    max-height: 500px;
    overflow-y: auto;
}

.message{
    background: white;
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 15px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.05);
}

</style>

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

<a href="#">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="../logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
</a>

</div>

<!-- MAIN -->

<div class="main-content">

<div class="topbar">

<h4>
Ticket #<?php echo $ticket['id']; ?>
</h4>

</div>

<!-- INFO -->

<div class="ticket-card">

<div class="row">

<div class="col-md-6">

<h5>Información Ticket</h5>

<p>
<strong>Cliente:</strong>
<?php echo $ticket['cliente']; ?>
</p>

<p>
<strong>Correo:</strong>
<?php echo $ticket['correo']; ?>
</p>

<p>
<strong>Categoría:</strong>
<?php echo $ticket['categoria']; ?>
</p>

<p>
<strong>Prioridad:</strong>

<?php

if($ticket['prioridad'] == 'Alta'){
    echo "<span class='badge bg-danger'>Alta</span>";
}
elseif($ticket['prioridad'] == 'Media'){
    echo "<span class='badge bg-warning text-dark'>Media</span>";
}
else{
    echo "<span class='badge bg-success'>Baja</span>";
}

?>

</p>

<p>
<strong>Estado:</strong>

<?php

if($ticket['estado'] == 'Abierto'){
    echo "<span class='badge bg-primary'>Abierto</span>";
}
elseif($ticket['estado'] == 'En proceso'){
    echo "<span class='badge bg-warning text-dark'>En proceso</span>";
}
else{
    echo "<span class='badge bg-success'>Resuelto</span>";
}

?>

</p>

</div>

<div class="col-md-6">

<h5>Cambiar Estado</h5>

<form method="POST">

<select name="nuevo_estado" class="form-select mb-3">

<option>Abierto</option>
<option>En proceso</option>
<option>Resuelto</option>

</select>

<button type="submit"
name="estado"
class="btn btn-primary">

Actualizar Estado

</button>

</form>

</div>

</div>

<hr>

<h5>Descripción</h5>

<p>
<?php echo $ticket['descripcion']; ?>
</p>

</div>

<!-- RESPUESTAS -->

<div class="ticket-card">

<h5 class="mb-4">Conversación</h5>

<div class="chat-box">

<?php

$respuestas = mysqli_query($conn,

"SELECT respuestas.*,
usuarios.nombre

FROM respuestas

INNER JOIN usuarios
ON respuestas.usuario_id = usuarios.id

WHERE ticket_id='$id'

ORDER BY respuestas.id ASC");

while($respuesta = mysqli_fetch_assoc($respuestas)){

?>

<div class="message">

<div class="d-flex justify-content-between">

<strong>
<?php echo $respuesta['nombre']; ?>
</strong>

<small>
<?php echo $respuesta['fecha']; ?>
</small>

</div>

<p class="mt-2 mb-0">
<?php echo $respuesta['mensaje']; ?>
</p>

</div>

<?php } ?>

</div>

</div>

<!-- RESPONDER -->

<div class="ticket-card">

<h5>Responder Ticket</h5>

<form method="POST">

<textarea
name="mensaje"
class="form-control mb-3"
rows="5"
required></textarea>

<button
type="submit"
name="responder"
class="btn btn-primary">

Enviar Respuesta

</button>

</form>

</div>

</div>

</body>
</html>