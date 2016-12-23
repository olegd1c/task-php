<?php

include_once ROOT.'/models/Tasks.php';

class TaskController
{
	public function actionList(){
		$pages = null;
		$tasksList = Tasks::getTasksList(false);
		//
		//			require_once ROOT.'/view/tasks/list.php';
		
		require (ROOT.'/views/site/index.php');
		return true;
		
	}
	
	public function actionView($id){
		if($id){
			$taskItem = Tasks::getTasksItemById($id);
			
			require_once ROOT.'/views/tasks/task.php';
			return true;
		}
	}
	
	public function actionEdit($id){
		if($id){
			$currentTask = Tasks::getTasksItemById($id);
			
			require_once ROOT.'/views/tasks/edit.php';
			return true;
		}
	}
	
	public function actionUpdate($id){
		//i		f($id){
			$rezult = Tasks::addTask($id);
			if ($rezult) {
				
				$currentTask = Tasks::defTask();
				$url = "Location: /";
				if(Tasks::getcurrentPage()!=null){
					$url=$url.'page/'.Tasks::getcurrentPage();
				}
				
				header($url);
			}
			return $rezult;
			//}
		}
		
		public function actionAdd(){
			//i			f($id){
				$rezult = Tasks::addTask();
				if ($rezult) {
					
					$url = "Location: /";
					if(Tasks::getcurrentPage()!=null){
						$url=$url.'page/'.Tasks::getcurrentPage();
					}
					
					header($url);
					//h					eader("Location: /");
				}
				//r				equire_once ROOT.'/views/tasks/task.php';
				return $rezult;
				//}
			}
			
			public function actionUpdateDone($id,$done){
				//i				f($id){
					//e					cho 'actionUpdateDone: '.$id.', $done: '.$done;
					Tasks::updateDoneTask($id,$done);
					
					$pages = null;
					$tasksList = Tasks::getTasksList(false);
					//,					,$pages
							//h					eader("Location: /");
					
					//r					equire (ROOT.'/views/site/index.php');
					require (ROOT.'/views/layouts/listTask.php');
					return true;
					//}
				}
				
				public function actionChekeddDone($value){
					//i					f($id){
						//$						rezult = Tasks::setChekedDone($value);
						Tasks::setChekedDone($value);
						$pages = null;
						$tasksList = Tasks::getTasksList(false,null,$pages);
						require (ROOT.'/views/layouts/listTask.php');
						//r						equire (ROOT.'/views/site/index.php');
						
						return true;
						//}
					}
					
					
					
				}
				?>