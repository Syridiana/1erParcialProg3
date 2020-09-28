<?php

    require_once "./FileHandler.php";

    class Usuario {
        public $_email;
        public $_tipo;
        public $_pass;

        public static $_pathTxt = './archivos/users.txt';
        public static $_pathJson = './archivos/users.json';
        public static $_pathSerialize = './archivos/usersSerialize.txt';

        function __construct($email = '',  $tipo = 'user', $pass = '') {
            $this->_email = $email;
            $this->_pass = $pass;
            $this->_tipo = $tipo;

        }
        

 



        public static function guardarJson($objeto) {

            FileHandler::guardarJson($objeto, Usuario::$_pathJson);
        }

      
        public static function leerJson(string $path) {

           $archivoArray = (array) FileHandler::leerJson($path);

          // var_dump($archivoArray);

           $listaUsuarios = [];

          foreach($archivoArray as $datos)
           {

               $nuevoUsuario = new Usuario($datos->_email, $datos->_tipo, $datos->_pass);
               array_push($listaUsuarios, $nuevoUsuario);
           }

           return $listaUsuarios;
        }


        public static function existeUsuario($usuario1, $listaUsuarios) {

            if($listaUsuarios != null)
            {
                foreach($listaUsuarios as $user)
                {
                    if($usuario1->_email == $user->_email)
                    {
                        return true;
                    } 
                }
    
            }

            return false;
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
            return $this->_email.'*'.$this->_tipo.'*'.$this->_pass;
         }

    }


