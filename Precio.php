<?php

    require_once "./FileHandler.php";

    class Precio {
        public $_precio_hora;
        public $_precio_estadia;
        public $_precio_mensual;

        public static $_pathTxt = './archivos/precios.txt';
        public static $_pathJson = './archivos/precios.json';
        public static $_pathSerialize = './archivos/preciosSerialize.txt';


        function __construct($precio_hora = -1, $precio_estadia = -1, $precio_mensual = -1) {
            $this->_precio_hora = $precio_hora;
            $this->_precio_estadia = $precio_estadia;
            $this->_precio_mensual = $precio_mensual;

        }

        

     



        public static function sobreEscribirJson($objeto) {

            FileHandler::guardarJson($objeto, Precio::$_pathJson);
        }

      
        public static function leerJson() {

           $archivoArray = (array) FileHandler::leerJson(Precio::$_pathJson);

           //var_dump($archivoArray);

           $listaPrecios = [];

          foreach($archivoArray as $datos)
           {

               $nuevoPrecio = new Precio($datos->_precio_hora, $datos->_precio_estadia, $datos->_precio_mensual);
               array_push($listaPrecios, $nuevoPrecio);
           }

           return $listaPrecios;
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
            return $this->_precio_hora.'*'.$this->_precio_estadia.'*'.$this->_precio_mensual;
         }

    }


