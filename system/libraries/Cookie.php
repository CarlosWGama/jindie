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

class Cookie {

    /**
    * Constantes de duração por dia, semana, mes, semestre, ano e vida inteira
    */
    const Session = null;
    const OneDay = 86400;
    const SevenDays = 604800;
    const ThirtyDays = 2592000;
    const SixMonths = 15811200;
    const OneYear = 31536000;
    const Lifetime = -1; // 2030-01-01 00:00:00

    /**
    * local onde fica armazenado as informações do cookie
    * @access protected
    * @var string
    */
    protected $path = "/";

    /**
    * dominio que o cookie armazena
    * @access protected
    * @var string
    */
    protected $domain = false;

    /**
    * Duração dos dados
    * @access protected
    * @var int
    */
    protected $expiry = -1;

    /**
    * @param string $time
    */
    public function setExpiry($time) {
    	switch(strtoupper($time)) {
    		case "DAY":
    			$this->expiry = Cookie::OneDay;
    			break;
    		case "WEEK":
    			$this->expiry = Cookie::SevenDays;
    			break;
    		case "MONTH":
    			$this->expiry = Cookie::ThirtyDays;
    			break;
    		case "SEMESTER":
    			$this->expiry = Cookie::SixMonths;
    			break;
    		case "YEAR":
    			$this->expiry = Cookie::OneYear;
    			break;
    		case "FOREVER":
    			$this->expiry = Cookie::Lifetime;
    			break;
    		case "SESSION":
    			$this->expiry = Cookie::Session;
    			break;
    	}
    }

    /**
    * @param string $path
    */
    public function setPath($path) {
    	$this->path = $path;
    }

    /**
    * @param string $domain
    */
    public function setDomain($domain) {
    	$this->domain = $domain;
    }

    /**
    * checa se uma variavel existe no cookie
    * @param string $name
    * @return bool
    */
    public function exists($name) {
        return isset($_COOKIE[$name]);
    }

    /**
    * checa se uma variavel está vázia no cooki
    * @param string $name
    * @return bool
    */
    public function isEmpty($name) {
        return empty($_COOKIE[$name]);
    }

    /**
    * retorna a variavel
    * @param string $name
    * @param mix $default   //Dado que recebe caso não tenha informação no cookie
    * @return mix
    */
    public function get($name, $default = FALSE) {
        return (isset($_COOKIE[$name]) ? unserialize(base64_decode($_COOKIE[$name])) : $default);
    }

    /**
    * @uses $cookie->set('nome', 'Carlos');
    * @param string $name
    * @param mix $value
    */
    public function set($name, $value) {

        $value = serialize($value);
        $value = base64_encode($value);

        $retval = false;
        if (!headers_sent()) {
            if ($this->domain === false)
                $this->domain = $_SERVER['HTTP_HOST'];

            $expiry = 0;
            if ($this->expiry === -1)
                $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
            elseif (is_numeric($this->expiry))
                $expiry = (time() + $this->expiry);
            else
                $expiry = strtotime($this->expiry);
            
            $retval = @setcookie($name, $value, $expiry, $this->path, $this->domain);
            if ($retval) {
                $_COOKIE[$name] = $value;
            }
        }
        return $retval;
    }

    /**
    * @uses $cookie->delete('nome');
    * @param string $name
    * @param bool $remove_from_global
    * @return bool
    */
    public function delete($name, $remove_from_global = false) {
        $retval = false;
        if (!headers_sent()) {
            if ($this->domain === false)
                $this->domain = $_SERVER['HTTP_HOST'];
            $retval = setcookie($name, '', time() - 3600, $this->path, $this->domain);

            if ($remove_from_global)
                unset($_COOKIE[$name]);
        }
        return $retval;
    }
}
