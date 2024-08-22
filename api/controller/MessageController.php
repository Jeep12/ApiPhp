<?php
require_once "api/model/message.model.php";
require_once "api/view/api-view.php";
require_once "api/utils/JwtMiddleware.php";
require_once('vendor/autoload.php');  // Incluye autoload.php de Composer para Twilio

use Twilio\Rest\Client;
use Api\Utils\JwtMiddleware;

class MessageController
{
    private $view;  
    private $modelMessage;
    private $jwtMiddleware; 
    private $data;

    public function __construct()
    {
        $this->jwtMiddleware = new JwtMiddleware();
        $this->modelMessage = new MessageModel();
        $this->view = new APIView();
        $this->data = file_get_contents("php://input");
    }

    public function sendWhatsApp() {
        $data = json_decode($this->data, true);
    
        // Validar los datos
        if (!isset($data['to']) || !isset($data['message'])) {
            $this->view->response(['message' => 'Missing required fields'], 400);
            return;
        }
    
        $to = $data['to'];
        $message = $data['message'];
    
        // Configurar Twilio
        $sid = $this->getSid();
        $token = $this->getAuthToken();
        $twilio = new Client($sid, $token);
    
        try {
            // Enviar el mensaje
            $response =  $twilio->messages
            ->create("whatsapp:+5492983520538", // to
              array(
                "from" => "whatsapp:+14155238886",
                "body" => "Este es un mensaje de la asociacion de jugadores de Wow. La razon de este mensaje es para informarle que usted se a ganado un skin el lol, pinta un aram? "
              )
            );
      
    
            // Respuesta exitosa
            $this->view->response([
                'message' => 'Message sent successfully',
                'sid' => $response->sid,
                'to' => $response->to,
                'from' => $response->from
            ], 200);
        } catch (Exception $e) {
            // Respuesta con error
            $this->view->response([
                'message' => 'Failed to send message',
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 500);
        }
    }

    private function getSid()
    {
        global $parametros;
        return $parametros['sid'];
    }

    private function getAuthToken()
    {
        global $parametros;
        return $parametros['token'];
    }
}
