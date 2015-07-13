<?php
/**
*   JIndie
*   @package JIndie
*   @category support Class
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*
*   Google API - OAth
*   @version 2.0
*   @link https://github.com/google/google-api-php-client
*   @link https://console.developers.google.com/project
*/

require_once (__dir__.'/login/googleplus/src/Google/autoload.php');
require_once (__dir__.'/login/googleplus/src/Google/Client.php');
require_once (__dir__.'/login/googleplus/src/Google/Service/Oauth2.php');

class LoginGoogle implements ISocialLogin {


    /**
    * clientID no Google+
    * @access protected
    * @var int 
    */
    protected $clientID;

    /**
    * cliente secret no Google+
    * @access protected
    * @var string
    */
    protected $clientSecret;

    /**
    * Página de retorno após login no Google+
    * @access protected
    * @var string
    */
    protected $redirectURL;

    /**
    * Mensagem de erro
    * @access private
    * @var string
    */
    private $error;

    /**
    * @access protected
    * @var Input
    */
    protected $input;

    /**
    * @access protected
    * @var Google_Client;
    */
    protected $client;

    public function __construct() {
        $this->input = Input::getInstance();
        $this->client = new Google_Client();
        $credential = Config::getConfiguration('login');  

        $this->setCredentials($credential);
        if (empty($credential['redirectURLGoogle'])) 
            $this->setRedirectURL($input->getCurrentURL());
        else 
            $this->setRedirectURL($credential['redirectURLGoogle']);
    }

    /**
    * Array com o ID e Secret Key do aplicação do Google
    * @param array @credential
    */
    public function setCredentials($credential) {
        if (!empty($credential['clientID']))
            $this->clientID        = $credential['clientID'];
        if (!empty($credential['clientSecret']))
            $this->clientSecret    = $credential['clientSecret'];

        Log::message(Language::getMessage('log', 'debug_google_set_credentials', array('clientID' => $this->clientID, 'clientSecret' => $this->clientSecret)), 2);
    }

    /**
    * URL para onde será redirecionado após realizar o login
    * @param string $redirectURL
    */
    public function setRedirectURL($redirectURL) {
        $this->redirectURL = $redirectURL;
        if (substr($this->redirectURL, 0, 4) != 'http')
            $this->redirectURL = "http://".$this->redirectURL;
    }

    /**
    * Seta as configurações do Google_Client
    * @access protected
    */
    protected function initGoogleClient() {
        $this->client->setApplicationName("Login");
        $this->client->setClientId($this->clientID);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($this->redirectURL);        
        $this->client->addScope("https://www.googleapis.com/auth/userinfo.email");
    }

    /**
    * Retornar a URL para realizar o Login no Google
    * @return string
    */
    public function getURLLogin() {
        $this->initGoogleClient();
        $url = $this->client->createAuthUrl();
        Log::message(Language::getMessage('log', 'debug_google_get_url', array('url' => $url)), 2);
        return $url;
    }

    /**
    * Retorna um array com dados da conta do Google do Usuário após Login
    * @return array
    */
    public function getUserData() {
        $this->initGoogleClient();

        //Authenticate code from Google OAuth Flow       
        $code = $this->input->get('code');
        if (!empty($code)) {
            try {
                $this->client->authenticate($code);
                $acessToken = $this->client->getAccessToken();
            } catch (Exception $ex) {
                $error = "[GOOGLE] " . $ex->getMessage();
                Log::message($error, array(1, 2));
                throw new Exception($error, 15);
            } 
        }

        return $this->getUserDataByAccessToken($acessToken);
       
    }

    /**
    * Retorna um array com dados da conta do Google do Usuário usando um Token
    * @param string $acessToken |Um Json
    * @return array
    */
    public function getUserDataByAccessToken($acessToken) { 
        $this->initGoogleClient();
        $objOAuthService = new Google_Service_Oauth2($this->client);

        //Set Access Token to make Request
        if (isset($acessToken) && $acessToken) 
            $this->client->setAccessToken($acessToken);

        //Get User Data from Google Plus
        if ($this->client->getAccessToken()) 
            $userData = $objOAuthService->userinfo->get();
 
        if (isset($userData)) {
            $data['name']            = $userData->getName();
            $data['email']           = $userData->getEmail();
            $data['picture']         = $userData->getPicture();
            $data['id']              = $userData->getId();
            $data['link']            = $userData->getLink();
            $data['locale']          = $userData->getLocale();
            $data['verifiedEmail']   = $userData->getVerifiedEmail();
            $data['gender']          = $userData->getGender();
            $data['acessToken']      = $acessToken;

            Log::message(Language::getMessage('log', 'debug_google_get_data', array('data' => json_encode($data))), 2);
            return $data;
        }
    }

    /**
    * checa se as credências foram setadas
    * @access protected
    * @return bool
    */
    protected function credentialIsSet() {
        
        if (empty($this->clientID)) {
            $message = Language::getMessage('login', 'google_credential', array('credencial' => 'clientID'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 14);
        }

        if (empty($this->clientSecret)) {
            $message = Language::getMessage('login', 'google_credential', array('credencial' => 'clientSecret'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 14);
        }

        return true;
    }

    /**
    * Seta a mensagem de erro
    * @param string $error;
    */
    protected function setError($error) {
        $this->error = $error;
    }

    /**
    * Recupera a mensagem de error
    * @return string
    */
    public function getError() {
        return $this->error;
    }
}