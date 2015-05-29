<?php 

// simplification of CDatabase from oophp. 

class CDatabase {
	
	private $dbh;
	private $stmt;
	
	/**
	 * Constructor. Set up the connection to the database.
	 * @param array connection details
	 *
	 */
	public function __construct($connection) {

		// extract variables from the array, creating $dsn etc. 
		extract($connection);

		try { // use to have control over the thrown exception.
			$this->dbh = new PDO($dsn, $username, $password, $options);
		}
		catch(Exception $e) {
			throw new PDOException('Could not connect to database.. :( (Hiding connection details)');
		}
		$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchStyle);
	}
	/**
	 * function to return array of the query results. 
	 * @param $query, the SQL query to run.
	 * @param $params, any parametres for the SQL query.
	 * @return query result as array.
	 */
	public function executeSelectQueryAndFetchAll($query, $params = array()) {
		$this->stmt = $this->dbh->prepare($query);
		$this->stmt->execute($params);
		return $this->stmt->fetchAll();
	}
	
	/**
	 *
	 * Function to execute a SELECT query and fetch one row. 
	 * @return one row. 
	 */
	public function executeSelectQueryAndFetch($query, $params = array()) {
		$this->stmt = $this->dbh->prepare($query);
		$this->stmt->execute($params);
		return $this->stmt->fetch();
	}
	/**
	 *
	 * Function to execute a statement. 
	 * @return last insert ID (if MySQL). 
	 *
	 */
	public function executeQuery($query, $params = array()) {
		$this->stmt = $this->dbh->prepare($query);
		return $this->stmt->execute($params);
	}
}