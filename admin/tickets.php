<?php
session_start();
include("../config/database.php");

if($_SESSION['rol'] != 'admin'){
    header("Location: ../login.php");
}

/* FILTROS */

$where = [];

if(isset($_GET['buscar']) && $_GET['buscar'] != ''){

    $buscar = $_GET['buscar'];

    $where[] = "(tickets.asunto LIKE '%$buscar%'
    OR usuarios.nombre LIKE '%$buscar%')";
}

if(isset($_GET['estado']) && $_GET['estado'] != ''){

    $estado = $_GET['estado'];

    $where[] = "tickets.estado='$estado'";
}

if(isset($_GET['prioridad']) && $_GET['prioridad'] != ''){

    $prioridad = $_GET['prioridad'];

    $where[] = "tickets.prioridad='$prioridad'";
}

if(isset($_GET['categoria']) && $_GET['categoria'] != ''){

    $categoria = $_GET['categoria'];

    $where[] = "categorias.id='$categoria'";
}

/* QUERY */

$query = "

SELECT
tickets.*,
usuarios.nombre AS cliente,
categorias.nombre AS categoria

FROM tickets

INNER JOIN usuarios
ON tickets.usuario_id = usuarios.id

INNER JOIN categorias
ON tickets.categoria_id = categorias.id

";

if(count($where) > 0){

    $query .= " WHERE ".implode(" AND ",$where);
}

$query .= " ORDER BY tickets.id DESC";

$result = mysqli_query($conn,$query);

/* CATEGORIAS */

$categorias = mysqli_query($conn,
"SELECT * FROM categorias");

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tickets</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.filter-card{
    background: white;
    padding: 25px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.08);
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

<a href="usuarios.php">
<i class="fa-solid fa-users"></i> Usuarios
</a>

<a href="reportes.php">
<i class="fa-solid fa-chart-column"></i> Reportes
</a>

<a href="../logout.php">
<i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
</a>

</div>

<!-- MAIN -->

<div class="main-content">

<!-- TOPBAR -->

<div class="topbar">

<h4>Gestión de Tickets</h4>

</div>

<!-- FILTROS -->

<div class="filter-card">

<form method="GET">

<div class="row g-3">

<div class="col-md-3">

<input
type="text"
name="buscar"
class="form-control"
placeholder="Buscar ticket o cliente"
value="<?php echo $_GET['buscar'] ?? ''; ?>">

</div>

<div class="col-md-2">

<select
name="estado"
class="form-select">

<option value="">Estado</option>

<option value="Abierto">
Abierto
</option>

<option value="En proceso">
En proceso
</option>

<option value="Resuelto">
Resuelto
</option>

</select>

</div>

<div class="col-md-2">

<select
name="prioridad"
class="form-select">

<option value="">Prioridad</option>

<option value="Baja">
Baja
</option>

<option value="Media">
Media
</option>

<option value="Alta">
Alta
</option>

</select>

</div>

<div class="col-md-3">

<select
name="categoria"
class="form-select">

<option value="">Categoría</option>

<?php while($cat = mysqli_fetch_assoc($categorias)){ ?>

<option value="<?php echo $cat['id']; ?>">

<?php echo $cat['nombre']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-2">

<button
class="btn btn-primary w-100">

<i class="fa-solid fa-filter"></i>
Filtrar

</button>

</div>

</div>

</form>

</div>

<!-- TABLA -->

<div class="table-container">

<table class="table table-hover align-middle">

<thead>

<tr>
<th>ID</th>
<th>Cliente</th>
<th>Asunto</th>
<th>Categoría</th>
<th>Prioridad</th>
<th>Estado</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $row['cliente']; ?></td>

<td><?php echo $row['asunto']; ?></td>

<td><?php echo $row['categoria']; ?></td>

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

<td>

<a
href="ver-ticket.php?id=<?php echo $row['id']; ?>"
class="btn btn-primary btn-sm">

<i class="fa-solid fa-eye"></i>

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