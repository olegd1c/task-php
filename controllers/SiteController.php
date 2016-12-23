<?php

class SiteController
{
	public function actionIndex($page = null){
		$pages = null;
		$tasksList = Tasks::getTasksList(false,$page,$pages);
		require (ROOT.'/views/site/index.php');
		return true;
	}
	
}
