<?php
class BaseController extends Controller
{
	protected $ssdb;
	
	function init($ctx){
		session_start();
		$ctx->user =$_SESSION['login_user'];
		if(!$ctx->user){
			_redirect('login');
			return;
		}

		$conf = App::$config['ssdb'];
		$this->ssdb = new SimpleSSDB($conf['host'], $conf['port']);
		
		if(!empty($conf['password']))
			$this->ssdb->auth($conf['password']);
		
		$req = $_GET + $_POST;
		if(isset($req['size'])){
			$ctx->size = intval($req['size']);
			if($ctx->size > 0){
				setcookie('psa_size', $ctx->size, time() + 86400 * 30, '/');
				$_COOKIE['psa_size'] = $ctx->size;
			}
		}
		if(isset($_COOKIE['psa_size'])){
			$ctx->size = intval($_COOKIE['psa_size']);
		}else{
			$ctx->size = 0;
		}
		if($ctx->size <= 0){
			$ctx->size = 10;
		}
	}
}
