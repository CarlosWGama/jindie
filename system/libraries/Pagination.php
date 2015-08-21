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
class Pagination {

	/**
	* URL Base do link
	* @access protected
	* @var string
	*/
	protected $url	 = '';		

	/**
	* Total de linhas no resultado
	* @access protected
	* @var int
	*/
	protected $totalItens = 0;

	/**
	 * Quantos números são exibidos na paginação
	 * @access protected
	 * @var	int
	 */
	protected $numLinks = 2;

	/**
	 * Quantos itens existem por página (Total/itens = número de páginas)
	 * @access protected
	 * @var	int
	 */
	public $itensPerPage = 10;

	/**
	 * Página atual
	 * @access protected
	 * @var	int
	 */
	public $currentPage = 0;

	/**
	 * Simbolo que representa pular para primeira página
	 * @access protected
	 * @var	string
	 */
	protected $firstLinkLabel = '&lsaquo; &lsaquo;';

	/**
	 * Simbolo que representa pular para próxima página
	 * @access protected
	 * @var	string
	 */
	protected $nextLinkLabel = '&gt;';

	/**
	 * Simbolo que representa voltar para página anterior
	 * @access protected
	 * @var	string
	 */
	protected $prevLinkLabel = '&lt;';

	/**
	 * Simbolo que representa pular para última página
	 * @access protected
	 * @var	string
	 */
	protected $lastLinkLabel = '&rsaquo; &rsaquo;';

	/**
	 * Tag que usada no inicio do número da página atual
	 * @access protected
	 * @var	string
	 */
	protected $currentTagOpen = '<strong>';

	/**
	 * Tag que usada no final do número da página atual
	 * @access protected
	 * @var	string
	 */
	protected $currentTagClose = '</strong>';


	/**
	 * Tag que usada no inicio do link de uma página
	 * @access protected
	 * @var	string
	 */
	protected $tagOpen = '';

	/**
	 * Tag que usada no final do link de uma página
	 * @access protected
	 * @var	string
	 */
	protected $tagClose = '';

	/**
	 * Exibe os números das páginas
	 * @access protected
	 * @var	bool
	 */
	protected $displayPages = TRUE;	

	/**
	 * Reuse query string flag
	 *
	 * @var	bool
	 */
	protected $reuseQueryString = FALSE;


	// --------------------------------------------------------------------

	/**
	 * Configura as variaveis
	 * @uses $pag->config('currentPage', 1);
	 * @uses $pag->config(array('currentPage' => 1));
	 * @param	string $key
	 * @param	mix $value
	 * @return	Pagination
	 */
	public function config($key, $val = null) {
		if (is_array($key)) {
			foreach ($key as $key2 => $value)
				$this->config($key2, $value);

		} else {
			if (property_exists($this, $key)) 
				$this->$key = $val;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Cria a paginação
	 * @return	string
	 */
	public function create() {
		// Se não for definido o total de itens ou número de páginas...
		if ($this->totalItens == 0 OR $this->itensPerPage == 0)
			return '';
		
		// Calcúla o número de páginas
		$numPages = (int) ceil($this->totalItens / $this->itensPerPage);

		// Se existir apenas uma página
		if ($numPages === 1) 
			return '';

		// Checa se foi informado o número de tens que será exibido
		$this->numLinks = (int) $this->numLinks;

		if ($this->numLinks < 0) 
			return '';

		// Mantem os itens enviados via get ao final do link
		if ($this->reuseQueryString === TRUE) {
			$get = $_GET;
		}
		else 
			$get = array();

		// Verifica o item separado
		$url = trim($this->url);

		$query_string = '';
		$query_string_sep = (strpos($url, '?') === FALSE) ? '?' : '&amp;';

		// Adiciona os itens ao final do link da página
		if ( ! empty($get)) {
			$query_string = $query_string_sep.http_build_query($get);
		}

		$first_url = $url.$query_string;
		
		$url = rtrim($url, '/').'/';

		// Se não há nenhuma página definida então setá como primeira página
		if ( ! filter_var($this->currentPage, FILTER_VALIDATE_INT) OR ($this->currentPage === 0)) 
			$this->currentPage = 1;
		else //Senão, seta a página definida
			$this->currentPage = (int) $this->currentPage;
		

		// Se a página atual for maior que o total de páginas muda para a ultima página,
		if ($this->currentPage > $numPages) 
			$this->currentPage = $numPages;
		
		$uri_page_number = $this->currentPage;

		// Calcula quantas páginas serão exibidas
		$start	= (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
		$end	= (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;

		// Inicia a criação da paginação
		$output = '';

		// Cria Primeira página
		if ($this->firstLinkLabel !== FALSE && $this->currentPage > ($this->numLinks + 1 + ! $this->numLinks)) 
			$output .= $this->tagOpen.'<a href="'.$first_url.'">'.$this->firstLinkLabel.'</a>'.$this->tagClose;
		
		// Cria Link para voltar
		if ($this->prevLinkLabel !== FALSE && $this->currentPage !== 1) {
			$i = $uri_page_number - 1;

			if ($i === 1) 
				$output .= $this->tagOpen.'<a href="'.$first_url.'">'.$this->prevLinkLabel.'</a>'.$this->tagClose;
			else 
				$output .= $this->tagOpen.'<a href="'.$url.$i.$query_string.'">'.$this->prevLinkLabel.'</a>'.$this->tagClose;
		}

		// Exibe as páginas numeradas
		if ($this->displayPages !== FALSE) {
			
			for ($loop = $start -1; $loop <= $end; $loop++)	{
				$i = $loop;

				if ($i >= 1) {
					if ($this->currentPage === $loop) // Current page
						$output .= $this->currentTagOpen.$loop.$this->currentTagClose;

					elseif ($i === 1) // First page
						$output .= $this->tagOpen.'<a href="'.$first_url.'">'.$loop.'</a>'.$this->tagClose;

					else //Normal page
						$output .= $this->tagOpen.'<a href="'.$url.$i.$query_string.'">'.$loop.'</a>'.$this->tagClose;
				}
			}
		}

		// Cria o link para próxima página
		if ($this->nextLinkLabel !== FALSE && $this->currentPage < $numPages) {
			$i = $this->currentPage + 1;
			$output .= $this->tagOpen.'<a href="'.$url.$i.$query_string.'">'.$this->nextLinkLabel.'</a>'.$this->tagClose;
		}

		// Cria o Link última página
		if ($this->lastLinkLabel !== FALSE && ($this->currentPage + $this->numLinks + ! $this->numLinks) < $numPages) {
			$i = $numPages;
			$output .= $this->tagOpen.'<a href="'.$url.$i.$query_string.'">'.$this->lastLinkLabel.'</a>'.$this->tagClose;
		}

		// Remove double slashes
		$output = preg_replace('#([^:])//+#', '\\1/', $output);

		return $output;
	}
}
