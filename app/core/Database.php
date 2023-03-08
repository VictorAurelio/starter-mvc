<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Summary of Database
 * I decided to declare Database as a trait in order to be able to use it onto Model.php and still have it(model) as a trait as well.
 */
trait Database{
    use \Config;
    protected $db;
    private function connect() {
        $this->environmentType();
        try {    
            $this->db = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            echo "ERROR:" . $e->getMessage();
            exit;    
        }
        return $this->db;
    }
	   /**
	    * Summary of query
	    * @param mixed $query
	    * @param mixed $data
	    * @return array|bool
	    */
    // Query will be responsible for preparing the data received by model and executing it
    public function query($query, $data = []):array|bool
	{
        // echo '<br>';
        // var_dump($data);
        // echo '<br>';
        // var_dump($query);
        $this->db = $this->connect();
		$stmt = $this->db->prepare($query);
		$check = $stmt->execute($data);
		if($check)
		{
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(is_array($result) && count($result))
			{
				return $result;
			}
		}
		return false;
	}
	   /**
	    * Summary of getRow
	    * @param mixed $query
	    * @param mixed $data
	    * @return array|bool
	    */
    // getRow will be responsible for returning the first row
    public function getRow($query, $data = [])
	{
        $db = $this->connect();
		$stmt = $this->db->prepare($query);

		$check = $stmt->execute($data);
		if($check)
		{
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(is_array($result) && count($result))
			{
				return $result[0];
			}
		}
		return false;
	}
}