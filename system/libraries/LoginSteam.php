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
*   OPEN ID
*   @author Mewp
*   @copyright Copyright (c) 2010, Mewp
*   @license http://www.opensource.org/licenses/mit-license.php MIT
*   @link https://developer.valvesoftware.com/wiki/Steam_Web_API
*   @link http://steamcommunity.com/dev/apikey |Obtem a Key
*/

require_once(__DIR__.'/login/steam/openid.php');

class LoginSteam implements ISocialLogin {


    /**
    * Key da API
    * @var string 
    */
	protected $apiKey;

    /**
    * Domain do site a qual a API está vinculado
    * @var string 
    */
	protected $domain;

    /**
    * Página de retorno após login na Steam
    * @access protected
    * @var string
    */
    protected $redirectURL;

    /**
    * Mensagem de erro
    * @var string
    */
    private $error;

    public function __construct() {
        $credential = Config::getConfiguration('login');  

        $this->setCredentials($credential);
        $this->setRedirectURL($credential['redirectURLSteam']);
    }

    /**
    * Array com o api Key e o dominio a qual o app está vinculado
    * @param array @credential
    */
    public function setCredentials($credential) {
        if (!empty($credential['apiKey']))
            $this->apiKey        = $credential['apiKey'];
        if (!empty($credential['domain']))
            $this->domain    = $credential['domain'];

        Log::message(Language::getMessage('log', 'debug_steam_set_credentials', array('apiKey' => $this->apiKey, 'domain' => $this->domain)), 2);
    }

    /**
    * URL para onde será redirecionado após realizar o login
    * @param string $redirectURL
    */
    public function setRedirectURL($redirectURL) {
        if (!empty($redirectURL))  {
            $this->redirectURL = $redirectURL;
            if (substr($this->redirectURL, 0, 4) != 'http')
                $this->redirectURL = "http://".$this->redirectURL;
        }
    }
    
    /**
    * URL para logar na Steam
    * @return string
    */
    public function getURLLogin() {
        if (!$this->credentialIsSet()) return;

        $openID = new LightOpenID($this->domain);// put your domain

        if (!empty($this->redirectURL))
            $openID->returnUrl = $this->redirectURL;

        $openID->identity = 'http://steamcommunity.com/openid';
        $url = $openID->authUrl();
        Log::message(Language::getMessage('log', 'debug_steam_get_url', array('url' => $url)), 2);
        return $url;
    }

    /**
    * Dados após logado
    * @return array
    */
    public function getUserData() {
        if (!$this->credentialIsSet()) return;

        $openID = new LightOpenID($this->domain);// put your domain

        $mode = $openID->mode;
        if(isset($mode) && $mode != 'cancel') {           
            if($openID->validate()) {
                $URL        = $openID->identity;
                $steamID    = str_replace('http://steamcommunity.com/openid/id/', "", $URL);    
            } else {
                $message = Language::getMessage('login', 'steam_fail_auth');
                Log::message($message, 2);
                $this->setError($message);
            }
        } elseif(isset($openID->mode) && $openID->mode == 'cancel') {           
            $message = Language::getMessage('login', 'steam_cancel');
            Log::message($message, 2);
            $this->setError($message);
        }

        if (isset($steamID))
            return $this->getUserDataBySteamID($steamID);        
        return null;
    }

    public function getUserDataBySteamID($steamID) {
        if ($steamID) {
            $response = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $this->apiKey . '&steamids=' . $steamID);

            $data = json_decode($response, true);
            if (!empty($data['response']['players']))   {
                $data = $data['response']['players'][0];

                Log::message(Language::getMessage('log', 'debug_steam_get_data', array('data' => json_encode($data))), 2);
                return $data;
            }
        }
        return null;
    }


    /**
    * checa se as credências foram setadas
    * @return bool
    */
    protected function credentialIsSet() {
        if (empty($this->apiKey)) {
            $message = Language::getMessage('login', 'steam_credential', array('credencial' => 'apiKey'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 12);
        }

        if (empty($this->domain)) {
            $message = Language::getMessage('login', 'steam_credential', array('credencial' => 'domain'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 12);
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