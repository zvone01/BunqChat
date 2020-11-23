<?php
require_once 'jwt/BeforeValidException.php';
require_once 'jwt/ExpiredException.php';
require_once 'jwt/SignatureInvalidException.php';
require_once 'jwt/JWT.php';


use \Firebase\JWT\JWT; 
define('SECRET_KEY','Kljucic.koji.je.tajni12345');
define('SECRET_KEY_PASS','Your-Not-Secret-Key12345');  /// secret key can be a random string and keep in secret from anyone
define('ALGORITHM','HS512');   // Algorithm used to sign the token, see
                               //https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
class Token
{
    
    
    public static function create_token($id,$name)
    {
        $configs = include('config.php');
        $tokenId    = 55;
        $issuedAt   = time();
        $notBefore  = $issuedAt -1;  //Adding 10 seconds
        $expire     = $notBefore + 7200; // Adding 7200 seconds
        $serverName = $configs->host; /// set your domain name 

        /*
         * Create the token as an array
         */
        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => [                  // Data related to the logged user you can set your required data
            'id'   => $id, // id from the users table
            'name' => $name, //  name
                      ]
        ];
      /// Here we will transform this array into JWT:
        $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                SECRET_KEY,
                ALGORITHM
               ); 
        return $jwt;

    }

    public static function create_token_resetPass($userID,$name)
    {
        $configs = include('config.php');
        $tokenId    = 55;
        $issuedAt   = time();
        $notBefore  = $issuedAt -1;  //Adding 10 seconds
        $expire     = $notBefore + 7200; // Adding 7200 seconds
        $serverName = $configs->host; /// set your domain name 

        /*
         * Create the token as an array
         */
        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'data' => [                  // Data related to the logged user you can set your required data
            'id'   => $userID, // id from the users table
            'name' => $name,         
                      ]
        ];
      /// Here we will transform this array into JWT:
        $jwt = JWT::encode(
                $data, //Data to be encoded in the JWT
                SECRET_KEY_PASS,
                ALGORITHM
               ); 
        return $jwt;

    }

    public static function is_valid($token)
    {
        try
        {
            $decoded = JWT::decode($token, SECRET_KEY, array(ALGORITHM));
            $array = json_decode(json_encode($decoded), true);
            return $array;
        }
        catch (\Exception $e) 
        {
            return false;
        }

    }
    public static function is_valid_pass($token)
    {
        try
        {
            $decoded = JWT::decode($token, SECRET_KEY_PASS, array(ALGORITHM));
            $array = json_decode(json_encode($decoded), true);
            return $array;
        }
        catch (\Exception $e) 
        {
            return false;
        }

    }
}
?>