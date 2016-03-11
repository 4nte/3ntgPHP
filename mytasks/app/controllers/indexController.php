<?php
/**
* Index controller
*/

class IndexController extends Controller
{

	public function finishedAction(){

		$this->setView("index/index");
		$tasks = new Tasks();
		$taskList = array();
		$fetched = $tasks->findFinished();

		// 0 Tasks found 
		if(!$fetched)
			return;

		foreach ($fetched as $task) {
			
			$taskList[] = [
						"title" => $task->title,
						"titleEncoded" => urlencode($task->title),
						"description" => $task->description,
						"status" => $task->status ? "finished" : "created",
						"date" => $task->getDate()
						];
		}
		$this->view->set("tasks",$taskList);
	}
	public function activeAction(){
		$this->setView("index/index");
		$tasks = new Tasks();


		$taskList = array();

		$fetched = $tasks->findActive();

		// 0 Tasks found 
		if(!$fetched)
			return;

		foreach ($fetched as $task) {
			
			$taskList[] = [
						"title" => $task->title,
						"titleEncoded" => urlencode($task->title),
						"description" => $task->description,
						"status" => $task->status ? "finished" : "created",
						"date" => $task->getDate()
						];
		}
		$this->view->set("tasks",$taskList);
	}
	
	public function languageAction($lang){
		$_SESSION['lang'] = $lang;
		$this->redirect("index");
	}
	public function logsAction(){
		$this->view->set("log", Logger::read());
	}
	public function indexAction($err = null){

		if($err)
			$this->view->outputError("{{duplicateError|t}}");

		$tasks = new Tasks();


		$taskList = array();

		$fetched = $tasks->find();

		// 0 Tasks found 
		if(!$fetched)
			return;

		foreach ($fetched as $task) {
			$date = $task->status ? date("d.m.Y", strtotime($task->finished)) : date("d.m.Y H", strtotime($task->started))."h";
			$taskList[] = [
						"title" => $task->title,
						"titleEncoded" => urlencode($task->title),
						"description" => $task->description,
						"status" => $task->status ? "finished" : "created",
						"date" => $date
						];
		}
		$this->view->set("tasks",$taskList);
	}

}