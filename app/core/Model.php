<?php
namespace App\Core;

use PDO;

/**
 * Summary of Model
 * Declared as Trait since I'll be using another model for products only, this one is for sql queries
 */
trait Model {    
    use Database;
    protected $order_type 	= "desc";
	protected $order_column = "id";
    // protected $table = 'users';
    // protected $allowedColumns = [];
    public function select(array $columns = ['*']): self
    {
        $this->select = implode(',', $columns);
        return $this;
    }
    /**
     * Summary of findAll
    * @return array|bool
    */
    public function findAll($limit = null)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->order_column} {$this->order_type}";
        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }
    
        $stmt = $this->db->prepare($query);
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function where($data, $noData = []) {
        $query = "SELECT * FROM {$this->table} WHERE ";
    
        // Add conditions for data
        foreach ($data as $key => $value) {
            $query .= "$key = :$key AND ";
        }
    
        // Add conditions for noData
        foreach ($noData as $key => $value) {
            $query .= "$key != :$key AND ";
        }
    
        // Remove the trailing "AND" from the query
        $query = rtrim($query, 'AND ');
    
        // Add the order by clause
        $query .= " ORDER BY {$this->order_column} {$this->order_type}";
    
        // Merge the data arrays
        $data = array_merge($data, $noData);
    
        // Execute the query
        $result = $this->query($query, $data);
    
        // Return null if there are no results
        if (empty($result)) {
            return null;
        }
    
        return $result;
    }
    public function first($data, $noData = []) {
		$query = "SELECT * FROM {$this->table} WHERE ";

        // Add conditions for data
        foreach ($data as $key => $value) {
            $query .= "$key = :$key AND ";
        }
    
        // Add conditions for noData
        foreach ($noData as $key => $value) {
            $query .= "$key != :$key AND ";
        }
		
        // Remove the trailing "AND" from the query
        $query = rtrim($query, 'AND ');

		// $query .= ""; // left this line in case I wanna change the query later on
		$data = array_merge($data, $noData);
		
		$result = $this->query($query, $data);
		if($result)
			return $result[0];
		return false;
	}
	 		/**
	 		 * Summary of create
	 		 * @param mixed $data
	 		 * @return bool
	 		 */
    public function create($data): array|bool
	{		
		// check if not allowed data was inserted
		if(!empty($this->allowedColumns))
		{
			foreach ($data as $key => $value) {				
				if(!in_array($key, $this->allowedColumns))
				{
					unset($data[$key]);
				}
			}
		}
		$keys = array_keys($data);
        // var_dump($keys);
        // echo '<br>';
		$query = "INSERT INTO {$this->table} (".implode(",", $keys).") VALUES (:".implode(",:", $keys).")";
        // var_dump($query);
        // echo '<br>';
		$this->query($query, $data);
		return false;
	}
    public function update($id, $data, $idColumn = 'id') {
		// check if not allowed data was inserted
		if(!empty($this->allowedColumns))
		{
			foreach ($data as $key => $value) {
				
				if(!in_array($key, $this->allowedColumns))
				{
					unset($data[$key]);
				}
			}
		}
		$keys = array_keys($data);
		$query = "UPDATE {$this->table} SET ";

		foreach ($keys as $key) {
			$query .= $key . " = :". $key . ", ";
		}
		$query = trim($query,", ");

		$query .= " where $idColumn = :$idColumn ";

		$data[$idColumn] = $id;

		$this->query($query, $data);

		return false;
	}
    public function delete($id, $idColumn = 'id') {
		$data[$idColumn] = $id;
		$query = "DELETE FROM {$this->table} WHERE $idColumn = :$idColumn ";

		$this->query($query, $data);

		return false;
	}

}