<?php

namespace Api\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    private $errorMessage; // Nueva variable para almacenar mensajes de error

    public function __construct()
    {
        // Puedes agregar inicialización aquí si es necesario
    }

    protected function getJWTSecretKey()
    {
        global $parametros;
        return $parametros['jwt_secret_key'];
    }

    public function generateJWT($email,$rol,$telefono,$direccion,$emailVerified)
    {
        $secretKey = $this->getJWTSecretKey();

        $payload = [
            'email' => $email,
            'rol' => $rol,  // Asumiendo que 'rol' es una propiedad del usuario
            'telefono' => $telefono,
            'direccion' => $direccion,
            'emailVerified' => $emailVerified,
            'iat' => time(),
            'exp' => time() + (60 * 60) // 1 hora de expiración
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return $jwt;
    }
    public function generateJWTVerification($email)
    {
        $secretKey = $this->getJWTSecretKey();

        $payload = [
            'email' => $email,
      
            'iat' => time(),
            'exp' => time() + (15 * 60) // 1 hora de expiración
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return $jwt;
    }

    public function validateJWT($jwt)
    {
        $key = $this->getJWTSecretKey();
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            error_log("JWT Validation Error: " . $e->getMessage());
            $this->errorMessage = "JWT Validation Error: " . $e->getMessage();
            return false;
        }
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
?>
