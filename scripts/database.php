<?php
class Database 
{
    private $PDOLocal;
    private $user = "ego";
    private $password = "ego";
    private $server = "mysql:host=localhost; dbname=egogym2";

    function conectarDB()
    {
        try
        {
            $this->PDOLocal = new PDO($this->server, $this->user, $this->password);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage(); 
        }
    }

    function desconectarBD()
    {
        try
        {
            $this->PDOLocal = null;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage(); 
        }
    }

    function seleccionar($consulta)
    {
        try
        {
            $resultado = $this->PDOLocal->query($consulta);
            $fila = $resultado->fetchAll(PDO::FETCH_OBJ);
            return $fila;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    function ejecutarSQL($cadena)
    {
        try
        {
            $this->PDOLocal->query($cadena);
            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }


    function verifica($email,$contraseña)
    {
        try 
        {
        $pase = false;
        $query = "SELECT * from persona  left join cliente on persona.id_persona = cliente.id_cliente
        where correo ='$email'";
        $consulta=$this->PDOLocal->query($query);
       
       
        while( $renglon=$consulta->fetch(PDO::FETCH_ASSOC))
        {
            if(password_verify($contraseña,$renglon['contraseña']))
            {
                $pase=true;
                
               
                $nombreUsuario = $renglon['nombre'];
                if ($renglon['tipo_usuario'] === 'cliente') {
                    
                    $fechaFinMembresia = $renglon['fecha_fin'];

                  
                    if (strtotime($fechaFinMembresia) < time()) {
                      
                        echo "<div class='alert alert-danger'>";
                        echo "<h2 align='center'>La membresía ha expirado, no se permite el inicio de sesión.</h2>";
                        echo "</div>";
                        header("refresh:2 ../index.php");
                        return;
                    }
                   

                }
                else
                {
                    $consulta1 = "select tipo_empleado from empleado
                    inner join persona on empleado.id_empleado = persona.id_persona
                    where persona.correo = '$email'";
                    $resu = $this->PDOLocal->query($consulta1);
                        $fila = $resu->fetch(PDO::FETCH_ASSOC);
    
                    if($fila['tipo_empleado'] === 'recepcionista')
                    {
                        header("Location: ../views/recepcionista/index.php");
                    }
                    else if ($fila['tipo_empleado'] === 'fisio')
                    {
                        header("Location: ../views/fisioterapeuta/index.php");
                    }
                    else if ($fila['tipo_empleado'] === 'nutri')
                    {
                        header("Location: ../views/nutriologo/index.php ");
                    }
                    else if ($fila['tipo_empleado'] === 'entrenador')
                    {
                        header("Location: ../views/entrenador/index.php");
                    }
                    else
                    {
                        echo "NO SE PUDO ACCEDER";  
                    }
                }
            }
            else
            {
                echo "<div class='alert alert-danger'>";
                echo "<h2 align='center'>Usuario o password incorrecto ...</h2>";
                echo"</div";
               
            
               
                header("refresh:2 ../index.php#membershipForm");
            }
            
            
           
            
        }

        if ($pase) {
            session_start();

            $_SESSION["correo"] = $email;

            header("refresh:0 ../views/clientes/index.php");
        } else {
            echo "<div class='alert alert-danger'>";
            echo "<h2 align='center'>Usuario o contraseña incorrecto ...</h2>";
            echo "</div>";

            header("refresh:2 ../index.php");
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}



function verifica2($email,$contraseña)
{
    try 
    {
    $pase = false;
    $query = "SELECT * from persona  left join cliente on persona.id_persona = cliente.id_cliente
    where correo ='$email'";
    $consulta=$this->PDOLocal->query($query);
   
   
    while( $renglon=$consulta->fetch(PDO::FETCH_ASSOC))
    {
        if(password_verify($contraseña,$renglon['contraseña']))
        {
            $pase=true;
            
           
            $nombreUsuario = $renglon['nombre'];
            if ($renglon['tipo_usuario'] === 'cliente') {
                
                $fechaFinMembresia = $renglon['fecha_fin'];

              
                if (strtotime($fechaFinMembresia) < time()) {
                  
                    echo "<div class='alert alert-danger'>";
                    echo "<h2 align='center'>La membresía ha expirado, no se permite el inicio de sesión.</h2>";
                    echo "</div>";
                    header("refresh:2 ../index.php");
                    return;
                }
               

            }
            else
            {
                $consulta1 = "select tipo_empleado from empleado
                inner join persona on empleado.id_empleado = persona.id_persona
                where persona.correo = '$email'";
                $resu = $this->PDOLocal->query($consulta1);
                    $fila = $resu->fetch(PDO::FETCH_ASSOC);

                if($fila['tipo_empleado'] === 'recepcionista')
                {
                    header("Location: ../views/recepcionista/index.php");
                }
                else if ($fila['tipo_empleado'] === 'fisio')
                {
                    header("Location: ../views/fisioterapeuta/index.php");
                }
                else if ($fila['tipo_empleado'] === 'nutri')
                {
                    header("Location: ../views/nutriologo/index.php ");
                }
                else if ($fila['tipo_empleado'] === 'entrenador')
                {
                    header("Location: ../views/entrenador/index.php");
                }
                else
                {
                    echo "NO SE PUDO ACCEDER";  
                }
            }
        }
        else
        {
            echo "<div class='alert alert-danger'>";
            echo "<h2 align='center'>Usuario o password incorrecto ...</h2>";
            echo"</div";
           
        
           
            header("refresh:2 ../index.php#membershipForm");
        }
        
        
       
        
    }

    if ($pase) {
        session_start();

        $_SESSION["correo"] = $email;

        header("refresh:0 ../views/clientes/citas.php");
    } else {
        echo "<div class='alert alert-danger'>";
        echo "<h2 align='center'>Usuario o contraseña incorrecto ...</h2>";
        echo "</div>";

        header("refresh:2 ../index.php");
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
}

    function cerrarSesion()
    {
        session_start();
        session_destroy();
        header("Location: ../index.php");
    }


    function grafica($consulta, $parametros = array()) {
        $stmt = $this->PDOLocal->prepare($consulta);
        $stmt->execute($parametros);
        return $stmt;
    }
    
    
            function obtenerNombreMes($numeroMes) {
                $meses = array(
                    1 => 'Enero',
                    2 => 'Febrero',
                    3 => 'Marzo',
                    4 => 'Abril',
                    5 => 'Mayo',
                    6 => 'Junio',
                    7 => 'Julio',
                    8 => 'Agosto',
                    9 => 'Septiembre',
                    10 => 'Octubre',
                    11 => 'Noviembre',
                    12 => 'Diciembre'
                );
                return $meses[$numeroMes];
            }


}
?>
