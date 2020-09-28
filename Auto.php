<?php

    require_once "./FileHandler.php";

    class Auto {
        public $_patente;
        public $_tipo;
        public $_fecha_ingreso;
        public $_email;

        public static $_pathTxt = './archivos/autos.txt';
        public static $_pathJson = './archivos/autos.json';
        public static $_pathSerialize = './archivos/autosSerialize.txt';


        function __construct($patente = '', $tipo = '', $fecha = '', $email = '') {
            $this->_patente = $patente;
            $this->_tipo = $tipo;
            $this->_fecha_ingreso = $fecha;
            $this->_email = $email;

        }
        



        public static function guardarJson($objeto) {

            FileHandler::guardarJson($objeto, Auto::$_pathJson);
        }




      
        public static function leerJson() {

           $archivoArray = (array) FileHandler::leerJson(Auto::$_pathJson);

           $listaAutos = [];

          foreach($archivoArray as $datos)
           {
               $nuevoAuto = new Auto($datos->_patente, $datos->_tipo, $datos->_fecha_ingreso, $datos->_email);
               array_push($listaAutos, $nuevoAuto);
           }

           return $listaAutos;
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
            return $this->_patente.'*'.$this->_tipo.'*'.$this->_fecha_ingreso.'*'.$this->_email;
         }

    }


