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
		
	}

	/**
	* @param string $link
	*/
	public function setLinkReload($link) {
		if (substr($link, 0, 4) != "http")
			$link = 'http://' . $link;

		$this->linkReload = $link;
	}

	/**
	* @param string $link
	*/
	public function setLinkSubmit($link) {
		if (substr($link, 0, 4) != "http")
			$link = 'http://' . $link;

		$this->linkSubmit = $link;	
	}

	/**
	* Adicion as novas mensagens que serão transformadas em json no getJsonMessages
	* @param string $name
	* @param string $comment
	* @param string $avatar
	* @param string $date
	*/
	public function addMessage($name, $comment, $avatar = null, $date = null) {
		$this->messages[] = array(
			'name'			=> $name,
			'comment'		=> $comment,
			'avatar'		=> $avatar,
			'date'			=> $this->formatDate($date)
		);
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