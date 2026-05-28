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
RECIBIR DATOS
*/

$usuario_id = $_SESSION['id'];

$asunto = $_POST['asunto'];
$descripcion = $_POST['descripcion'];
$categoria_id = $_POST['categoria_id'];
$prioridad = $_POST['prioridad'];

/*
SUBIR ARCHIVO
*/

$archivoRuta = NULL;

if(isset($_FILES['archivo']) &&
$_FILES['archivo']['error'] == 0){

    $archivoNombre =
    time() . "_" .
    $_FILES['archivo']['name'];

    $rutaDestino =
    "../uploads/" .
    $archivoNombre;

    /*
    MOVER ARCHIVO
    */

    if(move_uploaded_file(
        $_FILES['archivo']['tmp_name'],
        $rutaDestino
    )){

        $archivoRuta =
        "uploads/" . $archivoNombre;

    }

}

/*
INSERTAR TICKET
*/

$query = "
INSERT INTO tickets
(
usuario_id,
categoria_id,
asunto,
descripcion,
prioridad,
archivo
)

VALUES
(
'$usuario_id',
'$categoria_id',
'$asunto',
'$descripcion',
'$prioridad',
'$archivoRuta'
)
";

$result = mysqli_query($conn,$query);

/*
REDIRECCIONAR
*/

if($result){

    header("Location: dashboard.php");

}else{

    echo "Error al crear ticket";

}
?>