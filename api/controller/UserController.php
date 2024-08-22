<?php
require_once "api/model/user.model.php";
require_once "api/view/api-view.php";
require_once "api/utils/JwtMiddleware.php";
define('CACHE_DIR', __DIR__ . '/cache/');
// Asegúrate de que la ruta sea correcta
use Api\Utils\JwtMiddleware;
use App\Mailer;

class UserController
{
    private $cacheDir;

    private $modelUsers;
    private $view;
    private $data;
    private $jwtMiddleware; // Añade el middleware

    public function __construct()
    {
        $this->cacheDir = CACHE_DIR;
        // Verificar y crear el directorio de caché si no existe
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        $this->modelUsers = new UserModel();
        $this->view = new APIView();
        $this->jwtMiddleware = new JwtMiddleware(); // Inicializa el middleware

        $this->data = file_get_contents("php://input");
    }

    private function get_data()
    {
        return json_decode($this->data);
    }

    public function login()
    {
        // Obtener datos de la solicitud
        $data = $this->get_data();

        // Validar datos de entrada
        if (!isset($data->email) || !isset($data->password)) {
            $this->view->response("Email and password are required", 400);
            return;
        }

        $email = $data->email;
        $password = $data->password;

        // Verificar el formato del email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view->response("Invalid email format", 400);
            return;
        }

        // Obtener el usuario por email
        $user = $this->modelUsers->getUserByEmail($email);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user) {
            if (password_verify($password, $user->password)) {
                // Generar el JWT
                $jwt = $this->jwtMiddleware->generateJWT($user->email, '', $user->telefono, $user->direccion, $user->emailVerified);
                $this->view->response(['token' => $jwt], 200);
            } else {
                $this->view->response("Invalid credentials", 401);
            }
        } else {
            $this->view->response("User not found", 404);
        }
    }


    public function loginGoogle()
    {
        $customHeader = isset($_SERVER['HTTP_X_CUSTOM_HEADER']) ? $_SERVER['HTTP_X_CUSTOM_HEADER'] : null;

        if ($customHeader) {
            $firebaseToken = str_replace('Bearer ', '', $customHeader);

            $decodedToken = $this->modelUsers->verifyGoogleToken($firebaseToken);

            if ($decodedToken && is_object($decodedToken)) {
                // Extrae la información del payload
                $claims = $decodedToken->claims();
                $email = $claims->get('email') ?? null;
                $displayName = $claims->get('name') ?? null;
                $expiryTime = $claims->get('exp') ?? null;

                // Convierte el tiempo de vencimiento a un formato legible
                $expiryDate = $expiryTime instanceof \DateTimeImmutable ? $expiryTime->format('Y-m-d H:i:s') : null;

                // Desarma el displayName en nombre y apellido
                list($nombre, $apellido) = explode(' ', $displayName, 2) + [NULL, NULL];

                $user = $this->modelUsers->getUserByEmail($email);

                if ($user) {
                    // Si el usuario ya existe, actualízalo
                    $this->modelUsers->updateUserWithGoogle(
                        // Mantener el teléfono actual o actualizar si es necesario
                        $email,
                        1,
                        ''                  // Asumiendo que el email está verificado
                    );
                    $token = $this->jwtMiddleware->generateJWT($user->email, false, $user->telefono, $user->direccion, $user->emailVerified);
                    // Maneja el resultado de la actualización
                    $this->view->response([
                        'token' => $token,

                    ], 200);
                } else {
                    // Si el usuario no existe, créalo
                    $createResult = $this->modelUsers->createUserwithGoogle($nombre, $apellido, $email);

                    // Maneja el resultado de la creación
                    if (isset($createResult['id'])) {
                        $userById = $this->modelUsers->getUserById($createResult);

                        $rol = $userById->admin;
                        $token = $this->jwtMiddleware->generateJWT($userById, $rol, $userById->telefono, $userById->direccion, $userById->emailVerified);

                        $this->view->response([

                            'token' => $token
                        ], 200);
                    } else {
                        $this->view->response([
                            'error' => 'Error al crear el usuario.',
                        ], 500);
                    }
                }
            } else {
                $this->view->response([
                    'error' => 'Token no válido'
                ], 401);
            }
        } else {
            $this->view->response([
                'error' => 'Token no proporcionado'
            ], 400);
        }
    }



    public function register()
    {
        $data = $this->get_data();

        $id = null;
        $nick = $data->nick;
        $nombre = $data->nombre;
        $apellido = $data->apellido;
        $direccion = $data->direccion;
        $telefono = $data->telefono;
        $email = $data->email;
        $password = $data->password;

        $existingUser = $this->modelUsers->getUserByEmail($email);

        if ($existingUser) {
            $this->view->response(['error' => 'User already exists'], 409);
        } else {
            $result = $this->modelUsers->createUser($id, $nick, $nombre, $apellido, $direccion, $telefono, $email, $password);

            if (isset($result['id'])) {
                $this->view->response($result, 201);
            } else {
                $this->view->response(['error' => 'Failed to create user'], 500);
            }
        }
    }
    public function verifymail()
    {
        // Verifica si el token está presente en la URL
        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            // Llama al modelo para verificar el token
            $verified = $this->modelUsers->verifyUserByToken($token);

            if ($verified) {
                // Responde con un mensaje de éxito
                $this->view->response(['message' => 'Correo electrónico verificado con éxito.'], 200);
            } else {
                // Responde con un mensaje de error si el token no es válido
                $this->view->response(['message' => 'Token de verificación inválido o ya utilizado.'], 400);
            }
        } else {
            // Responde con un mensaje de error si el token no está presente
            $this->view->response(['message' => 'Token de verificación no proporcionado.'], 400);
        }
    }
    public function getUserInfo()
    {
        $customHeader = $_SERVER['HTTP_X_CUSTOM_HEADER'] ?? null;
        $jwt = $customHeader ? str_replace('Bearer ', '', $customHeader) : null;

        if ($jwt && $this->jwtMiddleware->validateJWT($jwt)) {
            $decoded = $this->jwtMiddleware->validateJWT($jwt);
            $email = $decoded->email ?? null;

            if ($email) {
                $user = $this->modelUsers->getUserByEmail($email);

                if ($user) {
                    unset($user->password);
                    $this->view->response($user, 200);
                } else {
                    $this->view->response("User not found", 404);
                }
            } else {
                $this->view->response("Invalid token", 400);
            }
        } else {
            $this->view->response("Unauthorized", 401);
        }
    }
    public function resendEmailVerification()
    {
        $customHeader = $_SERVER['HTTP_X_CUSTOM_HEADER'] ?? null;
        $jwt = $customHeader ? str_replace('Bearer ', '', $customHeader) : null;

        $data = $this->get_data();
        $email = $data->email ?? null;

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view->response(['message' => 'Email is required or invalid'], 400);
            return;
        }

        // Validar el JWT
        $user = $this->modelUsers->getUserByEmail($email);
        if ($jwt && $this->jwtMiddleware->validateJWT($jwt)) {
            if (!$user) {
                $this->view->response(['message' => 'User not found'], 404);
                return;
            }


            // Verificar el tiempo desde el último envío
            $lastSentTimestamp = $this->getCache($email);
            $currentTimestamp = time();
            $interval = 15 * 60; // 15 minutos en segundos

            if ($lastSentTimestamp && ($currentTimestamp - $lastSentTimestamp) < $interval) {
                $remainingTime = $interval - ($currentTimestamp - $lastSentTimestamp);
                $this->view->response([
                    'message' => 'Please wait before requesting another verification email.',
                    'retry_after' => $remainingTime
                ], 429);
                return;
            }

            // Generar nuevo token de verificación
            $tokenVerification = $this->jwtMiddleware->generateJWTVerification($email,);
            $this->modelUsers->updateUserVerificationToken($user->id, $tokenVerification);

            // Enviar el correo de verificación
            $emailSent = $this->modelUsers->sendVerificationEmail($email, $tokenVerification,$user->nombre,$user->apellido);

            // Actualizar la caché con el nuevo timestamp
            $this->setCache($email, $currentTimestamp);

            // Responder éxito
            $this->view->response([
                'message' => 'Verification email sent successfully.',
                'email_sent' => $emailSent
            ], 200);
        } else {
            $this->view->response(['message' => 'Invalid or missing JWT'], 401);
        }
    }

    private function setCache($email, $timestamp)
    {
        $cacheFile = $this->cacheDir . md5($email) . '.cache';
        file_put_contents($cacheFile, $timestamp);
    }

    // Método para leer de caché
    private function getCache($email)
    {
        $cacheFile = $this->cacheDir . md5($email) . '.cache';
        if (file_exists($cacheFile)) {
            return file_get_contents($cacheFile);
        }
        return null;
    }

    // Método para eliminar la caché (opcional)
    private function clearCache($email)
    {
        $cacheFile = $this->cacheDir . md5($email) . '.cache';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
