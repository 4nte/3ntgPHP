<?php

/**
* Model 
*/
class Model
{

	private $modelName;
	
	function __construct()
	{
		$this->modelName = strtolower(get_class($this));
	}

	public function findFirst(){

		$result = $this->rawQuery("SELECT * FROM ".$this->modelName." ORDER BY started desc LIMIT 1");
		$res = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$instance = new $this->modelName;
				foreach ($row as $key => $value) {
					echo $key;
					$instance->{$key} = $value;
				}
				$res[] = $instance;
			}
			return $res;
		}
	 	else
			return false;
	}

	public function rawQuery($q){
		global $conn;
		return $conn->query($q);
	}

	public function rawMultiQuery($q){
		global $conn;
		return $conn->multi_query($q);
	}


	
}