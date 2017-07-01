<?php
/**
 * 	Handles all Database Functionality
 */
class DB {

	/**
	 *	Stores an instance of the Database
	 *  @var null 			_instance		Database instance
	 */
	private static $_instance = null;

	private $_pdo, // Stores the PDO Object
			$_query, // Last Query That was Executed
			$_error = false, // Whether Query Failed or not
			$_results, // Results Set from Query
			$_count = 0; // Count of Results

	// Connect to the DB
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	// Check to see if we are connected to the DB, if we are return the database, if not connect and then return DB
	// Must be called every time the DB class is called
	// Use "self::" instead of "$this->" because its inside of a static method
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	// Query the Database
	public function query($sql, $params = array()) {
		// reset the error back to false
		$this->_error = false;
		// Prepare the SQl for the Execution and Assign to "_query" variable
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			// Check if the Params Exist
			if(count($params)) {
				// Assign each Param to the "?" within the Prepared SQL statement
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			// Execute the Query
			if($this->_query->execute()) {
				// If Success, assign results and count to variables
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				// Print the PDO Error
				// print_r($this->_query->errorInfo());
				// Something went wrong? Make Error = true
				$this->_error = true;
			}
		}
		// Return the Current Object
		return $this;
	}

	// Action on the DB
	public function action($action, $table, $where = array()) {
		// If Where has all three values
		if(count($where) === 3) {
			// Define all "LEGAL" operators
			$operators = array('=', '>', '<', '>=', '<=');
			// Define the Three Variables from Where
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			// If the operator submitted is in Legal list
			if(in_array($operator, $operators)) {
				// Write the SQL
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				// If there isn't an error upon query
				if(!$this->query($sql, array($value))->error()) {
					// Return Current Object
					return $this;
				}
			}
		}
		// Something went wrong? Return False;
		return false;
	}

	// Insert into DB
	public function insert($table, $fields = array()) {
		// If Fields & Table variable isn't null
		if(count($fields) && !$table == null) {
			// Get the keys of the array
			$keys = array_keys($fields);
			// Set Values to Null & Start Counter at 1
			$values = '';
			$x = 1;
			// Loop through the fields and assign the values to the values string.
			foreach ($fields as $field) {
				// Put a Question mark for the value
				$values .= '?';
				// Add Commas between the values but not at the end
				if($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			// Get SQL Statement together
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
			// Execute Query and check for errors
			// If there are NO errors return true
			if(!$this->query($sql, $fields)->error()) {
				// If entered correctly Return True
				return true;
			}
		}
		// If something went wrong, return false
		return false;
	}

	// Update a record in the database
	public function update($table, $id, $fields = array()) {
		// Define some variables
		$set = '';
		$x = 1;
		// Assign the Key to $name and Value to $value
		foreach($fields as $name => $value) {
			// Write some SQL (see insert() for more info)
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		// Get the SQL together in a string
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		// IF query, doesn't return any errors
		if(!$this->query($sql, $fields)->error()) {
			// If entered correctly Return True
			return true;
		}
		// Something went wrong? Return False
		return false;
	}

	// Get All From Table
	public function getAll($table) {
		$sql = "SELECT * FROM {$table}";
		// IF query, doesn't return any errors
		if(!$this->query($sql)->error()) {
			// If entered correctly Return True
			return $this->_results;
		}
	}

	// Get Table Fields from Table
	public function getTableFields($table) {
		$sql = "DESCRIBE {$table}";
		// IF query, doesn't return any errors
		if(!$this->query($sql)->error()) {
			// If entered correctly Return True
			return $this->_results;
		}
	}

	// Edit the Table
	public function editTable($sql) {
		if(!$this->query($sql)->error()) {
			// If entered correctly Return True
			return true;
		} else {
			echo $this->error();
		}
	}

	// Get the Number of total entries in the table
	public function getEntriesCount($table) {
		$sql = "SELECT * FROM {$table}";

		if(!$this->query($sql)->error()) {
			// Return the number of entries
			return $this->_count;
		}
	}

	// Check to see if table exists
	public function tableExists($table) {
		$sql = "SELECT 1 FROM {$table} LIMIT 1";

		if(!$this->query($sql)->error()) {
			// If count is null, table doesn't exist, return false
			if(is_null($this->_count)) {
				return false;
			} else {
				return true;
			}
		}
	}

	// Delete Table from DB
	public function tableDel($table) {
		$sql = "DROP TABLE {$table}";

		// If there is no errors
		if(!$this->query($sql)->error()) {
			return true;
		} else {
			return false;
		}
	}

	// Create Table in the DB
	public function tableCreate($sql) {
		// If there is no errors
		if(!$this->query($sql)->error()) {
			return "true";
		} else {
			return "false";
		}
	}

	// List Table Names
	public function getTables() {
		$sql = "SHOW TABLES";

		if(!$this->query($sql)->error()) {
			// If entered correctly Return True
			return $this->_results;
		}
	}

	// Get Number of Fields/Columns of Specified Table
	public function getFieldCount($table) {
		$sql = "SHOW COLUMNS FROM {$table}";

		if(!$this->query($sql)->error()) {
			$results = $this->_results;
			$count = 0;
			foreach($results as $field) {
				$count++;
			}
			// If entered correctly Return True
			return $count;
		}
	}

	// Get
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	// Delete
	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	// Get value of Error Variable
	public function error() {
		return $this->_error;
	}

	// Returns Count Private Variable
	public function count() {
		return $this->_count;
	}

	// Returns Results
	public function results() {
		return $this->_results;
	}

	// Returns first result
	public function first() {
		return $this->results()[0];
	}

}

?>