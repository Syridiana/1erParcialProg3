<?php

    require_once "./FileHandler.php";

    class Asignacion {
        public $_legajoProfesor;
        public $_turno;
        public $_idMateria;
        public static $_pathTxt = './archivos/asignacion.txt';
        public static $_pathJson = './archivos/asignacion.json';
        public static $_pathSerialize = './archivos/asignacionSerialize.txt';


        function __construct($legajoProfesor = '', $turno = '', $idMateria = '') {
            $this->_legajoProfesor = $legajoProfesor;
            $this->_turno = $turno;
            $this->_idMateria = $idMateria;
        }

        

        public function guardarTxt() {
            FileHandler::guardarTxt(Asignacion::$_pathTxt, $this);
        }

      
        public static function leerTxt() {

            $archivoAsignaciones = FileHandler::BringArray(Asignacion::$_pathTxt);

            $listaAsignaciones = array();

            foreach($archivoAsignaciones as $datos)
            {  

                if(count($datos) == 3)
                {
                    $asignacion = new Asignacion($datos[0], $datos[1], $datos[2]);
                    array_push($listaAsignaciones, $asignacion);

                }

            }


            return $listaAsignaciones;
        }




        public static function guardarJson($objeto) {

            FileHandler::guardarJson($objeto, Asignacion::$_pathJson);
        }

      
        public static function leerJson() {

           $archivoArray = (array) FileHandler::leerJson(Asignacion::$_pathJson);


           $listaAsignaciones = array();

          foreach($archivoArray as $datos)
           {

               $nuevaAsignacion = new Asignacion($datos->_legajoProfesor, $datos->_turno, $datos->_idMateria);
               array_push($listaAsignaciones, $nuevaAsignacion);
           }

           return $listaAsignaciones;
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
            return $this->_legajoProfesor.'*'.$this->_turno.'*'.$this->_idMateria;
         }

    }


