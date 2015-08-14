<?php
/**
*   JIndie
*   @package JIndie
*   @category Library
*   @author Carlos W. Gama <carloswgama@gmail.com>
*   @copyright Copyright (c) 2015
*   @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
*   @version   1.0
*/

class Chat {
	
	/**
	* Tempo usado para verificar se existe novas mensagens
	* @access protected
	* @var int
	*/
	protected $timeReload = 0;

	/**
	* link onde buscar informações de novas mensagens
	* @access protected
	* @var string
	*/
	protected $linkReload;

	/**
	* link para onde enviar nova mensagem
	* @access protected
	* @var string
	*/
	protected $linkSubmit;

	/**
	* novas mensagens para serem adicionados
	* @access protected
	* @var array
	*/
	protected $messages = array();

	/**
	* Formato da dataa ser exibido
	* @access protected
	* @var string
	*/
	protected $formatDate = 'H:i:s';

	/**
	* @param string $formatDate
	*/
	public function setFormatDate($formatDate) {
		$this->formatDate = $formatDate;

		Log::message(Language::getMessage('log', 'debug_chat_format_date', array('format' => $this->formatDate)), 2);
	}

	/**
	* @param int $time
	*/
	public function setTimeReload($time) {
		$time + 0;
		if (is_int($time)) {
			$time *= 1000; //transform to miliseconds
			$this->timeReload = $time;	
		}

		Log::message(Language::getMessage('log', 'debug_chat_time', array('time' => $this->timeReload)), 2);
		
	}

	/**
	* @param string $link
	*/
	public function setLinkReload($link) {
		if (substr($link, 0, 4) != "http")
			$link = 'http://' . $link;

		$this->linkReload = $link;

		Log::message(Language::getMessage('log', 'debug_chat_link_reload', array('link' => $this->linkReload)), 2);
	}

	/**
	* @param string $link
	*/
	public function setLinkSubmit($link) {
		if (substr($link, 0, 4) != "http")
			$link = 'http://' . $link;

		$this->linkSubmit = $link;	

		Log::message(Language::getMessage('log', 'debug_chat_link_submit', array('link' => $this->linkSubmit)), 2);
	}

	/**
	* Adicion as novas mensagens que serão transformadas em json no getJsonMessages
	* @uses $chat->addMessage($usuario, 'hello');
	* @uses $chat->addMessage('carlos', 'hello', 'link-imagem', '2015-01-01');
	* @uses $chat->addMessage('carlos', 'hello', 'link-imagem');
	* @uses $chat->addMessage('carlos', 'hello', 'link-imagem');
	* @uses $chat->addMessage('carlos', 'hello');
	* @param string|IUser $name
	* @param string $comment
	* @param string $avatar
	* @param string $date
	*/
	public function addMessage($name, $comment, $avatar = null, $date = null) {
		if ($name instanceof JIndie\Model\IUser) {
			$avatar = $name->getAvatar();
			$name = $name->getName();
		}


		$this->messages[] = array(
			'name'			=> $name,
			'comment'		=> $comment,
			'avatar'		=> $avatar,
			'date'			=> $this->formatDate($date)
		);

		Log::message(Language::getMessage('log', 'debug_chat_new_message', array('name' => $name, 'comment' => $comment, 'avatar' => $avatar, 'date' => $this->formatDate($date))), 2);
	}
	/**
	* Retorna as mensagens em formato Json
	* @return string |json
	*/
	public function getJsonMessages() {
		$json = array(
			'comments' 		=> $this->messages,
			'last_check'	=> date('Y-m-d H:i:s')
		);

		Log::message(Language::getMessage('log', 'debug_chat_json', array('json' => json_encode($json))), 2);
		return json_encode($json);
	}

	/**
	* Gera o HTML do Chat
	* @return string
	*/
	public function getChat() {
		$linkReload = $this->linkReload;
		$linkSubmit = $this->linkSubmit;
		$timeReload = $this->timeReload;


		if (!empty($this->messages))
			$comments = $this->getJsonMessages();

		ob_start();
		require(VIEWS_PATH.'library/chat.php');
		$html = ob_get_clean();

		Log::message(Language::getMessage('log', 'debug_chat_create', array('link_reload' => $linkReload, 'link_submit' => $linkSubmit, 'time_reload' => $timeReload)), 2);
		
		return $html;
	}

	/**
	* Transforma o campo date para o novo formato de data
	* @access protected
	* @param string $date
	* @return string
	*/
	protected function formatDate($date) {
		if (!empty($date))
			return date($this->formatDate, strtotime($date));
		return null;
	}


}