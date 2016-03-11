<?php

/**
* Tasks model
*/
class Tasks extends Model
{
	
	public $title;
	public $description;
	public $started;
	public $finished;
	public $status;

	public function find(){
		$result =  $this->rawQuery("SELECT * FROM tasks ORDER BY started desc");

		$res = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$instance = new Tasks();
				foreach ($row as $key => $value) {
					$instance->{$key} = $value;
				}
				$res[] = $instance;
			}
		}
		else
			return false;
		return $res;
	}


	public function findActive(){
		$result =  $this->rawQuery("SELECT * FROM tasks WHERE status = '0' ORDER BY started desc");

		$res = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$instance = new Tasks();
				foreach ($row as $key => $value) {
					$instance->{$key} = $value;
				}
				$res[] = $instance;
			}
		}
		else
			return false;
		return $res;
	}

	public function findFinished(){
		$result =  $this->rawQuery("SELECT * FROM tasks WHERE status = '1' ORDER BY started desc");
		
		$res = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$instance = new Tasks();
				foreach ($row as $key => $value) {
					$instance->{$key} = $value;
				}
				$res[] = $instance;
			}
		}
    	else
			return false;
		return $res;
	}

	public function getDate(){
		return $this->status ? date("d.m.Y", strtotime($this->finished)) : date("d.m.Y H", strtotime($this->started))."h";
	}

	public function doFinish(){
		return $this->rawQuery("UPDATE tasks SET status = '1', finished ='{$this->finished}' WHERE title = '{$this->title}' AND status = '{$this->status}'");
	}

	public function create(){
		$res = $this->rawMultiQuery( "SET @p0='{$this->title}'; SET @p1='{$this->description}'; SET @p2='{$this->started}'; CALL `insertTask`(@p0, @p1, @p2); select @duplicate as dup;" );
		if(!is_null($res[0][0]))
			return false;
		return true;
	}

}