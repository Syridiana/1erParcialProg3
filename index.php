<?php


require_once './Usuario.php';
require_once './Auto.php';
require_once './Precio.php';
require_once './Token.php';
require_once './PassManager.php';

use \Firebase\JWT\JWT;

require __DIR__ . '/vendor/autoload.php';



$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;

$explode_path = explode('/', $path);


switch ($explode_path[1]) {

        #region /registro
    case 'registro':
        if ($method == 'POST') {

            if (isset($_POST['email']) && isset($_POST['tipo']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $tipo = $_POST['tipo'];
                $password = $_POST['password'];

                echo "$email $tipo $password";

                $usuario = new Usuario($email, $tipo, PassManager::Create($password));
                if (!(Usuario::existeUsuario($usuario, Usuario::leerJson(Usuario::$_pathJson)))) {

                    if (Usuario::guardarJson($usuario, Usuario::$_pathJson)) {
                        echo "Usuario guardado con exito.";
                    }
                } else {
                    echo "Error. Ya existe ese usuario.";
                }
            } else {
                echo "Debe cargar todos los datos para continuar.";
            }


        } else {
            echo "Metodo no permitido";
        }

        break;

    case 'login':
        if ($method == 'POST') {
            $logueado = false;

            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $listaUsuariosJson = Usuario::leerJson(Usuario::$_pathJson);

                foreach ($listaUsuariosJson as $user) {
                    if ($user->_email == $email) {

                        if ((strcmp($user->_pass, PassManager::Create($password))) == 0) {
                            $logueado = true;
                            break;
                        }
                    }
                }

                if ($logueado) {
                    echo "Usuario logueado con exito.</br></br>";
                    $token = Token::getToken($user->_email, $user->_tipo);
                    print_r($token);
                } else {
                    echo "Datos incorrectos, no se pudo loguear";
                }

            } else {
                echo "Debe cargar todos los datos para continuar.";
            }
        }
        break;

    case 'precio':
        if ($method == 'POST') {


            if (isset($_POST['precio_hora']) && isset($_POST['precio_estadia']) && isset($_POST['precio_mensual'])) {

                $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

                if ($validacion) {
                    $hora = $_POST['precio_hora'];
                    $estadia = $_POST['precio_estadia'];
                    $mensual = $_POST['precio_mensual'];



                   if(Token::tipoUsuarioSegunToken($_SERVER['HTTP_TOKEN']) == 'admin')
                   {

                    $nuevoPrecio = new Precio($hora, $estadia, $mensual);

                    Precio::sobreEscribirJson($nuevoPrecio);
                   

                   } else
                   {
                       echo "Su tipo de usuario no le permite hacer modificaciones en los precios.";
                   }


                } else {
                    echo "Error. Debe estar logueado para acceder a esta pagina";
                }
            } else {
                echo "Debe cargar todos los datos para continuar.";
            }
        } else if ($method == 'GET') {
            $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

            if ($validacion) {
                $listadoMaterias = array();
                $listadoMaterias = Materia::leerJson();

                echo "Listado de materias:</br>";

                if($listadoMaterias != null && count($listadoMaterias) > 0)
                {
                    foreach($listadoMaterias as $unaMateria)
                    {
                        echo "$unaMateria->_nombre, cuatrimestre: $unaMateria->_cuatrimestre, id: $unaMateria->_id.</br>";
                    }
                } else
                {
                    echo "No hay materias cargadas.";
                }

            } else {
                echo "Error. Debe estar logueado para acceder a esta pagina";
            }


        } else {
            echo "Metodo no permitido";
        }
        break;

    case 'ingreso':
        if ($method == 'POST') {


            if (isset($_POST['patente']) && isset($_POST['tipo'])) {

                $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

                if ($validacion) {

                    $patente = $_POST['patente'];
                    $tipo = $_POST['tipo'];
                    $fecha_ingreso = date("d-m-Y H");


                    if(Token::tipoUsuarioSegunToken($_SERVER['HTTP_TOKEN']) == 'user')
                    {
                        
                    $emailUsuario = Token::emailSegunToken($_SERVER['HTTP_TOKEN']);

                    $nuevoAuto = new Auto($patente, $tipo, $fecha_ingreso, $emailUsuario);

                    Auto::guardarJson($nuevoAuto);
                    
                    } else
                    {
                        echo "Su tipo de usuario no le permite ingresar un auto.";
                    }


                } else {
                    echo "Error. Debe estar logueado para acceder a esta pagina";
                }
            } else {
                echo "Debe cargar todos los datos para continuar.";
            }

            #endregion





        } else if ($method == 'GET') {
            $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

            if ($validacion) {

                $listaAutos = Auto::leerJson();

                
                var_dump($listaAutos);


                if (isset($_GET['patente'])) {

                    $patente = $_GET['patente'];
                    foreach($listaAutos as $unAuto)
                    {
                        if($unAuto->_patente == $patente)
                        {
                            echo $unAuto;
                        }
                    }
                    
                }

            } else {
                echo "Error. Debe estar logueado para acceder a esta pagina";
            }
        } else {
            echo "Metodo no permitido";
        }
        break;

    case 'retiro':
       if ($method == 'GET') {

            $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

            if ($validacion) {
                if(Token::tipoUsuarioSegunToken($_SERVER['HTTP_TOKEN']) == 'user')
                {
                    
                $fecha_egreso = date("d-m-Y H");
                $patente = $explode_path[2];

                $listaAutos = Auto::leerJson();
                $existe = false;

                if($listaAutos != null && count($listaAutos) > 0)
                {
                    foreach($listaAutos as $unAuto){
                        if($unAuto->_patente == $patente)
                        {
                            $tipo = $unAuto->_tipo;
                            $fecha_ingreso = $unAuto->_fecha_ingreso;
                            $existe = true;
                        break;

                        }
                    }

                    $ingreso = strtotime($fecha_ingreso);
                    $egreso = strtotime($fecha_egreso);
                    

                    switch($tipo)
                    {
                        case 'hora':
                            $horaIngreso = date('H', $ingreso);
                            $horaEgreso = date('H', $egreso);

                            $precios = Precio::leerJson();

                            $costo = $precios[0]->_precio_hora * ($horaEgreso - $horaIngreso);

                            echo $costo;
                        break;

                        case 'estadia':

                            $horaIngreso = date('d', $ingreso);
                            $horaEgreso = date('d', $egreso);

                            $precios = Precio::leerJson();

                            $costo = $precios[0]->_precio_estadia * ($horaEgreso - $horaIngreso);

                            echo $costo;
                        break;

                        case 'mensual':
                            
                            $horaIngreso = date('m', $ingreso);
                            $horaEgreso = date('m', $egreso);

                            $precios = Precio::leerJson();

                            $costo = $precios[0]->_precio_mensual * ($horaEgreso - $horaIngreso);

                            echo $costo;
                        break;
                    }
                }


                
                } else
                {
                    echo "Su tipo de usuario no le permite ingresar un auto.";
                }
              
            } else {
                echo "Error. Debe estar logueado para acceder a esta pagina";
            }
        } else {
            echo "Metodo no permitido";
        }
        break;

        case 'importe':
            if ($method == 'GET') {

                if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final'])) {

                    $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);
    
                    if ($validacion) {

    
                       if(Token::tipoUsuarioSegunToken($_SERVER['HTTP_TOKEN']) == 'admin')
                       {

                        $fecha_inicio = $_POST['fecha_inicio'];
                        $fecha_final = $_POST['fecha_final'];
                       }
                    }
                }
            }
        break;


}
