<?php
/**
* add controller
*/
class AddController extends Controller
{
	
	public function indexAction(){}

	public function insertAction(){


		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$title = $_POST["title"];
		  	$description = $_POST["description"];

			$task = new Tasks();
			$task->title = $title;
			$task->description = $description;
			$task->started = date("Y-m-d H:i:s", time());
			$res = $task->create();
			if($res){
				$this->redirect("index");
			}
			else{
				Logger::log("error","Failed to insert duplicate task record ({$task->title})");	
				$this->redirect("index/index/1");			
			}
		}
	}
	public function finishAction($title,$status){
		$status = ucfirst(substr($status, 0,1)) == "F" ? 1 : 0;
		$title = urldecode($title);

		$task = new Tasks();
		$task->title = $title;
		$task->status = $status;
		$task->finished = date("Y-m-d", time());
		$task->doFinish();

		$this->redirect("index");
	}

}