<?php

    require_once "./FileHandler.php";

    class Profesor {
        public $_nombre;
        public $_legajo;
        public static $_pathTxt = './archivos/profesores.txt';
        public static $_pathJson = './archivos/profesores.json';
        public static $_pathSerialize = './archivos/profesoresSerialize.txt';


        function __construct($nombre = '', $legajo = '') {
            $this->_nombre = $nombre;
            $this->_legajo = $legajo;
        }

        

        public function guardarTxt() {
            FileHandler::guardarTxt(Profesor::$_pathTxt, $this);

        }

      
        public static function leerTxt() {

            $archivoProfesores = FileHandler::BringArray(Profesor::$_pathTxt);

            $listaProfesores = array();



            foreach($archivoProfesores as $datos)
            {  

                if(count($datos) == 2)
                {
                    $profesor = new Profesor($datos[1], $datos[0]);
                    array_push($listaProfesores, $profesor);

                }

            }

            return $listaProfesores;
        }




        public static function guardarJson($objeto) {

            FileHandler::guardarJson($objeto, Profesor::$_pathJson);
        }

      
        public static function leerJson() {

           $archivoArray = (array) FileHandler::leerJson(Profesor::$_pathJson);

           //var_dump($archivoArray);

           $listaProfesores = [];

          foreach($archivoArray as $datos)
           {

               $nuevoProfesor = new Profesor($datos->_nombre, $datos->_legajo);
               array_push($listaProfesores, $nuevoProfesor);
           }

           return $listaProfesores;
        }





        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        public function __get($name)
        {
            return $this->$name;
        }


        public function __toString(){
            return $this->_legajo.'*'.$this->_nombre;
         }

    }


