<?php

class PageController
{
	public function actionIndex(){
		
		$tasksList = Tasks::getTasksList(false);
		require_once (ROOT.'/views/site/index.php');
		return true;
	}
}
