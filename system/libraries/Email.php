<?php
/**
* 	JIndie
*	@package JIndie
*	@category Library
*	@author PHPMailer 		//Biblioteca por trás
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/

require_once(dirname(__FILE__).'/PHPMailer/PHPMailerAutoload.php');

class Email {

	/**
	* Tipo de envio: smtp | sendmail | mail
	* @var string
	*/
	public $protocol = 'smtp';					
	
	/**
	* Email para usar no envio smtp
	* @var string
	*/
	public $user = '';						

	/**
	* Senha para usar no envio smtp
	* @var string
	*/
	public $pass = '';						

	/**
	* Host para usar no envio smtp
	* @var string
	*/
	public $host = 'smtp.gmail.com';			

	/**
	* Porta int usar no envio smtp
	* @var string
	*/
	public $port = 587;						

	/**
	* Protocolo de segurança para usar no envio smtp (tls | ssl)
	* @var string
	*/
	public $secure = 'tls';					

	/**
	* Fazer debug no envio do email (0 - Sem | 1 - Cliente | 2 - Cliente e Servidor)
	* @var int
	*/
	public $smtpDebug = 0;						

	/**
	* Remetente (nome, email)
	* @access private
	* @var array
	*/
	private $from = array();					

	/**
	* Para quem responder  (nome, email)
	* @access private
	* @var array
	*/
	private $replyTo = array();					

	/**
	* Destinatários Matriz [(nome, email),(nome, email)]
	* @access private
	* @var array
	*/
	private $to = array();					

	/**
	* Assunto
	* @access protected
	* @var string
	*/
	protected $subject	= "";						

	/**
	* Mensagem
	* @access protected
	* @var string
	*/
	protected $msg	= "";						

	/**
	* Anexos (arquivo, nome do arquivo)
	* @access protected
	* @var string
	*/
	protected $attachments = array();					//Anexos

	/**
	* @access private
	* @var PHPMailer
	*/
	private $mail = null;

	public function __construct() {
		$this->mail = new PHPMailer;

		$config = Config::getConfiguration('email');
		$this->setConfig($config);
	}

	/**
	* @param array $config
	*/
	public function setConfig($config) {
		if (isset($config['protocol'])) 	$this->protocol = $config['protocol'];
		if (isset($config['user']))			$this->user = $config['user'];
		if (isset($config['pass']))			$this->pass = $config['pass'];
		if (isset($config['host']))			$this->host = $config['host'];
		if (isset($config['port']))			$this->port = $config['port'];
		if (isset($config['secure']))		$this->secure = $config['secure'];
		if (isset($config['smtpDebug']))	$this->smtpDebug = $config['smtpDebug'];
		if (isset($config['subject']))		$this->subject = $config['subject'];
		if (isset($config['msg']))			$this->msg = $config['msg'];
	}

	/**
	* @param string $subject
	*/
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	* @param string $message
	*/
	public function setMessage($message) {
		$this->msg = $message;
	}

	/**
	* @uses $email->setFrom('carloswgama@gmail.com')
	* @uses $email->setFrom('carloswgama@gmail.com', 'Carlos')
	* @uses $email->setFrom($usuario);
	* @param string|IUser $email
	* @param string $name
	*/
	public function setFrom($email, $name = '') {
		if ($email instanceof JIndie\Model\IUser) {
			$name = $email->getName();
			$email = $email->getEmail();
		}

		if (empty($name)) $name = $email;
		$this->from = array('email' => $email, 'name' => $name);
	}

	/**
	* @uses $email->setReplyTo('carloswgama@gmail.com')
	* @uses $email->setReplyTo('carloswgama@gmail.com', 'Carlos')
	* @uses $email->setReplyTo($usuario);
	* @param string|IUser $email
	* @param string $name
	*/
	public function setReplyTo($email, $name = '') {
		if ($email instanceof JIndie\Model\IUser) {
			$name = $email->getName();
			$email = $email->getEmail();
		}

		if (empty($name)) $name = $email;
		$this->replyTo = array('email' => $email, 'name' => $name);
	}

	/**
	* @uses $email->addTo('carloswgama@gmail.com')
	* @uses $email->addTo('carloswgama@gmail.com', 'Carlos')
	* @uses $email->addTo($usuario);
	* @param string|IUser $email
	* @param string $name
	*/
	public function addTo($email, $name = '') {
		if ($email instanceof JIndie\Model\IUser) {
			$name = $email->getName();
			$email = $email->getEmail();
		}

		if (empty($name)) $name = $email;
		$this->to[] = array('email' => $email, 'name' => $name);
	}

	/**
	* @uses $email->addAttachment('/dir/dir2/read-me.txt');
	* @uses $email->addAttachment('/dir/dir2/read-me.txt', 'read.txt');
	* @param string $file
	* @param string $name
	*/
	public function addAttachment($file, $name = '') {
		$extFile = explode('.', $file);
		$extFile = end($extFile);

		if (!empty($name))
			$name .= "." . $extFile;

		$this->attachments[] = array('file' => $file, 'name' => $name);
	}

	/**
	* @return bool
	*/
	public function send() {

		//Log
		Log::message(Language::getMessage('log', 'debug_email_start', array('protocol' => $this->protocol)), 2);
		

		switch ($this->protocol) {
			case "smtp":
				$this->mail->isSMTP();
				$this->mail->SMTPDebug = $this->smtpDebug;
				$this->mail->Debugoutput = 'html';
				$this->mail->Host = $this->host;
				$this->mail->Port = $this->port;
				$this->mail->SMTPSecure = $this->secure;
				$this->mail->SMTPAuth = true;
				$this->mail->Username = $this->user;
				$this->mail->Password = $this->pass;
				break;
			case "sendmail":
				$this->mail->isSendmail();
				break;
			case "mail":
			default:
				break;
		}

		//From
		$this->mail->setFrom($this->from['email'],$this->from['name']);
		
		//Reply
		if (!empty($this->replyTo))
			$this->mail->addReplyTo($this->replyTo['email'], $this->replyTo['name']);

		//Adress
		$logTo = "";
		foreach ($this->to as $to) {
			$logTo .=  " " . $to['email'] . "(" .  $to['name'] . "),";
			$this->mail->addAddress($to['email'], $to['name']);
		}
		Log::message(Language::getMessage('log', 'debug_email_to', array('to' => substr($logTo, 0, -1))), 2);


		//Subject
		$this->mail->Subject = $this->subject;

		//MSG
		$this->mail->msgHTML($this->msg);
		
		//Attachments
		foreach ($this->attachments as $attachment) {
			if (empty($attachment['name']))
				$this->mail->addAttachment($attachment['file'], $attachment['name']);
			else
				$this->mail->addAttachment($attachment['file'], $attachment['name']);
		}

		$sucess = $this->mail->send();

		if ($sucess)
    		Log::message(Language::getMessage('log', 'debug_email_success'), 2);
		else 
			Log::message(Language::getMessage('log', 'debug_email_error', array('error' => $this->getError())), 2);
    		
    	return $sucess;
	}

	/**
	* Retorna mensagem de erro, caso exista
	* @return sring
	*/
	public function getError() {
		return $this->mail->ErrorInfo;
	}

	/**
	* Apaga todos os dados anteriores
	*/
	public function clear() {
		$this->mail = new PHPMailer;

		$this->replyTo = array();
		$this->from = array();
		$this->to = array();
		$this->attachments = array();
		$this->subject = '';
		$this->msg = '';
	}
}
