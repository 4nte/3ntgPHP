<?php
/**
* Database connection to mysql
*/
class DatabaseConnection
{

	private $conn;
	
	function __construct(config &$config)
	{

		$this->conn = new mysqli($config->get('host'),
									 $config->get('user'),
									 $config->get('password'),
									 $config->get('database'));
		if ($this->conn->connect_errno) 
    		die("Connect failed: %s\n". $this->conn->connect_error);
	}
	public function query($q){
		return $this->conn->query($q);
	}
	public function multi_query($q){
		$this->conn->multi_query($q);
		$results = array();
		do {
        	if ($result = $this->conn->store_result()) {
            		while ($row = $result->fetch_row()) {
               				$results[] = $row;
            		}
            $result->free();
        	}
    	} while ($this->conn->more_results() && $this->conn->next_result());
    return $results;	
	}
}