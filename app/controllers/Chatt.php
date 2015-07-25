<?php


class Chatt extends Controller {

	public function __construct() {
		parent::__construct();
		$this->loadLibrary('chat');
	}
	
	public function index() {

		$this->chat->setTimeReload(10);
		$this->chat->setLinkReload('http://local.jindie.com.br/chatt/reloadChat');
		$this->chat->setLinkSubmit('http://local.jindie.com.br/chatt/submitChat');

		$this->chat->addMessage('Carlos', 'oi', 'https://lh4.googleusercontent.com/-UcdMsFEkE4E/AAAAAAAAAAI/AAAAAAAAAAA/QQU9brpaDTc/s128-c-k/photo.jpg', date('Y-m-d H:i:s'));
		$this->chat->addMessage('Mylana', 'oi!!', 'https://lh6.googleusercontent.com/-QWQkDptS_D8/AAAAAAAAAAI/AAAAAAAAAAA/WILcTanO7Yc/s128-c-k/photo.jpgs', date('Y-m-d H:i:s'));

		$dados['chat'] = $this->chat->getChat();

		$this->view->render('chat', $dados);
	}

	public function submitChat() {
		$msg = $this->input->post('message');

		$this->chat->addMessage('Carlos', $msg, 'https://lh4.googleusercontent.com/-UcdMsFEkE4E/AAAAAAAAAAI/AAAAAAAAAAA/QQU9brpaDTc/s128-c-k/photo.jpg', date('Y-m-d H:i:s'));
		$this->reloadChat();
	}

	public function reloadChat() {
		$msg = $this->input->post('last-check');
		$this->chat->addMessage('Carlos', $msg, 'https://lh4.googleusercontent.com/-UcdMsFEkE4E/AAAAAAAAAAI/AAAAAAAAAAA/QQU9brpaDTc/s128-c-k/photo.jpg', date('Y-m-d H:i:s'));	
		echo $this->chat->getJsonMessages();
	}	
}