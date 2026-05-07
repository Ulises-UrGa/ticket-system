<?php
session_start();
include("../config/database.php");

if($_SESSION['rol'] != 'admin'){
    header("Location: ../login.php");
}

$totalTickets = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tickets"));

$abiertos = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='Abierto'"));

$proceso = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='En proceso'"));

$resueltos = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='Resuelto'"));

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

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

<a href="#">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="#">
<i class="fa-solid fa-ticket"></i> Tickets
</a>

<a href="#">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="#">
<i class="fa-solid fa-chart-column"></i> Reportes
</a>

<a href="../logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
</a>

</div>

<!-- MAIN -->

<div class="main-content">

<!-- TOPBAR -->

<div class="topbar d-flex justify-content-between align-items-center">

<h4 class="mb-0">
Bienvenido, <?php echo $_SESSION['nombre']; ?>
</h4>

</div>

<!-- CARDS -->

<div class="row g-4">

<div class="col-md-3">

<div class="stat-card bg-primary-custom">

<h5>Total Tickets</h5>

<h2><?php echo $totalTickets; ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-warning-custom">

<h5>Abiertos</h5>

<h2><?php echo $abiertos; ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-success-custom">

<h5>En proceso</h5>

<h2><?php echo $proceso; ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card bg-danger-custom">

<h5>Resueltos</h5>

<h2><?php echo $resueltos; ?></h2>

</div>

</div>

</div>

<!-- TABLA -->

<div class="table-container">

<h4 class="mb-4">Tickets recientes</h4>

<table class="table table-hover">

<thead>

<tr>
<th>ID</th>
<th>Asunto</th>
<th>Prioridad</th>
<th>Estado</th>
<th>Fecha</th>
</tr>

</thead>

<tbody>

<?php

$query = "SELECT * FROM tickets ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $row['asunto']; ?></td>

<td>

<?php

if($row['prioridad'] == 'Alta'){
    echo "<span class='badge bg-danger'>Alta</span>";
}
elseif($row['prioridad'] == 'Media'){
    echo "<span class='badge bg-warning text-dark'>Media</span>";
}
else{
    echo "<span class='badge bg-success'>Baja</span>";
}

?>

</td>

<td>

<?php

if($row['estado'] == 'Abierto'){
    echo "<span class='badge bg-primary'>Abierto</span>";
}
elseif($row['estado'] == 'En proceso'){
    echo "<span class='badge bg-warning text-dark'>En proceso</span>";
}
else{
    echo "<span class='badge bg-success'>Resuelto</span>";
}

?>

</td>

<td><?php echo $row['fecha']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>