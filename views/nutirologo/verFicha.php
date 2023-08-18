<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <title>Ficha médica</title>
      <!-- SCRIPTS -->
      <script src="../../js/jquery.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/aos.js"></script>
      <script src="../../js/smoothscroll.js"></script>
      <script src="../../js/custom.js"></script>

     <link rel="stylesheet" href="../../css/bootstrap.min.css">
     <link rel="stylesheet" href="../../css/font-awesome.min.css">
     <link rel="stylesheet" href="../../css/aos.css">

     <!-- MAIN CSS -->
     <link rel="stylesheet" href="../../css/egogym.css">
    </head>
    <body>
    <?php
    include '../../scripts/database.php';
    $conexion = new Database();
    $conexion->conectarDB();

    session_start();
    $email = $_SESSION["correo"];
    $consulta = "SELECT tipo_empleado, nombre from persona inner join empleado on persona.id_persona = empleado.id_empleado
        where correo ='$email'";
    $datos = $conexion -> seleccionar($consulta);

        foreach ($datos as $dato)
        {
          $tipo = $dato->tipo_empleado;
          $name = $dato->nombre;
        }

    if(isset($email) and $tipo == 'nutri' )
    {
      
    }
    else 
    {
        header("Location:../../First.php");
    }
       
    ?>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">

            <a class="navbar-brand" href="../nutriologo/index.php">EGO GYM</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-lg-auto">
                <li class="nav-item">
                        <a href="../nutriologo/index.php" class="nav-link smoothScroll">Inicio</a>
                    </li>
                    <li class="nav-item">
                    <a href="citas_hoy.php" class="nav-link smoothScroll">Citas</a>
                    </li>
                </ul>

                
                <ul class="navbar-nav ml-lg-2">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false" >
                          <?php echo "Hola".'  '."$name"; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../nutriologo/perfil_nutri.php">Perfil</a></li>
                          <li><a class="dropdown-item" href="../../scripts/cerrarsesion.php">Cerrar Sesion</a></li>
                        </ul>
                      </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="kiara">

    <div class="container" style="padding-top: 3%;">

    
    <?php
      $conexion = new Database();
      $conexion->conectarDB();

      $idFicha = $_GET['id'];
      $consulta="SELECT concat(nombre,' ',apellido_paterno,' ',apellido_materno) as nombre, citas.fecha, 
      FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) AS edad FROM persona INNER JOIN cliente on cliente.id_cliente=persona.id_persona
      INNER JOIN citas on citas.cliente=cliente.id_cliente INNER JOIN ficha_nutri 
      ON ficha_nutri.cita=citas.id_cita WHERE ficha_nutri.id_ficha=$idFicha";
      $parametros = array(':id'=> $idFicha);
      $persona =$conexion->seleccionar($consulta, $parametros);

      if($persona)
      {

        echo "<div class='container' style='padding-top:5%'>
        <div class='container text-center'><h3>Ficha médica</<h3></div>
        <div class='card-header' style='color:grey; float: right'><h5> Fecha: ".$persona[0]->fecha."</h5></div>
         <div class='card-header'><h5>Cliente: ".$persona[0]->nombre."</h5></div>";
        
        echo"<div class='card'>";
      
        $consulta = "SELECT ficha_nutri.objetivo, ficha_nutri.motivo, ficha_nutri.peso,
        ficha_nutri.altura, ficha_nutri.med_cintura, ficha_nutri.med_cadera, ficha_nutri.med_cuello,
        ficha_nutri.porc_grasa_corporal, ficha_nutri.masa_corp_magra, ficha_nutri.observaciones
        from ficha_nutri 
        where ficha_nutri.id_ficha= $idFicha";
        $ficha = $conexion->seleccionar($consulta);

        foreach($ficha as $fila)
        {
        echo "<div class='row'>";

        echo "<div class='col-lg-6 col-6'>";
        echo "<div class='modal-body' style='padding: 3%'>";
        echo "<h6 style='font-weight:bold;color:black; opacity:0.7;'>Datos del paciente</h6><br>"; 
        echo "<p>Edad: ".$persona[0]->edad." años</p>";  
        echo "<p>Altura: ".$fila->altura." m</p>";
        echo "<p>Peso: ".$fila->peso." kg</p>";
        echo "<p>Cintura: ".$fila->med_cintura." cm</p>";
        echo "<p>Cadera: ".$fila->med_cadera." cm</p>";
        echo "<p>Cuello: ".$fila->med_cuello." cm</p>";
        echo "</div>";
        echo "</div>";

        echo "<div class='col-lg-6 col-6'>";
        echo "<div class='modal-body' style='padding: 3%'>";
        echo "<h6 style='font-weight:bold;color:black; opacity:0.7;'>Detalles: </h6><br>"; 
        echo "<p>Grasa Corporal: $fila->porc_grasa_corporal</p>";
        echo "<p>Masa Corporal Magra: $fila->masa_corp_magra</p>";
        echo "<p>Objetivo: ". $fila->objetivo." kg</p>";
        echo "<p>Motivo: $fila->motivo</p>";
        echo "<p>Observaciones: $fila->observaciones</p>";
        echo "</div>";
        echo "</div>";

        echo "</div>";
        }
        echo "</div>
        </div>";

        echo "</div>
        </div>";

      }
      else
      {
        echo "¡Esta cita no tiene una ficha médica!";
      }
      
      $conexion->desconectarBD();
      ?>
        
    
   
    </div>
    </section>
    </body>
</html>