<?php

    require_once "./FileHandler.php";

    class Materia {
        public $_nombre;
        public $_cuatrimestre;
        public $_id;
        public static $_pathTxt = './archivos/materias.txt';
        public static $_pathJson = './archivos/materias.json';
        public static $_pathSerialize = './archivos/materiasSerialize.txt';


        function __construct($nombre = '', $cuatrimestre = '', $id = '') {
            $this->_nombre = $nombre;
            $this->_cuatrimestre = $cuatrimestre;

            $this->_id = $id;

        }
        

        public function guardarTxt() {
            FileHandler::guardarTxt(Materia::$_pathTxt, $this);
        }

      
        public static function leerTxt() {

            $archivoMaterias = FileHandler::BringArray(Materia::$_pathTxt);

            $listaMaterias = array();

            foreach($archivoMaterias as $datos)
            {  

                if(count($datos) == 3)
                {
                    $materia = new Materia($datos[1], $datos[2]);
                    array_push($listaMaterias, $materia);

                }

            }


            return $listaMaterias;
        }




        public static function guardarJson($objeto) {

            FileHandler::guardarJson($objeto, Materia::$_pathJson);
        }

      
        public static function leerJson() {

           $archivoArray = (array) FileHandler::leerJson(Materia::$_pathJson);

           $listaMaterias = [];

          foreach($archivoArray as $datos)
           {
               $nuevaMateria = new Materia($datos->_nombre, $datos->_cuatrimestre, $datos->_id);
               array_push($listaMaterias, $nuevaMateria);
           }

           return $listaMaterias;
        }


        public static function proximoIdMateria(array $listaDeMaterias) {
            $maximo = 0;

            foreach($listaDeMaterias as $materia)
            {
                if($materia->_id > $maximo)
                {
                    $maximo = $materia->_id;
                }
            }

            $maximo++;

            return $maximo;
           
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
            return $this->_id.'*'.$this->_nombre.'*'.$this->_cuatrimestre;
         }

    }


