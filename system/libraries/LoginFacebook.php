<?php
/**
* 	JIndie
*	@package JIndie
*	@category support Class
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*
*	Facebook SDK
* 	@version 4.0
* 	@author Facebook
* 	@link https://developers.facebook.com/docs/php/api/4.0.0
* 	@link https://developers.facebook.com/ |Obtem o appID e appSecret
*/

require_once(__DIR__.'/login/facebook/autoload.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphUser;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

class LoginFacebook implements ISocialLogin {

	/**
	* ID do app no facebook
	* @access protected
	* @var int 
	*/
	protected $appID;

	/**
	* secret key no facebook
	* @access protected
	* @var string
	*/
	protected $appSecret;

	/**
	* Página de retorno após login no Facebook
	* @access protected
	* @var string
	*/
	protected $redirectURL;

	/**
	* Deve pegar os amigos do facebook que usam o aplicativo?
	* @access protected
	* @var bool
	*/
	protected $getFriends;

	/**
	* Mensagem de erro
	* @var string
	*/
	private $error;

	public function __construct() {
		$credential = Config::getConfiguration('login');  

		$this->setCredentials($credential);
		$this->setRedirectURL($credential['redirectURLFacebook']);
		$this->getFriends = (isset($credential['getFriendsUsingApp']) ? (bool) $credential['getFriendsUsingApp'] : false);

		//Se sessão não tiver sido iniciada, a inicializa
		if (session_status() == PHP_SESSION_NONE) {
 	 		require_once(__dir__.'/Session.php');   
 	 		$session = new Session();
		}
	}

	/**
	* Array com o ID e Secret Key do aplicação do facebook
	* @param array @credential
	*/
	public function setCredentials($credential) {
		if (!empty($credential['appID']))
			$this->appID 		= $credential['appID'];
		if (!empty($credential['appSecret']))
			$this->appSecret 	= $credential['appSecret'];

		Log::message(Language::getMessage('log', 'debug_facebook_set_credentials', array('appID' => $this->appID, 'appSecret' => $this->appSecret)), 2);

		FacebookSession::setDefaultApplication($this->appID, $this->appSecret);
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
	* URL para logar no facebook
	* @return string
	*/
	public function getURLLogin() {
		if (!$this->credentialIsSet()) return;

		$helper = new FacebookRedirectLoginHelper($this->redirectURL, $this->appID, $this->appSecret);
		try {

			if ($this->getFriends)
				$url = $helper->getLoginUrl(array('user_friends'));
			else
				$url = $helper->getLoginUrl();

			Log::message(Language::getMessage('log', 'debug_facebook_get_url', array('url' => $url)), 2);

			return $url;	
		} catch (Exception $ex) {
			throw new Exception($ex->getMessage());
		}	
	}

	/**
	* Dados após logado
	* @return array
	*/
	public function getUserData() {
	
		if (!$this->credentialIsSet()) return;
	
		$helper = new FacebookRedirectLoginHelper($this->redirectURL);
	
		try {
  			$session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
			Log::message("[FACEBOOK]" . $ex->getMessage(), array(1, 2));
			$this->setError($ex->getMessage());
			return null;
		} catch(Exception $ex) {
			Log::message("[FACEBOOK]" . $ex->getMessage(), array(1, 2));
			$this->setError($ex->getMessage());
			return null;
		}

		if (isset($session)) {
		
			$object = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject();

			//avatar = <img src="//graph.facebook.com/$id/picture?type=large">
			$data = array(
				'id' 			=> $object->getProperty('id'),
				'name' 			=> $object->getProperty('name'),
				'first_name' 	=> $object->getProperty('first_name'),
				'gender'		=> $object->getProperty('gender'),
				'last_name'		=> $object->getProperty('last_name'),
				'link' 			=> $object->getProperty('link'),
				'locale' 		=> $object->getProperty('locale'),
				'timezone' 		=> $object->getProperty('timezone'),
				'updated_time'	=> $object->getProperty('updated_time'),
				'verified' 		=>$object->getProperty('verified'),
			);
			
			if ($this->getFriends) {
				$object = (new FacebookRequest($session, 'GET', '/me/friends'))->execute()->getGraphObject()->asArray();	
				foreach ($object['data'] as $friend) {
					$data['friends'][] = array(
						'id'		=> $friend->id,
						'name'		=> $friend->name
					);
				}
			}
			
			Log::message(Language::getMessage('log', 'debug_facebook_get_data', array('data' => json_encode($data))), 2);
			return $data;
		}
		return null;
		

	}

	/**
	* checa se as credências foram setadas
	* @return bool
	*/
	protected function credentialIsSet() {
		if (empty($this->appID)) {
			$message = Language::getMessage('login', 'facebook_credential', array('credencial' => 'AppID'));
			Log::message($message, array(1, 2));
			throw new Exception($message, 11);
		}

		if (empty($this->appSecret)) {
			$message = Language::getMessage('login', 'facebook_credential', array('credencial' => 'appSecret'));
			Log::message($message, array(1, 2));
			throw new Exception($message, 11);
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