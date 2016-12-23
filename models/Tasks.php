<?php

class Tasks
{
	
	public static function getTasksItemById($id){
		if($id){
			$db = DB::getConnection();
			
			$paramsPath = ROOT.'/config/settings.php';
			$params = include ($paramsPath);
			
			$rezult = $db->query("SELECT * FROM tasks WHERE id = ".$id);
			$rezult->setFetchMode(PDO::FETCH_ASSOC);
			$taskItem = array();
			$row= $rezult->fetch();
			
			$taskItem['id'] = $row['id'];
			$taskItem['description'] = $row['description'];
			$taskItem['done'] = $row['done'];
			$taskItem['user'] = $row['user'];
			$files = array();
			$files[0] = '/'.$params['image_url'].'/'.$row['image'];
			
			$taskItem['file'] = $files;
			//'			/'.$params['image_url'].'/'.$row['image'];
 			return $taskItem;
        }
    }
    public static function getTasksList($done1,$page = null, &$pages = null){
		$paramsPath = ROOT.'/config/settings.php';
		$params = include ($paramsPath);
		$done = Tasks::isCheckDone();
		//var_dump($done);
		$limit = BootstrapPagination::getCountItemPage();
		if($page != null) {$offset = $page*$limit;} else $offset = 0;
		Tasks::setCurrentPage($page);
		$db = DB::getConnection();
		///////////
		$strQuery = "SELECT count(id) as count FROM tasks";
		if ($done){
			$strQuery = $strQuery." WHERE done = 1";
		}
 		$rezult = $db->query($strQuery);
        $rezult->setFetchMode(PDO::FETCH_ASSOC);
		$row= $rezult->fetch();
//
		//require_once('BootstrapPagination.php');
		$total = $row['count'];
		$pager = new BootstrapPagination($total, (isset($_GET['page']) ? $_GET['page'] : 1), 10);
		//$pager->setParams(['term' => 'test']);
		//$pager->setLocation('/example.php');
		$pager->setLocation('/');
		//$pager->setQuerySeparator('/');
		//$pager->setQuerySeparator('/');
		//$pager->keyValueSeparator = '/';
		//$pager->keyValueSeparator = '/';
		$pager->pagesCutOff = 20;
		//$pager->itemsTemplate = "Page {current} of {pages} <ul >\n{items}</ul>";
		$pages = $pager->getPages();
		//
		/////////////////
        $taskList = array();
		$strQuery = "SELECT * FROM tasks";
		if ($done){
			$strQuery = $strQuery." WHERE done = 1";
		}
		$strQuery = $strQuery." ORDER BY id DESC";
		if(!empty($limit)) $strQuery = $strQuery." LIMIT $limit";
		if(!empty($offset)) $strQuery = $strQuery." OFFSET $offset";
        $rezult = $db->query($strQuery);
        $i = 0;
        while($row = $rezult->fetch()){
            $taskList[$i]['id'] = $row['id'];
			$taskList[$i]['description'] = $row['description'];
			$taskList[$i]['done'] = $row['done'];
			$taskList[$i]['image'] = '/'.$params['image_url'].'/'.$row['image'];
			$taskList[$i]['status'] = ($row['done'] === "1") ? "Выполнена":"Новая";
            $i++;
        }
		//var_dump($strQuery);
		//var_dump($taskList);
		return $taskList;
    }
	public static function updateDoneTask($id,$done){
		$db = DB::getConnection();
		// set the PDO error mode to exception
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$strQuery = "UPDATE tasks SET done = $done WHERE id = $id";
		//echo '$strQuery: '.$strQuery;
		try{
			$db->exec($strQuery);
			//echo 'Задача изменена';
		}
			catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
	}
    public static function addTask(){
		$paramsPath = ROOT.'/config/settings.php';
		$params = include ($paramsPath);
		//echo '$addTask start';
		 // проверяем isset файла из отправленной формы данных
		if (isset($_POST['id'])){
			$id=$_POST['id'];
		}else {
			$id=null;
		}
		if(isset($_FILES['myfile']['name']) && !empty($_FILES['myfile']['name']) && !empty($_FILES['myfile']['name'][0])) {$isFile = true; } else {$isFile = false;}
		$isUpdateTask = $id!=null && !$isFile;
		echo '$id: '.$id;
		echo '$isFile: '.$isFile;
		echo '$isUpdateTask: '.$isUpdateTask;
		if ($isFile || $isUpdateTask)
		{
			$url = ROOT.'/'.$params['image_url'];
			//проверка расширения файла
			$file_name = $_FILES['myfile']['name'][0];
			$filetype = substr($file_name, strlen($file_name) - 3);
			//echo '$file_name: '.$file_name;
			//echo '$filetype: '.$filetype;
			//JPG/GIF/PNG
			if ($filetype == "jpg" ||
					$filetype == "gif" ||
					$filetype == "png" ||
			   		$isUpdateTask == true
			   )
			{
				// файл не должен быть пустым,
				// или его размер должен быть <= 800 Кбайт
				if($_FILES['myfile']['size'] != 0
				   //AND $_FILES['FILE']['size']<=819200
				  )
			  {
				$temp_file = $_FILES['myfile']['tmp_name'][0];
			   //проверяем функцией is_uploaded_file
				if(is_uploaded_file($temp_file) || $isUpdateTask == true)
				  {
					// проверяется перемещение файла
					// в файловую систему хостинга
					$user = $_POST['user'];
					$description = '"'.$_POST['description'].'"';
					$isValid = true;
					if($isUpdateTask) {
						$image = '';
					}else {
						$basename_f = basename(str_replace(' ','',$file_name));
						$image_url = $url."/".$basename_f;
						if (file_exists($image_url)) {
							$uid = getUID();
							$basename_f = $uid.'_'.$basename_f;
							$image_url = $url."/".$basename_f;
						};
						if (move_uploaded_file($temp_file, $image_url))  {
							imageresize($image_url,$image_url,$filetype);
						}	else $isValid = false;
						$image = '"'.$basename_f.'"';
					}
					if ($isValid)  {
						$db = DB::getConnection();
						// set the PDO error mode to exception
    					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						if ($id!=null){
							$strQuery = "UPDATE tasks SET user = $user, description = $description";
							if ($image !='') $strQuery = $strQuery." , image = $image";
							$strQuery = $strQuery." WHERE id = $id";
						}	else{
							$strQuery = "INSERT INTO tasks (user,description,image) VALUES($user,$description,$image)";
						}
						//echo '$strQuery: '.$strQuery;
						try{
							$db->exec($strQuery);
							return true;
							//echo 'Задача добавлена';
						}
						catch(PDOException $e)
						{
							echo $strQuery . "<br>" . $e->getMessage();
						}
					}else{
						echo 'Ошибка загрузки файл '.basename($file_name) ;
					}
				  }
			   }
			}
			else { echo 'Расширение файла: "'.$filetype.
							  '" запрещено для загрузки';}
		}
		return false;
		/*
		$rezult = $db->query("SELECT * FROM tasks");
        $i = 0;
        while($row = $rezult->fetch()){
            $taskList[$i]['id'] = $row['id'];
			$taskList[$i]['description'] = $row['description'];
            $i++;
        }
		return $taskList;
		*/
    }
	public static function defTask(){
		$defTask = array();
		$defTask['id'] = '';
		$defTask['user'] = User::userId();
		$defTask['description'] = '';
		$defTask['file'] = '';
		$defTask['done'] = '';
		return $defTask;
	}
	public static function doneTask($id,$done){
	//echo '$id: '.$id.', $done: '.$done;
		$paramsPath = ROOT.'/config/settings.php';
		$params = include ($paramsPath);
		 // проверяем isset файла из отправленной формы данных
		if (isset($_FILES['myfile']['name']))
		{
			$url = ROOT.'/'.$params['image_url'];
			//проверка расширения файла
			$file_name = $_FILES['myfile']['name'][0];
			$filetype = substr($file_name, strlen($file_name) - 3);
			//echo '$file_name: '.$file_name;
			//echo '$filetype: '.$filetype;
			//JPG/GIF/PNG
			if ($filetype == "jpg" ||
					$filetype == "gif" ||
					$filetype == "rar" ||
					$filetype == "png")
			{
				// файл не должен быть пустым,
				// или его размер должен быть <= 800 Кбайт
				if($_FILES['myfile']['size'] != 0
				   //AND $_FILES['FILE']['size']<=819200
				  )
			  {
			$temp_file = $_FILES['myfile']['tmp_name'][0];
			   //проверяем функцией is_uploaded_file
			if(is_uploaded_file($temp_file))
				  {
					// проверяется перемещение файла
					// в файловую систему хостинга
					$basename_f = basename(str_replace(' ','',$file_name));
					$image_url = $url."/".$basename_f;
					if (file_exists($image_url)) {
						$image_url = $url."/".getUID().'_'.$basename_f;
					};
					if (move_uploaded_file($temp_file,
					   $image_url))
					  {
						imageresize($image_url,$image_url,$filetype);
						$db = DB::getConnection();
						// set the PDO error mode to exception
    					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$user = $_POST['user'];
						$description = '"'.$_POST['description'].'"';
						$image = '"'.$basename_f.'"';
						$strQuery = "INSERT INTO tasks (user,description,image) VALUES($user,$description,$image)";
						//echo '$strQuery: '.$strQuery;
						try{
							$db->exec($strQuery);
							//echo 'Задача добавлена';
						}
						catch(PDOException $e)
						{
							echo $sql . "<br>" . $e->getMessage();
						}
					}else{
						echo 'Ошибка загрузки файл '.basename($file_name) ;
					}
				  }
			   }
			}
			else { echo 'Расширение файла: "'.$filetype.
							  '" запрещено для загрузки';}
		}
    }
	public static function setChekedDone($value){
		$_SESSION['ChekedDone'] = $value;
		//var_dump($_SESSION['ChekedDone']);
	}
	public static function isCheckDone(){
		$isChekedDone = false;
		if (isset($_SESSION['ChekedDone']) && $_SESSION['ChekedDone'] == 1) {
			$isChekedDone = true;
		}
		//var_dump($_SESSION['ChekedDone']);
		return $isChekedDone;
	}
	public static function getcurrentPage()
    {
        if (isset($_SESSION['CurrentPage'])) {
            return $_SESSION['CurrentPage'];
        }
        return null;
    }
	public static function setCurrentPage($page)
    {
    	$_SESSION['CurrentPage'] = $page;
    }
}