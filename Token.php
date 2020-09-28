<?php

use \Firebase\JWT\JWT;
require __DIR__ . '/vendor/autoload.php';

class Token{

    private static $unaKey = 'primerparcial';

    public static function getToken($email, $tipo)
    {

        $payload = array(
            'data' => [
                'email' => $email,
                'tipo' => $tipo
            ]            );

        $jwt = JWT::encode($payload, Token::$unaKey);
     //   $decoded = JWT::decode($jwt, Token::$unaKey, array('HS256'));
    //    var_dump($decoded);
    
        return $jwt;
    
    }


    public static function validarToken($token)
    {
        try{

            $decoded = JWT::decode($token, Token::$unaKey, array('HS256'));

        } catch(Exception $e)
        {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }


    //    var_dump($token);
    
        return $decoded;
    
    }

    public static function GetHeader($KEY)
    {
        $headers = getallheaders();

        if($headers != false)
        {
            if(isset($headers[$KEY]) && !empty($headers[$KEY]))
            {
                return $headers[$KEY];
            }
        }
        return null;
    }
}





/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */


/*$decoded = JWT::decode($jwt, $key, array('HS256'));

print_r($decoded);*/

/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/

//$decoded_array = (array) $decoded;

/**
 * You can add a leeway to account for when there is a clock skew times between
 * the signing and verifying servers. It is recommended that this leeway should
 * not be bigger than a few minutes.
 *
 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
 */
/*JWT::$leeway = 60; // $leeway in seconds
$decoded = JWT::decode($jwt, $key, array('HS256'));*/
//TODO generar token
//TODO 

?>