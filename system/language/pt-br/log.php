<?php

$language = array(
	'debug_start' 						=> "DEBUG COMEÇOU",
	'debug_finish'						=> "DEBUG TERMINOU",
	'debug_load_page'					=> "A página demorou: %TIME% segundos",
	
	'debug_filter'						=> "FILTER: O filtro '%FILTER%' foi carregado",
	'debug_filter_start'				=> "FILTER: Iniciando o filtro '%FILTER%'",
	'debug_filter_not_filter'			=> "FILTER: A classe '%FILTER%' não herda a classe Filter",
	'debug_filter_fail'					=> "FILTER: Não foi possível executar o filtro '%FILTER%'",

	'debug_input_url'					=> "INPUT: URL identificada: '%URL%'",
	'debug_input_folder'				=> "INPUT: Pastas dentro de Controller identificados na URL: '%FOLDER%'",
	'debug_input_controller'			=> "INPUT: Controller identifcado na URL: '%CONTROLLER%'",
	'debug_input_method'				=> "INPUT: Método identificado na URL: '%METHOD%'",
	'debug_input_args'					=> "INPUT: Argumentos identificado na URL: '%ARGS%'",
	'debug_input_get'					=> "INPUT: Foram passado os seguintes dados como GET: %DATA% ",
	'debug_input_post'					=> "INPUT: Foram passado os seguintes dados como POST: %DATA% ",
	'debug_input_file'					=> "INPUT: Foram passado os seguintes dados como FILE: %DATA% ",

	'debug_controller_start'			=> "CONTROLLER: Iniciando o Controller '%CONTROLLER%'",
	'debug_controller_run'				=> "CONTROLLER: O método '%METHOD%' do controller '%CONTROLLER%' foi executado (sem argumentos)",
	'debug_controller_run_args'			=> "CONTROLLER: O método '%METHOD%' do controller '%CONTROLLER%' foi executado com os argumentos: '%ARGS%'",
	'debug_controller_not_controller'	=> "CONTROLLER: A classe '%CONTROLLER%' não herda a classe Controller",
	'debug_controller_not_exists'		=> "CONTROLLER: A classe '%CONTROLLER%' não existe",
	'debug_controller_method_not_exists'=> "CONTROLLER: O metódo '%METHOD%' não existe na classe '%CONTROLLER%'",
	'debug_controller_method_fail'		=> "CONTROLLER: Não foi possível executar o metódo '%METHOD%'. Causa: %CAUSE%",

	'debug_loader_model_try'			=> "LOADER: Class '%CLASS%' está tentando carregar o Model '%MODEL%'",
	'debug_loader_model_load'			=> "LOADER: Model '%MODEL%' carregado com o nome '%MODEL_NAME%'",
	'debug_loader_model_fail'			=> "LOADER: Não foi possível carregar o Model '%MODEL%'",

	'debug_loader_library_try'			=> "LOADER: Class '%CLASS%' está tentando carregar a Biblioteca '%LIBRARY%'",
	'debug_loader_library_load'			=> "LOADER: Biblioteca '%LIBRARY%' carregada com o nome '%LIBRARY_NAME%'",
	'debug_loader_library_fail'			=> "LOADER: Não foi possível carregar a Biblioteca '%LIBRARY%'",

	'debug_form_validation_start'		=> "FORM VALIDATION: Iniciado validação do formulário com os campos: %FIELDS%",
	'debug_form_validation_sucess'		=> "FORM VALIDATION: O formulário foi executado com sucessos",
	'debug_form_validation_error'		=> "FORM VALIDATION: O formulário falhou na verificação. Erros: %ERROR%",

	'debug_email_start'					=> "EMAIL: Iniciada tentativa de enviar email usando protocolo %PROTOCOL%",
	'debug_email_to'					=> "EMAIL: Enviando email para: %TO%",
	'debug_email_success'				=> "EMAIL: Email enviado com sucesso",
	'debug_email_error'					=> "EMAIL: Não foi possível enviar email. Erro: %ERROR%",
	'debug_email_debug'					=> "EMAIL: Email Debug: %DEBUG%",

	'debug_session_start'				=> "SESSION: Sessão iniciada",
	'debug_session_regenerate'			=> "SESSION: Sessão renovada",
	'debug_session_prevent_hijacking'	=> "SESSION: Sessão será regerada para evitar Hijacking",
	'debug_session_validate_fail'		=> "SESSION: Sessão obsoleta, descartada",

	'debug_cache_set'					=> "CACHE: Salvando dados em cache %DATA%",
	'debug_cache_get'					=> "CACHE: Tentando recuperar os dados em cache no arquivo '%FILE%'",
	'debug_cache_get_expiry'			=> "CACHE: Não foi possível recuperar os dados. Cache expirou em %EXPIRY%",
	'debug_cache_get_success'			=> "CACHE: Dados recuperado com sucesso. Dados %DATA%",
	'debug_cache_file_not_exists'		=> "CACHE: Não existe nenhum arquivo em '%FILE%'",

	'debug_database_old_instance'		=> "DATABASE: Variavel de banco iniciado com Instância já ativa",
	'debug_database_new_instance'		=> "DATABASE: Variavel de banco iniciado com nova Instância",

	'debug_facebook_set_credentials'	=> "LOGIN FACEBOOK: Setando as Credenciais: AppID(%APPID%) e AppSecret(%APPSECRET%)",
	'debug_facebook_get_url'			=> "LOGIN FACEBOOK: URL para login foi gerada (%URL%)",
	'debug_facebook_get_data'			=> "LOGIN FACEBOOK: Foram recuperado os seguintes dados após login: %DATA%",
	
	'debug_google_set_credentials'		=> "LOGIN GOOGLE: Setando as Credenciais: clientID(%CLIENTID%) e clientSecret(%CLIENTSECRET%)",
	'debug_google_get_url'				=> "LOGIN GOOGLE: URL para login foi gerada (%URL%)",
	'debug_google_get_data'				=> "LOGIN GOOGLE: Foram recuperado os seguintes dados após login: %DATA%",

	'debug_steam_set_credentials'		=> "LOGIN STEAM: Setando as Credenciais: apiKey(%APIKEY%) e domain(%DOMAIN%)",
	'debug_steam_get_url'				=> "LOGIN STEAM: URL para login foi gerada (%URL%)",
	'debug_steam_get_data'				=> "LOGIN STEAM: Foram recuperado os seguintes dados após login: %DATA%",

	'debug_twitter_set_credentials'		=> "LOGIN TWITTER: Setando as Credenciais: consumerKey(%CONSUMERKEY%) e consumerSecret(%CONSUMERSECRET%)",
	'debug_twitter_get_url'				=> "LOGIN TWITTER: URL para login foi gerada (%URL%)",
	'debug_twitter_get_data'			=> "LOGIN TWITTER: Foram recuperado os seguintes dados após login: %DATA%",



);