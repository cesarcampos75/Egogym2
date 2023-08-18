<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <title>Inicio</title>
      <!-- SCRIPTS -->
      <script src="../../js/jquery.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/aos.js"></script>
      <script src="../../js/smoothscroll.js"></script>
      <script src="../../js/custom.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


     <link rel="stylesheet" href="../../css/bootstrap.min.css">
     <link rel="stylesheet" href="../../css/font-awesome.min.css">
     <link rel="stylesheet" href="../../css/aos.css">

     <!-- MAIN CSS -->
     <link rel="stylesheet" href="../../css/egogym.css">

     <script>
$(document).ready(function() {

  $('.dropdown-menu a.dropdown-item').click(function(event) {
 
    event.preventDefault();


    var href = $(this).attr('href');

    
    window.location.href = href;
  });
});
</script>
    </head>
    <body data-spy="scroll" data-target="#navbarNav" data-offset="50">
    <?php
    include '../../scripts/database.php';
    $conexion = new Database();
    $conexion->conectarDB();

    session_start();
    $email = $_SESSION["correo"];
    $consulta = "SELECT tipo_empleado from persona inner join empleado on persona.id_persona = empleado.id_empleado
        where correo ='$email'";
    $datos = $conexion -> seleccionar($consulta);

        foreach ($datos as $dato)
        {
          $tipo = $dato->tipo_empleado;
        }

    if(isset($email) and $tipo == 'recepcionista' )
    {
      
    }
    else 
    {
        header("Location:../../index.php");
    }
       
    ?>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">

            <a class="navbar-brand" href="../recepcionista/index.php">EGO GYM</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-lg-auto">
                    <li class="nav-item">
                        <a href="../recepcionista/index.php" class="nav-link smoothScroll">Inicio</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false" > Citas</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item" href="citas.php">Agendar Cita</a></li>
                          <li><a class="dropdown-item" href="citas_prox.php">Ver Citas</a></li>
                        </ul>
                      </li>

                    <li class="nav-item">
                        <a href="usuarios.php" class="nav-link smoothScroll">Usuarios</a>
                    </li>

                    <li class="nav-item">
                        <a href="registrarusu.php" class="nav-link smoothScroll">Registrar Nuevo Usuario</a>
                    </li>
                    
                </ul>

                <ul class="navbar-nav ml-lg-2">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false" >
                         Hola Recepcionista
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <li><a class="dropdown-item" href="../../scripts/cerrarsesion.php">Cerrar Sesion</a></li>
                        </ul>
                      </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="kiara">
    <!--Inicio de recepcionista-->
    <div class="container" style="padding-top: 1%;">
        <h1 data-aos="fade-up" style="text-align: center;">¡Bienvenido!</h1>
        <hr class="dropdown divider" style="height: 2px;">

        <!--Tablas de citas registradas para el día actual-->
    </div>
    <div class="container">
        <?php
        $conexion = new database();
        $conexion->conectarDB();

        $consulta = "SELECT count(citas.id_cita) as cantidad, concat(persona.nombre,' ',persona.apellido_paterno,' ',persona.apellido_materno) AS cliente, 
        e.servicio as servicio,e.empleado AS empleado, citas.hora as hora
        from citas INNER JOIN cliente ON cliente.id_cliente= citas.cliente
        INNER JOIN persona ON persona.id_persona = cliente.id_cliente
        INNER JOIN
        (
        SELECT id_empserv, concat(persona.nombre,' ',persona.apellido_paterno,' ',persona.apellido_materno) AS empleado,
        servicios.nombre as servicio 
        FROM servicios_empleados 
        INNER JOIN servicios ON servicios.codigo=servicios_empleados.servicio
        INNER JOIN empleado ON servicios_empleados.empleado=empleado.id_empleado
        INNER JOIN persona ON empleado.id_empleado = persona.id_persona
        ) AS e ON citas.serv_emp = e.id_empserv 
         where citas.fecha = curdate()
         group by citas.id_cita;
        ";
         $tabla = $conexion->seleccionar($consulta);
         foreach($tabla as $registro)
         {
             $cant = $registro->cantidad;

             $cant = $registro;
         }
         if(isset($cant) != '0')
         {
            echo"<h3 data-aos='fade-right'>Citas del día de hoy</h3>";
            echo 
         "
         <table class='table' style='border-radius: 5px;'>
         <thead class='table-dark'>
             <tr>
             <br>
                 <th style='color: goldenrod;'>
                 Cliente
                 </th>
                 <th style='color: goldenrod;'>
                 Hora
                 </th>
                 <th style='color: goldenrod;'>
                 Servicio
                 </th>
                 <th style='color: goldenrod;'>
                 Empleado
                 </th>
                 <th>
                 </th>
             </tr>
         </thead>
         <tbody>";
         foreach ($tabla as $registro)
         {
             echo "<tr>";
             echo "<td> $registro->cliente</td> ";
             echo "<td> $registro->hora</td> ";
             echo "<td> $registro->servicio</td> ";
             echo "<td> $registro->empleado</td> ";
         }
         echo "</tbody>
         </table>";
         $conexion->desconectarBD();
         }
         else
         {
            echo "<h2 data-aos='fade-right' style='color: goldenrod'>¡No hay citas pendientes el día de hoy!</h2>";
         }

         
        ?>
    </div>
    </section>

    </body>
</html>