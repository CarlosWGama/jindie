<?php
/**
* 	JIndie
*	@package JIndie
*	@category Library
* 	@author Carlos W. Gama <carloswgama@gmail.com>
* 	@copyright Copyright (c) 2015
* 	@license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
* 	@version   1.0
*/


class FormValidation {
	
	/**
	* Armazena todas as regras e campos que precisam ser verificados
	* @access protected
	* @var array
	*/
	protected $fields = array();

	/**
	* Armazena todos os erros
	* @access protected
	* @var array
	*/
	protected $errors = array();

	/**
	* Usado para acessa os dados enviados via post e get
	* @access protected
	* @var Input
	*/
	protected $input = null;
	
	public function __construct() {
		$this->input = Input::getInstance();
	}

	/**
	* Separa as regras por ";", quando tiver informações extras usar em "[ ]"
	* @uses $this->formValidation->addRule('required', 'REQUIRED', 'required');
	* @uses $this->formValidation->addRule('match', 'MATCH', 'matches[required]');
	* @uses $this->formValidation->addRule('min_length', 'MIN LENGTH', 'minLength[3]');
	* @uses $this->formValidation->addRule('max_length', 'MAX LENGTH', 'maxLength[3]');
	* @uses $this->formValidation->addRule('exac_length', 'EXA LENGTH', 'exactLength[3]');
	* @uses $this->formValidation->addRule('max_value', 'MAX VALUE', 'maxValue[3]');
	* @uses $this->formValidation->addRule('max_value', 'MAX VALUE', 'minValue[3]');
	* @uses $this->formValidation->addRule('exa_value', 'EXA VALUE', 'exactValue[3]');
	* @uses $this->formValidation->addRule('number', 'Number', 'numeric');
	* @uses $this->formValidation->addRule('integer', 'Integer', 'integer');
	* @uses $this->formValidation->addRule('email', 'EMAIL', 'email;unique[usuarios.email]');
	* No caso de Unique dentro fica: [tabela.columna(campoParaVerificacaoIgnorar, valorParaVerificacaoIgnorar)]
	* @uses $this->formValidation->addRule('email', 'EMAIL', 'unique[usuarios.email(id,1)]');
	* @uses $this->formValidation->addRule('cpf', 'CPF', 'cpf');
	* @uses $this->formValidation->addRule('cnpj', 'CNPJ', 'cnpj');
	* @uses $this->formValidation->addRule('arquivo', 'FILE', 'fileRequired;fileExt[doc, png];fileMaxSize[0.5]');
	* @uses $this->formValidation->addRule('data', 'DATE - YYYY-MM-DD', 'date');
	* @uses $this->formValidation->addRule('mes', 'DATE - MM', 'date[m]');
	* @uses $this->formValidation->addRule('data', 'DATE - DD/MM/YYYY', 'date[d/m/Y]');
	* @param string $field
	* @param string $nameField
	* @param string $rule
	* @return FormValidation
	*/
	public function addRule($field, $nameField, $rule) {
		$this->fields[] = array(
			'field' 	=> $field,
			'nameField'	=> $nameField,
			'rule'		=> $rule
		);
		return $this;
	}
	/**
	* $this->formValidation->check()
	* @return bool
	*/
	public function check() {
		$ok = true;

		//Log
		$fieldLogs = "";
		foreach ($this->fields as $key => $field)
			$fieldLogs .= " " . $field['nameField'] . " (" . $field['field'] . "),";
		Log::message(Language::getMessage('log', 'debug_form_validation_start', array('fields' => substr($fieldLogs, 0, -1))), 2);
		//
		
		foreach ($this->fields as $key => $field) {
			$rulesOfField = explode(';', $field['rule']);
			
			foreach ($rulesOfField as $rule) {

				//Extra
				$extra = "";
				if (strpos($rule,'[') !== false) {
					$aux = explode('[', $rule);
					$rule = $aux[0];
					$extra = substr($aux[1], 0, -1);
				}
				
				switch ($rule) {
					case "required":
						if ($this->failRequired($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'required', array('name_field' => $field['nameField']));
						}
						break;
					case "matches":
						if ($this->failMatches($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'matches', array('name_field' => $field['nameField'], 'another_field' => $extra));
						}
						break;
					case "minLength":
						if ($this->failMinLength($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'minLength', array('name_field' => $field['nameField'], 'length' => $extra));							
						}
						break;
					case "maxLength":
						if ($this->failMaxLength($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'maxLength', array('name_field' => $field['nameField'], 'length' => $extra));
						}
						break;
					case "exactLength":
						if ($this->failExactLength($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'exactLength', array('name_field' => $field['nameField'], 'length' => $extra));
						}
						break;
					case "maxValue":
						if ($this->failMaxValue($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'maxValue', array('name_field' => $field['nameField'], 'value' => $extra));
						}
						break;
					case "minValue":
						if ($this->failMinValue($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'minValue', array('name_field' => $field['nameField'], 'value' => $extra));
						}
						break;
					case "exactValue":
						if ($this->failExactValue($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'exactValue', array('name_field' => $field['nameField'], 'value' => $extra));
						}
						break;
					case "numeric":
						if ($this->failIsNumeric($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'numeric', array('name_field' => $field['nameField']));
						}
						break;
					case "integer":
						if ($this->failIsInteger($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'integer', array('name_field' => $field['nameField']));
						}
						break;
					case "email":
						if ($this->failMail($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'email', array('name_field' => $field['nameField']));
						}
						break;
					case "cpf":
						if ($this->failCPF($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'cpf', array('name_field' => $field['nameField']));
						}
						break;
					case "cnpj":
						if ($this->failCNPJ($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'cnpj', array('name_field' => $field['nameField']));
						}
						break;
					case "fileRequired":
						if ($this->failFileRequired($field['field'])) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'fileRequired', array('name_field' => $field['nameField']));
						}
						break;
					case "fileExt":
						if ($this->failFileExt($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'fileExt', array('name_field' => $field['nameField']));
						}
						break;
					case "fileMaxSize":
						if ($this->failFileMaxSize($field['field'], $extra)) {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'fileMaxSize', array('name_field' => $field['nameField'], 'size' => $extra));
						}
						break;
					case "unique":
						if ($this->failUnique($field['field'], $extra))  {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'fileUnique', array('name_field' => $field['nameField']));
						}
						break;
					case "date":
						if ($this->failDate($field['field'], $extra))  {
							$ok = false;
							$this->errors[] = Language::getMessage('form_validation', 'fileDate', array('name_field' => $field['nameField']));
						}
						break;
				}
			}
		}
		//Log
		if ($ok)
			Log::message(Language::getMessage('log', 'debug_form_validation_sucess'), 2);
		else
			Log::message(Language::getMessage('log', 'debug_form_validation_error', array('error' => implode(',', $this->getErrors()))), 2);
		//

		return $ok;
	}
	
	/**
	* @return array
	*/
	public function getErrors() {
		return $this->errors;
	}
	
	//checks
	/**
	* checa se o campo foi preenchido
	* @access protected
	* @param string $field
	*/
	protected function failRequired($field) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));
		return (empty($value));
	}
	
	/**
	* checa se o campo  2 é igual ao campo 1
	* @access protected
	* @param string $field1
	* @param string $field2
	*/
	protected function failMatches($field1, $field2) {
		$value1 = ($this->input->isPost() ? $this->input->post($field1) : $this->input->get($field1));
		$value2 = ($this->input->isPost() ? $this->input->post($field2) : $this->input->get($field2));
		
		//Campo vázio é required
		if (empty($value1))
			return false;
		
		//Campo 2 vázio e campo 1 preenchido
		if (empty($value2))
			return true;
		
		return ($value1 !== $value2);
	}

	/**
	* checa se o campo $field possui tamanho de caracteres menor que $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failMinLength($field, $size) {	
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		return (strlen($value) < $size);
	}
	
	/**
	* checa se o campo $field possui tamanho de caracteres maior que $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failMaxLength($field, $size) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		return (strlen($value) > $size);
	}
	
	/**
	* checa se o campo $field possui tamanho de caracteres igual que $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failExactLength($field, $size) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		return (strlen($value) != $size);
	}
	
	/**
	* checa se o campo $field é menor que $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failMinValue($field, $size) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		//
		return (floatval($value) < floatval($size));
	}
	
	/**
	* checa se o campo $field é maior que $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failMaxValue($field, $size) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		//
		return (floatval($value) > floatval($size));
	}
	
	/**
	* checa se o campo $field é igual a $size
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failExactValue($field, $size) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;
		
		//
		return (floatval($value) != floatval($size));
	}

	/**
	* checa se o campo $field é um número
	* @access protected
	* @param string $field
	*/
	protected function failIsNumeric($field) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;

		//
		$value + 0; //Convert String to numeral se possível
		return !is_numeric($value);
	}
	
	/**
	* checa se o campo $field é um número inteiro
	* @param string $field
	*/
	protected function failIsInteger($field) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;

		//
		return (filter_var($value, FILTER_VALIDATE_INT) !== false ? false : true ); //Convert String to numeral se possível	;
	}

	/**
	* checa se o campo $field é um email valido
	* @access protected
	* @param string $field
	*/
	protected function failMail($field) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($value))
			return false;

		//
		return !filter_var($value, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	* checa se o campo $field é um cpf valido
	* @access protected
	* @param string $field
	*/
	protected function failCPF($field) {
		$cpf = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($cpf))
			return false;
		
	    // Elimina possivel mascara
	    $cpf = preg_replace('/[^0-9]/', '', $cpf);
	    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	     
	    // Verifica se o numero de digitos informados é igual a 11 
	    if (strlen($cpf) != 11) {
	        return true;
	    }
	    // Verifica se nenhuma das sequências invalidas abaixo foi digitada. Caso afirmativo, retorna falso
	    else if ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || 
	        $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
	        return true;

	     // Calcula os digitos verificadores para verificar se o CPF é válido
	     } else {   
	        for ($t = 9; $t < 11; $t++) {
	             
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            $d = ((10 * $d) % 11) % 10;
	            if ($cpf{$c} != $d) {
	                return true;
	            }
	        }
	 
	        return false;
	    }
	}
	
	/**
	* checa se o campo $field é um cnpj valido
	* @access protected
	* @param string $field
	*/
	protected function failCNPJ($field) {
		$cnpj = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));	
		
		if (empty($cnpj))
			return false;

		$cnpj = trim($cnpj);
	    $soma = 0;
	    $multiplicador = 0;
	    $multiplo = 0;
	   
	   
	    # [^0-9]: RETIRA TUDO QUE NÃO É NUMÉRICO,  "^" ISTO NEGA A SUBSTITUIÇÃO, OU SEJA, SUBSTITUA TUDO QUE FOR DIFERENTE DE 0-9 POR "";
	    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
	   
	    if(strlen($cnpj) != 14) 
	        return true;

	    # VERIFICAÇÃO DE VALORES REPETIDOS NO CNPJ DE 0 A 9 (EX. '00000000000000')    
	    for($i = 0; $i <= 9; $i++) {
	        $repetidos = str_pad('', 14, $i);
	       
	        if($cnpj === $repetidos)
	            return TRUE;
	    }
	   
	    # PEGA A PRIMEIRA PARTE DO CNPJ, SEM OS DÍGITOS VERIFICADORES    
	 	$parte1 = substr($cnpj, 0, 12);   
	   
	    # INVERTE A 1ª PARTE DO CNPJ PARA CONTINUAR A VALIDAÇÃO
	    $parte1_invertida = strrev($parte1);
	   
	    # PERCORRENDO A PARTE INVERTIDA PARA OBTER O FATOR DE CALCULO DO 1º DÍGITO VERIFICADOR
	    for ($i = 0; $i <= 11; $i++) {
	        $multiplicador = ($i == 0) || ($i == 8) ? 2 : $multiplicador;
	        $multiplo = ($parte1_invertida[$i] * $multiplicador);
	        $soma += $multiplo;
	        $multiplicador++;
	    }
	   
	    # OBTENDO O 1º DÍGITO VERIFICADOR        
	    $rest = $soma % 11;
	    $dv1 = ($rest == 0 || $rest == 1) ? 0 : 11 - $rest;
	       
	    # PEGA A PRIMEIRA PARTE DO CNPJ CONCATENANDO COM O 1º DÍGITO OBTIDO 
	    $parte1 .= $dv1;
	   
	    # MAIS UMA VEZ INVERTE A 1ª PARTE DO CNPJ PARA CONTINUAR A VALIDAÇÃO 
	    $parte1_invertida = strrev($parte1);
	    $soma = 0;
	   
	    # MAIS UMA VEZ PERCORRE A PARTE INVERTIDA PARA OBTER O FATOR DE CALCULO DO 2º DÍGITO VERIFICADOR       
	    for ($i = 0; $i <= 12; $i++) {
	        $multiplicador = ($i == 0) || ($i == 8) ? 2 : $multiplicador;
	        $multiplo = ($parte1_invertida[$i] * $multiplicador);
	        $soma += $multiplo;
	        $multiplicador++;
	    }
	       
	    # OBTENDO O 2º DÍGITO VERIFICADOR
	    $rest = $soma % 11;
	    $dv2 = ($rest == 0 || $rest == 1) ? 0 : 11 - $rest;
	   
	    # AO FINAL COMPARA SE OS DÍGITOS OBTIDOS SÃO IGUAIS AOS INFORMADOS (OU A SEGUNDA PARTE DO CNPJ)   
	    return ($dv1 == $cnpj[12] && $dv2 == $cnpj[13]) ? false : true;
	    
	}

	/**
	* checa se o campo $field foi enviado um arquivo
	* @access protected
	* @param string $field
	*/
	protected function failFileRequired($field) {
		$file = $this->input->file($field);
		return empty($file);
	}

	/**
	* checa se o campo $field possui um arquivo com determinado tipo de extensão
	* @access protected
	* @param string $field
	* @param array $ext
	*/
	protected function failFileExt($field, $ext) {
		$file = $this->input->file($field);
		$exts = explode(',', $ext);

		if (empty($file))
			return false;

		if (empty($exts))
			return true;
		
		$exts = array_map('strtoupper', $exts);
		$exts = array_map('trim', $exts);
		$extFile = explode('.', $file['name']);
		$extFile = end($extFile);
		return !(in_array(strtoupper($extFile), $exts));
	}

	/**
	* checa se o campo $field possui um tamanho maior que $size MegaBytes
	* @access protected
	* @param string $field
	* @param int $size
	*/
	protected function failFileMaxSize($field, $size) {
		$file = $this->input->file($field);
		
		if (empty($file))
			return false;		

		if (empty($size) || !is_numeric($size))
			return true;

		$fileSize = ($file['size'] / 1048576);
		return ($fileSize > $size);
	}

	/**
	* checa se o campo $field é unico ou se já existe outro valor igual armazenado no banco
	* @access protected
	* @param string $field
	* @param string $extra
	*/
	protected function failUnique($field, $extra) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));

		if (empty($value))
			return false;

		//Ignorar
		$ignore = "";
		if (strpos($extra,'(') !== false) {
			$aux = explode('(', $extra);
			$extra = $aux[0];
			$ignore = substr($aux[1], 0, -1);

			@$ignore = explode(',', $ignore);

			if (count($ignore) != 2)
				return false;
		}

		@list($table, $column) = explode('.', $extra);

		if (empty($table) || empty($column)) 
			return false;

		require_once(dirname(__FILE__).'/Database.php');
		$db = JI_Database::getInstance();	

		if (!empty($ignore)) 
			$db->where($ignore[0], $ignore[1], "!=");

		$db->where($column, $value);
		return $db->has($table);
	}

	/**
	* checa se o campo $field possui uma data válida. O Campo Extra serve para recuperar o formato da data para formata-lo para o padrão Y-m-d
	* @access protected
	* @param string $field
	* @param string $format
	*/
	protected function failDate($field, $extra) {
		$value = ($this->input->isPost() ? $this->input->post($field) : $this->input->get($field));

		if (empty($value))
			return false;

		try {
			if (empty($extra)) $format = 'Y-m-d';
			else $format = $extra;
			$d = DateTime::createFromFormat($format, $value);
    		return !($d && $d->format($format) == $value);
		} catch (Exception $ex) {
			return true;
		}
	}
}

//Helpers
/**
* Caso o dado tenha sido passado de uma página para outra, ele será usado novamente no campo input na página atual
* @access public
* @param string $field
* @return string
*/ 
if (!function_exists('form_set_text')) {
	function form_set_text($field) {
		$input = Input::getInstance();
		if ($input->isPost())
			return $input->post($field);
		return $input->get($field);
	}
}

/**
* Caso o dado tenha sido selecionado no formulário enviado, ele virá como marcado também na página atual
* @access public
* @param string $value
* @param string $field
* @param bool $isCheck 		//Se já vem marcado
* @return string
*/ 
if (!function_exists('form_set_check')) {
	function form_set_check($value, $field, $isCheck = false) {
		$input = Input::getInstance();
		if ($input->isPost())
			$data = $input->post($field);
		else
			$data = $input->get($field);

		if (empty($data))  {
			if ($isCheck)
				return 'checked="checked"';
			return;
		}

		if ($data == $value)
			return 'checked="checked"';
	}
}

/**
* Caso o dado tenha sido selecionado no formulário enviado com multiplos checkbox, ele virá como marcado também na página atual
* @access public
* @param string $value
* @param string $field
* @param bool $isCheck 		//Se já vem marcado
* @return string
*/ 
if (!function_exists('form_set_multicheck')) {
	function form_set_multicheck($value, $field, $isCheck = false) {
		$input = Input::getInstance();
		if ($input->isPost())
			$data = $input->post($field);
		else
			$data = $input->get($field);

		if (empty($data))  {
			if ($isCheck)
				return 'checked="checked"';
			return;
		}

		foreach ($data as $v) {
			if ($v == $value)
				return 'checked="checked"';
		}
	}
}

/**
* Caso o dado tenha sido selecionado no formulário enviado com select, ele virá como marcado também na página atual
* @access public
* @param string $value
* @param string $field
* @param bool $isSelected 		//Se já vem marcado
* @return string
*/ 
if (!function_exists('form_set_select')) {
	function form_set_select($value, $field, $isSelected = false) {
		$input = Input::getInstance();
		if ($input->isPost())
			$data = $input->post($field);
		else
			$data = $input->get($field);

		if (empty($data))  {
			if ($isSelected)
				return 'selected="selected"';
			return;
		}

		if ($data == $value)
			return 'selected="selected"';
	}
}

/**
* Caso o dado tenha sido selecionado no formulário enviado com multiplo select, ele virá como marcado também na página atual
* @access public
* @param string $value
* @param string $field
* @param bool $isSelected 		//Se já vem marcado
* @return string
*/ 
if (!function_exists('form_set_multiselect')) {
	function form_set_multiselect($value, $field, $isSelected = false) {
		$input = Input::getInstance();
		if ($input->isPost())
			$data = $input->post($field);
		else
			$data = $input->get($field);

		if (empty($data))  {
			if ($isSelected)
				return 'selected="selected"';
			return;
		}

		foreach ($data as $v) {
			if ($v == $value)
				return 'selected="selected"';
		}
	}
}