<?php


require_once './Usuario.php';
require_once './Materia.php';
require_once './Profesor.php';
require_once './Asignacion.php';
require_once './Token.php';
require_once './PassManager.php';

use \Firebase\JWT\JWT;

require __DIR__ . '/vendor/autoload.php';

/**
 * METODOS
 * GET: OBTENER RECURSOS.
 * POST: CREAR RECURSOS.
 * PUT: MODIFICAR RECURSOS.
 * DELETE: BORRAR RECURSOS.
 */

 echo "hola";

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;

switch ($path) {

        #region /registro
    case '/registro':
        if ($method == 'POST') {

            if (isset($_POST['email']) && isset($_POST['tipo']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $tipo = $_POST['tipo'];
                $password = $_POST['password'];

                echo "$email $tipo $password";

                $usuario = new Usuario($email, $tipo, PassManager::Create($pass));

                if (!(Usuario::existeUsuario($usuario, Usuario::leerJson(Usuario::$_pathJson)))) {
                   // $usuario->guardarTxt(Usuario::$_pathTxt);
                   // FileHandler::serializar($usuario, Usuario::$_pathSerialize);

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
        #endregion

        #region /login
    case '/login':
        if ($method == 'POST') {
            $logueado = false;

            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $listaUsuariosJson = Usuario::leerJson(Usuario::$_pathJson);
                // $listaUsuariosTxt = Usuario::leerTxt(Usuario::$_pathTxt);
                //$listaUsuariosSerialize = FileHandler::deserializar(Usuario::$_pathSerialize);


                foreach ($listaUsuariosJson as $user) {
                    if ($user->_email == $nombreUsuario) {

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


                /* foreach($listaUsuariosTxt as $user)
                { 
                   if($user->_nombreUsuario == $nombreUsuario)
                   {

                   // echo $user->_pass;
                       if((strcmp($user->_pass, $pass)) == 0)
                       {
                            echo "Contraseña correcta";
                       } else
                       {
                            echo "Contraseña incorrecta";
                       }
                   }
                }*/

                /*
               foreach($listaUsuariosJson as $user)
                { 
                   if($user->_nombreUsuario == $nombreUsuario)
                   {
                       if((strcmp($user->_pass, $pass)) == 0)
                       {
                            echo "Contraseña correcta";
                       } else
                       {
                            echo "Contraseña incorrecta";
                       }
                   }
                }*/
            } else {
                echo "Debe cargar todos los datos para continuar.";
            }
        }
        break;

        #endregion

        #region /materia
    case '/materia':
        if ($method == 'POST') {


            if (isset($_POST['nombre']) && isset($_POST['cuatrimestre'])) {

                $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

                if ($validacion) {
                    $nombre = $_POST['nombre'];
                    $cuatrimestre = $_POST['cuatrimestre'];



                    echo "Materia: " . $nombre . ", ";
                    echo "Cuatrimestre: " . $cuatrimestre;

                    $materia = new Materia($nombre, $cuatrimestre, Materia::proximoIdMateria(Materia::leerJson()));

                    Materia::guardarJson($materia);
                    $materia->guardarTxt();
                    FileHandler::serializar($materia, Materia::$_pathSerialize);
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

            // var_dump($validacion);
        } else {
            echo "Metodo no permitido";
        }
        break;

        #endregion

        #region /profesor
    case '/profesor':
        if ($method == 'POST') {


            if (isset($_POST['nombre']) && isset($_POST['legajo'])) {

                $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

                if ($validacion) {

                    $nombre = $_POST['nombre'];
                    $legajo = $_POST['legajo'];

                    echo "Profesor: " . $nombre . ", ";
                    echo "Legajo: " . $legajo . "<br>";


 #region Txt
                    $profesor = new Profesor($nombre, $legajo);

                    $listaProfesores = Profesor::leerTxt();
                    $existe = false;


                    if ($listaProfesores != null && count($listaProfesores) > 0) {
                        foreach ($listaProfesores as $profesorLista) {
                            if ($profesorLista->_legajo == $legajo) {
                                $existe = true;
                            }
                        }
                    }


                    if (!$existe) {
                        $profesor->guardarTxt();
                    }

                    #endregion

                    #region Json
                    $existeJson = false;

                    $listaProfesoresJson = Profesor::leerJson();


                    if ($listaProfesores != null && count($listaProfesores) > 0) {
                        foreach ($listaProfesoresJson as $profesorJson) {
                            if ($profesorJson->_legajo == $legajo) {
                                $existeJson = true;
                            }
                        }
                    }

                    if (!$existe) {
                        Profesor::guardarJson($profesor);
                    }

                    #endregion 

                    #region Serialize

                    $existeSerialize = false;

                    $listaProfesoresSerialize = FileHandler::deserializar(Profesor::$_pathSerialize);


                    if ($listaProfesores != null && count($listaProfesores) > 0) {
                        foreach ($listaProfesoresSerialize as $profesorSerialize) {
                            if ($profesorSerialize->_legajo == $legajo) {
                                $existeSerialize = true;
                            }
                        }
                    }
                    if (!$existe) {
                        FileHandler::serializar($profesor, Profesor::$_pathSerialize);
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
                $listadoProfesores = array();
                $listadoProfesores = Profesor::leerJson();

                echo "Listado de docentes:</br>";

                if($listadoProfesores != null && count($listadoProfesores) > 0)
                {
                    foreach($listadoProfesores as $docente)
                    {
                        echo "$docente->_nombre, legajo: $docente->_legajo. </br>";
                    }
                } else
                {
                    echo "No hay docentes cargados.";
                }
            } else {
                echo "Error. Debe estar logueado para acceder a esta pagina";
            }
        } else {
            echo "Metodo no permitido";
        }
        break;
        #endregion

        #region /aisgnacion
    case '/asignacion':
        if ($method == 'POST') {
            if (isset($_POST['legajo']) && isset($_POST['idMateria']) && isset($_POST['turno'])) {

                $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

                if ($validacion) {

                    $idMateria = $_POST['idMateria'];
                    $legajo = $_POST['legajo'];
                    $turno = $_POST['turno'];

                    echo "Legajo Profesor: " . $legajo . ", ";
                    echo "Id Materia: " . $idMateria . ", ";
                    echo "Turno: " . $turno . "<br>";


                    #region Txt
                    $asignacion = new Asignacion($legajo, $turno, $idMateria);

                    $listaAsignaciones = Asignacion::leerTxt();
                    $existe = false;


                    if ($listaAsignaciones != null && count($listaAsignaciones) > 0) {
                        foreach ($listaAsignaciones as $asignacionTxt) {
                            if ($asignacionTxt->_legajoProfesor == $legajo && $asignacionTxt->_turno == $turno && $asignacionTxt->_idMateria == $idMateria) {
                                $existe = true;
                            }
                        }
                    }
                    if (!$existe) {
                        $asignacion->guardarTxt();
                    }

                    #endregion

                    #region Json
                    $existeJson = false;

                    $listaAsignacionesJson = Asignacion::leerJson();


                    if ($listaAsignaciones != null && count($listaAsignaciones) > 0) {
                        foreach ($listaAsignacionesJson as $asignacionJson) {
                            if ($asignacionJson->_legajoProfesor == $legajo && $asignacionJson->_turno == $turno && $asignacionJson->_idMateria == $idMateria) {
                                $existeJson = true;
                            }
                        }
                    }

                    if (!$existe) {
                        Asignacion::guardarJson($asignacion);
                    }

                    #endregion 

                    #region Serialize

                    $existeSerialize = false;

                    $listaAsignacionesSerialize = FileHandler::deserializar(Asignacion::$_pathSerialize);

                    if ($listaAsignaciones != null && count($listaAsignaciones) > 0) {
                        foreach ($listaAsignacionesSerialize as $asignacionSerialize) {
                            if ($asignacionSerialize->_legajoProfesor == $legajo && $asignacionSerialize->_turno == $turno && $asignacionSerialize->_idMateria == $idMateria) {
                                $existeSerialize = true;
                            }
                        }
                    }

                    if (!$existe) {
                        FileHandler::serializar($asignacion, Asignacion::$_pathSerialize);
                    }
                } else {
                    echo "Error. Debe estar logueado para acceder a esta pagina.";
                }
            } else {
                echo "Debe cargar todos los datos para continuar.";
            }

            #endregion





        } else if ($method == 'GET') {
            $validacion = Token::validarToken($_SERVER['HTTP_TOKEN']);

            if ($validacion) {
                $listadoProfesores = array();
                $listadoProfesores = Profesor::leerJson();

                $listadoAsignaciones = array();
                $listadoAsignaciones = Asignacion::leerJson();

                $listadoMaterias = array();
                $listadoMaterias = Materia::leerJson();

                //var_dump($listadoProfesores);


                if ($listadoProfesores != null && count($listadoProfesores) > 0) {

                    foreach ($listadoProfesores as $profesor) {

                        echo "Docente: $profesor, Materias asignadas: ";

                        if ($listadoAsignaciones != null && count($listadoAsignaciones) > 0) {
                            foreach ($listadoAsignaciones as $asignacion) {
                                if ($asignacion->_legajoProfesor == $profesor->_legajo) {
                                    if ($listadoMaterias != null && count($listadoMaterias) > 0) {
                                        foreach ($listadoMaterias as $materia) {
                                            if ($asignacion->_idMateria == $materia->_id) {
                                                echo "$materia->_nombre, ";
                                            }
                                        }
                                    } else {
                                        echo "No hay materias registradas";
                                    }
                                }
                            }
                        } else {
                            echo "No asignaciones registradas.";
                        }
                        echo "<br><br>";
                    }
                } else {
                    echo "No hay profesores registrados";
                }
            } else {
                echo "Error. Debe estar logueado para acceder a esta pagina";
            }
        } else {
            echo "Metodo no permitido";
        }
        break;
        #endregion


}
