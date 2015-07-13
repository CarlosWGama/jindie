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
*   Twitter OAuth REST API
*   @author braham Williams <abraham@abrah.am>
*   @copyright Copyright (c) 2015
*   @license MIT
*   @link https://apps.twitter.com/app/new
*   @link https://twitteroauth.com/redirect.php
*   @version 0.5.3
*/

require_once(__DIR__.'/login/twitter/autoload.php');
require_once(__DIR__.'/Session.php');

use Abraham\TwitterOAuth\TwitterOAuth;

class LoginTwitter implements ISocialLogin {

    /**
    * Key do app no twitter
    * @access protected
    * @var int 
    */
    protected $consumerKey;

    /**
    * consumer key no Twitter
    * @access protected
    * @var string
    */
    protected $consumerSecret;

    /**
    * Página de retorno após login no Twiiter
    * @access protected
    * @var string
    */
    protected $redirectURL;

    /**
    * Mensagem de erro
    * @var string
    */
    private $error;

    /**
    *  @var Input
    */
    protected $input;

    /**
    *  @var Session
    */
    protected $session;

    public function __construct() {
        $this->input = Input::getInstance();
        $this->session = new Session();
        $credential = Config::getConfiguration('login');  

        $this->setCredentials($credential);
        if (empty($credential['redirectURLTwitter'])) 
            $this->setRedirectURL($input->getCurrentURL());
        else 
            $this->setRedirectURL($credential['redirectURLTwitter']);
    }

    /**
    * Array com o ID e Secret Key do aplicação do Twitter
    * @param array $credential
    */
    public function setCredentials($credential) {
        if (!empty($credential['consumerKey']))
            $this->consumerKey        = $credential['consumerKey'];
        if (!empty($credential['consumerSecret']))
            $this->consumerSecret    = $credential['consumerSecret'];

        Log::message(Language::getMessage('log', 'debug_twitter_set_credentials', array('consumerKey' => $this->consumerKey, 'consumerSecret' => $this->consumerSecret)), 2);
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
    * URL para logar no Twitter
    * @return string
    */
    public function getURLLogin() {
        if (!$this->credentialIsSet()) return;

        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->redirectURL));
        
        if($request_token) {
            $this->session->set('request_token', $request_token['oauth_token']);
            $this->session->set('request_token_secret', $request_token['oauth_token_secret']);

            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        } else {
            $message = Language::getMessage('login', 'twitter_request_token');
            Log::message($message, array(1, 2));
            $this->setError($message);
            return null;
        }

        Log::message(Language::getMessage('log', 'debug_twitter_get_url', array('url' => $url)), 2);
        return $url;     
    }

    /**
    * Dados após logado
    * @return array
    */
    public function getUserData() {
        if (!$this->credentialIsSet()) return;
        $oauth = $this->input->get('oauth_token');
        $request_token = $this->session->get('request_token');
        $request_token_secret = $this->session->get('request_token_secret');
        
        if(empty($oauth) || empty($request_token) || empty($request_token_secret)) {
            $message = Language::getMessage('login', 'twitter_fail_auth');
            Log::message($message, 2);
            $this->setError($message);
        } else {
            $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $request_token, $request_token_secret);
            $accessToken = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            return $this->getUserDataByAccessToken($accessToken);

        }
        
        return null;
    }

    public function getUserDataByAccessToken($accessToken) {
        if($accessToken) {
            $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);
            $user = $connection->get("account/verify_credentials");

            if(isset($user->screen_name) && isset($user->name)) {
                $data = get_object_vars($user);
                $data['acessToken'] = $accessToken;

                Log::message(Language::getMessage('log', 'debug_twitter_get_data', array('data' => json_encode($data))), 2);
                return $data;
            } else {
                $message = Language::getMessage('login', 'twitter_fail_auth');
                Log::message($message, 2);
                $this->setError($message);
            }
        }

        return null;
    }

    /**
    * checa se as credências foram setadas
    * @return bool
    */
    protected function credentialIsSet() {
        if (empty($this->consumerKey)) {
            $message = Language::getMessage('login', 'twitter_credential', array('credencial' => 'consumerKey'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 13);
        }

        if (empty($this->consumerSecret)) {
            $message = Language::getMessage('login', 'twitter_credential', array('credencial' => 'consumerSecret'));
            Log::message($message, array(1, 2));
            throw new Exception($message, 13);
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