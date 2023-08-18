<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <title>Registrar</title>
      <!-- SCRIPTS -->
      <script src="../js/jquery.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="../js/aos.js"></script>
      <script src="../js/smoothscroll.js"></script>
      <script src="../js/custom.js"></script>

     <link rel="stylesheet" href="../css/bootstrap.min.css">
     <link rel="stylesheet" href="../css/font-awesome.min.css">
     <link rel="stylesheet" href="../css/aos.css">

     <!-- MAIN CSS -->
     <link rel="stylesheet" href="../css/egogym.css">
    </head>
    <body>
    <div class="container">
        <?php
         include 'database.php';
         $db= new database();
         $db->conectarDB();
 
         extract($_POST);
         $contra = "1234";
         $hash = password_hash($contra, PASSWORD_DEFAULT);

         $cadena = "INSERT INTO persona(nombre, apellido_paterno, apellido_materno, correo, sexo,contraseña, telefono, fecha_nacimiento, tipo_usuario,foto)
         values('$nombre','$apellido_paterno','$apellido_materno','$correo','$sexo', '$hash', null, '$fecha_nacimiento','$tipo_usuario',default)";
 
         $db->ejecutarSQL($cadena);
         $db->desconectarBD();
         echo "<div class='alert alert-success'>
         Usuario registrado exitosamente</div>";
         header("refresh:3; ../views/recepcionista/index.php");
 
        ?>
    </div>
    </body>
</html>