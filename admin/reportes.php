<?php
session_start();
include("../config/database.php");

if($_SESSION['rol'] != 'admin'){
    header("Location: ../login.php");
}

/* ESTADISTICAS */

$totalTickets = mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM tickets"));

$abiertos = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='Abierto'"));

$proceso = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='En proceso'"));

$resueltos = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE estado='Resuelto'"));

/* PRIORIDADES */

$baja = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE prioridad='Baja'"));

$media = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE prioridad='Media'"));

$alta = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM tickets WHERE prioridad='Alta'"));

/* CATEGORIAS */

$categorias = mysqli_query($conn,

"SELECT categorias.nombre,
COUNT(tickets.id) as total

FROM tickets

INNER JOIN categorias
ON tickets.categoria_id = categorias.id

GROUP BY categorias.nombre");

$categoriaNombres = [];
$categoriaTotales = [];

while($cat = mysqli_fetch_assoc($categorias)){

    $categoriaNombres[] = $cat['nombre'];
    $categoriaTotales[] = $cat['total'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reportes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

.chart-card{
    background: white;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.08);
    margin-top: 30px;
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

<div class="topbar">

<h4>Reportes y Estadísticas</h4>

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

<!-- GRAFICAS -->

<div class="row">

<!-- ESTADOS -->

<div class="col-md-6">

<div class="chart-card">

<h5 class="mb-4">Tickets por Estado</h5>

<canvas id="estadoChart"></canvas>

</div>

</div>

<!-- PRIORIDAD -->

<div class="col-md-6">

<div class="chart-card">

<h5 class="mb-4">Tickets por Prioridad</h5>

<canvas id="prioridadChart"></canvas>

</div>

</div>

</div>

<!-- CATEGORIAS -->

<div class="chart-card">

<h5 class="mb-4">Tickets por Categoría</h5>

<canvas id="categoriaChart"></canvas>

</div>

</div>

<script>

/* ESTADOS */

new Chart(document.getElementById('estadoChart'),{

    type:'doughnut',

    data:{
        labels:[
            'Abiertos',
            'En proceso',
            'Resueltos'
        ],

        datasets:[{
            data:[
                <?php echo $abiertos; ?>,
                <?php echo $proceso; ?>,
                <?php echo $resueltos; ?>
            ]
        }]
    }
});

/* PRIORIDAD */

new Chart(document.getElementById('prioridadChart'),{

    type:'bar',

    data:{
        labels:[
            'Baja',
            'Media',
            'Alta'
        ],

        datasets:[{
            label:'Tickets',
            data:[
                <?php echo $baja; ?>,
                <?php echo $media; ?>,
                <?php echo $alta; ?>
            ]
        }]
    }
});

/* CATEGORIAS */

new Chart(document.getElementById('categoriaChart'),{

    type:'line',

    data:{
        labels:
        <?php echo json_encode($categoriaNombres); ?>,

        datasets:[{
            label:'Tickets',

            data:
            <?php echo json_encode($categoriaTotales); ?>,

            tension:0.4
        }]
    }
});

</script>

</body>
</html>