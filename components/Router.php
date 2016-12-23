<?php

class Router
{
	private $routers;
	
	function __construct()
	    {
		$routesPath = ROOT.'/config/routers.php';
		$this->routers = include ($routesPath);
		
	}
	
	function run(){
		$uri = $this->getUri();
		foreach($this->routers as $uriPatern => $path ){
			
			if(preg_match("~$uriPatern~",$uri)){
				$internalRoute = preg_replace("~$uriPatern~",$path,$uri);
				$segment = explode('/',$internalRoute);
				$controllerName = array_shift($segment).'Controller';
				$controllerName = ucfirst($controllerName);
				
				$actionName = 'action'.ucfirst(array_shift($segment));
				$params = $segment;
				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
				if(file_exists($controllerFile)){
					include_once ($controllerFile);
				}
				
				$controllerObject = new $controllerName;
				
				try{
					$rezult = call_user_func_array(array($controllerObject,$actionName),$params);
				}
				catch (Exception $e) {
					echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
				}
				//$				rezult = $controllerObject->$actionName($params);
				if($rezult != null){
					
					break;
				}
			}
		}
	}
	
	public function getUri()
	    {
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}
}
