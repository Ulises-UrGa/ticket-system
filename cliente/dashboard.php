<?php
session_start();

if($_SESSION['rol'] != 'cliente'){
    header("Location: ../login.php");
}
?>

<h1>Bienvenido Cliente</h1>
<a href="../logout.php">Cerrar sesión</a>
