<?php

class Teste extends Controller{


	public function __construct() {
		parent::__construct();
	}

	public function index() {
		//Load Library//
		//$this->loadLibrary('formValidation');
		//$this->formValidation->check();

		//$this->loadLibrary('lib');
		//$this->lib->check();

		//Load Model//
		//$this->loadModel("jogo", 'teste');
		//$this->teste->jogar();

		//View teste//
		//$dados['msg'] = 'nome';
		//$this->view->render('teste', $dados);
		die($this->input->get("teste"));
	}

	public function form() {
		$this->loadLibrary('formValidation');
		$dados['errors'] = array();
		if ($this->input->post('enviar')) {
			$this->formValidation->addRule('required', 'REQUIRED', 'required');
			$this->formValidation->addRule('match', 'MATCH', 'matches[required]');
			$this->formValidation->addRule('min_length', 'MIN LENGTH', 'minLength[3]');
			$this->formValidation->addRule('max_length', 'MAX LENGTH', 'maxLength[3]');
			$this->formValidation->addRule('exac_length', 'EXA LENGTH', 'exactLength[3]');
			$this->formValidation->addRule('max_value', 'MAX VALUE', 'maxValue[3]');
			$this->formValidation->addRule('max_value', 'MAX VALUE', 'minValue[3]');
			$this->formValidation->addRule('exa_value', 'EXA VALUE', 'exactValue[3]');
			$this->formValidation->addRule('number', 'Number', 'numeric');
			$this->formValidation->addRule('integer', 'Integer', 'integer');
			$this->formValidation->addRule('email', 'EMAIL', 'email;unique[usuarios.email(id,1)]');
			$this->formValidation->addRule('cpf', 'CPF', 'cpf');
			$this->formValidation->addRule('cnpj', 'CNPJ', 'cnpj');
			$this->formValidation->addRule('arquivo', 'FILE', 'fileRequired;fileExt[doc, png];fileMaxSize[0.5]');
			
			//'fileRequired'	
			//'fileExt'
			//'fileMaxSize'

			if ($this->formValidation->check()) {
				echo htmlspecialchars_decode($this->input->post("required"));
			} else {
				$dados['errors'] = $this->formValidation->getErrors();
			}
		}
		$this->view->render('form', $dados);
	}

	public function email() {
		$this->loadLibrary('email');

		$this->email->subject = "Teste";
		$this->email->msg = "<strong>Test</strong>e";
 		$this->email->setFrom('carlos@jogosindie.com', 'Carlos');
		$this->email->addTo('carloswgama@gmail.com', 'Carlos 2');
		$this->email->addAttachment(dirname(__FILE__).'/../../index.php', 'arquivo');

		if ($this->email->send())
			echo "Sucesso";
		else
			echo "Faio";
	}

	public function cookie($dados = false) {
		$this->loadLibrary("cookie");

		$this->cookie->setExpiry('day');
		if ($dados == "exibir") {
			echo "EXIBIR COOKIE - " . $this->cookie->get("teste");
		} elseif ($dados == "deletar") {
			$this->cookie->delete("teste");
			echo "Deletou";
		} else {
			$this->cookie->set("teste", "teste");
			echo "SALVOU COOKIE";
		}		
	}

	public function session($dados = false) {
		$this->loadLibrary("session");

		if ($dados == "exibir") {
			echo "EXIBIR Sessão - " . $this->session->get("teste2");
		} elseif ($dados == "deletar") {
			$this->session->delete("teste2");
			echo "Deletou";
		} else {
			$this->session->set("teste2", "teste");
			echo "SALVOU Sessão";
		}		
	}

	public function cache() {
		$this->loadLibrary('cache');

		if ($var = $this->cache->get('teste')) {
			echo $var;
		} else {
			$this->cache->set('teste', 'casa');
		}
	}

	public function upload() {
		if ($this->input->post('enviar')) {
			$this->loadLibrary('upload');

			$dir = $_SERVER['DOCUMENT_ROOT']. '/upload';
			//if($this->upload->doUpload('arquivo')) 					//OK
			//if($this->upload->doUpload('arquivo', $dir)) 				//OK
			//if($this->upload->doUpload('arquivo', $dir, 'imagem')) 	//OK
			/*if($this->upload->doUpload('arquivo', $dir, 'imagem', true))//OK
				echo "SUCESSO<br/>";
			else
				echo $this->upload->getError();*/

			if($this->upload->doMultiUpload('arquivos', $dir, array('imagem1', 'imagem2')))//OK
				echo "SUCESSO<br/>";
			else
				echo $this->upload->getError();
		}

		$this->view->render('upload');
	}

	public function database() {
		$this->loadDatabase('banco');
		print_r($this->banco->getOne("usuarios"));
		echo "OK!";
	}

	public function login() {
		$this->loadLibrary('loginFacebook');
		$this->loadLibrary('loginGoogle');
		$this->loadLibrary('loginTwitter');
		$this->loadLibrary('loginSteam');
		

		$url['facebook'] 	= $this->loginFacebook->getURLLogin();
		$url['google'] 		= $this->loginGoogle->getURLLogin();
		$url['twitter'] 	= $this->loginTwitter->getURLLogin();
		$url['steam'] 		= $this->loginSteam->getURLLogin();
		
		print_r($url);

		//header("Location: " . $urlGoogle);
		
		echo '[FIM]';
	}

	public function facebook() {
		$this->loadLibrary('loginFacebook');
		print_r($this->loginFacebook->getUserData());
	}	

	public function google() {
		$this->loadLibrary('loginGoogle');
		print_r($this->loginGoogle->getUserData());	

		//$acessToken = '{"access_token":"ya29.qgGKBL0qyy4EH3SdWcMnnwLt--fVcwFMlTBWsy9yMJ2nhaw7rTSUiFbyS1bsrKef2-ULNgF97DvFYw","token_type":"Bearer","expires_in":3596,"id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6ImJjOGEzMTkyN2FmMjA4NjA0MThmNmIyMjMxYmJmZDdlYmNjMDQ2NjUifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwic3ViIjoiMTE0NjAzNDU2MzkzMjc2MDcyNDAxIiwiYXpwIjoiMTQ4Nzk4MjA0NTE4LWsyYWZsOThvZDI0bnFvaWEwcDZsNTM0bzQwc2gzb3R0LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJjYXJsb3N3Z2FtYUBnbWFpbC5jb20iLCJhdF9oYXNoIjoiMy1PX1ZOOURPM3hDZkhnRGI0d2lQZyIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdWQiOiIxNDg3OTgyMDQ1MTgtazJhZmw5OG9kMjRucW9pYTBwNmw1MzRvNDBzaDNvdHQuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJpYXQiOjE0MzYzODAyNTYsImV4cCI6MTQzNjM4Mzg1Nn0.KNsRtdaRC6Yh0fpvaxqf2lth10_IzuZupx2UrdWgpRfaR5_KelT9BlXRsUfqtRdGl4z0z9BqqaSCpL1OdFdmFNWayeMTBcA3yVlWYbPKvZ_P6X0bSzPsHb308LKQUCiP_kCq4ed3Bc8pTthcf5uW_HrC-73HJeXVtwxHotTUejaAX2D-x8JO03ZYznQA6umCwEsqRaD3Pro0iigZuSYAA5ZmXD_DLKE4WPL9MGmsZad0e9yXPHoF6VyYOLdBC33iG0MaGAbAj2BMcT162XXHcS2klGeQE-UC7gFoscP3HLBf6RHrtwOfCiKlGldb9JR7-gzpsG9AEyimJORRo5CzDg","created":1436380256}';

		//print_r($this->loginGoogle->getUserDataByAccessToken($acessToken));
	}

	public function twitter() {
		$this->loadLibrary('loginTwitter');


		print_r($this->loginTwitter->getUserData());

		/*$accessToken = Array (
			'oauth_token' 			=> '2612061025-7gSiTPYYiHexS1lcgsiZA7puq71pH01WMBr3fog',
			'oauth_token_secret' 	=> 'BvJKu6Caj4Y9zUttGoXUQWv6xY7P5Uf2IvyHvqr4AeDiN',
			'user_id' 				=> '2612061025',
			'screen_name' 			=> 'blogjogosindie',
			'x_auth_expires' 		=> 0);
		
		print_r($this->loginTwitter->getUserDataByAccessToken($accessToken));
		*/
	}

	public function steam() {
		$this->loadLibrary('loginSteam');

		//print_r($this->loginSteam->getUserData());
		print_r($this->loginSteam->getUserDataBySteamID('76561198079065891'));
	}
}