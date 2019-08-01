<?php

//A databse should only be accessible in Model!
class Database extends PDO {

	public $result;
	public $error;
	
	public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
		parent::__construct(
				$DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				)
		);
	}

	//TODO: Add transaction, commit, & rollback.

	/**
	 * Insert function. Returns num rows affected or false for error. NOTICE: Can return 0 on a successful call if nothing updated.
	 * 
	 * @param array $on_dup_update_key	Ignores duplicates by updating key to itself. Any column or array of columns will work
	 * 
	 * @return mixed False if fail, num rows affected otherwise
	 */
	function insert($table_name, $arr_parameters, $on_dup_update_key = null, $debug = false) {
		if (strpos($table_name, '.') === FALSE) {
			$table_name = "`$table_name`";
		}
		// if we have something
		if (count($arr_parameters) > 0) {
			// Build query for single row insert
			if (!isset($multiple_rows) || !is_array($multiple_rows[0])) {
				$col_placeholder = implode('`,`', array_keys($arr_parameters));
				$val_placeholder = implode(',', array_fill(0, count($arr_parameters), '?'));
				$qry = "INSERT INTO $table_name (`$col_placeholder`)"
						. " VALUES ($val_placeholder)";
			}
			// Build query for multiple row insert
			else {
				$col_placeholder = implode('`,`', array_keys($arr_parameters[0]));
				$val_placeholder = implode(',', array_fill(0, count($arr_parameters[0]), '?'));
				$qry = "INSERT INTO $table_name (`$col_placeholder`)"
						. " VALUES (" . implode('),(', array_fill(0, count($arr_parameters), $val_placeholder)) . ")";
			}
			// Accept array of column names to update on duplicate key
			if (is_array($on_dup_update_key) && count($on_dup_update_key)) {
				$qry .= ' ON DUPLICATE KEY UPDATE';
				foreach ($on_dup_update_key as $column) {
					$qry .= " $column = VALUES($column),";
				}
				// Trim off last comma
				$qry = substr($qry, 0, -1);
			}
			// Accept single column name to update on duplicate key
			else if (!is_array($on_dup_update_key) && $on_dup_update_key) {
				$qry .= " ON DUPLICATE KEY UPDATE $on_dup_update_key = VALUES($on_dup_update_key)";
			}
			// run
			if ($this->run_unnamed_query($qry, $arr_parameters, false, $debug)) {
				return $this->get_num_rows();
			} else {
				return false;
			}
		} else {
			// Insert blank row
			if ($this->run_unnamed_query("INSERT INTO $table_name () VALUES()", null, false, $debug)) {
				return $this->get_num_rows();
			} else {
				return false;
			}
		}
	}

	/**
	 * Delete function. Returns pdo_statement object for parent function to use
	 * @param string $table_name
	 * @param array $arr_where_parameters Associative array of where parameters. Use :NULL: for 'IS NULL'. DOES NOT handle array of values for IN clause.
	 * @param boolean $is_limit_1 Default false
	 * @param boolean $debug Default true
	 * @return boolean
	 */
	function delete($table_name, $arr_where_parameters, $is_limit_1 = FALSE, $debug = false) {
		if (strpos($table_name, '.') === FALSE) {
			$table_name = "`$table_name`";
		}
		// if we have something
		$arr_parameters = array();
		if (count($arr_where_parameters) > 0) {
			// build the query
			$qry = "DELETE FROM $table_name WHERE ";

			foreach ($arr_where_parameters as $column_name => $value) {
				if (!is_array($value)) {
					if ($value === ':NULL:') {
						$conditionals[] = "$column_name IS NULL";
					} else if ($value === ':NOTNULL:') {
						$conditionals[] = "$column_name IS NOT NULL";
					} else {
						$conditionals[] = "$column_name = :$column_name";
						$arr_parameters[$column_name] = $value;
					}
				}
			}

			$qry .= implode(' AND ', $conditionals);
			if ($is_limit_1) {
				$qry .= " LIMIT 1";
			}
			// run
			return $this->run_named_query($qry, $arr_parameters, null, false, $debug);
		} else {
			$this->result = null;
			$this->error = "Delete from $table_name with conditionals ("
					. implode(',', $arr_where_parameters) . ") failed - nothing to be deleted.";
			return false;
		}
	}

	/**
	 * Build a simple select statement on the fly
	 * @param $table_name				Table to select from
	 * @param $arr_where_parameters		Associative array of where parameters. Use :NULL: for 'IS NULL'. Pass an array of values for an IN clause
	 * @param $arr_return_columns		Optional array or string list of columns to select/return. Defaults to selecting '*'
	 * @param $arr_order_by_parameters	Optional array or string list of columns to order by. Defaults to none.
	 * @param $is_desc					Optional Boolean. True to order by DESC. Defaults to false, ASC. Only used if you also pass $arr_order_by_parameters
	 * @param $limit					Optional limit number or array. If array, first number is offset, second is limit. Can pass 3rd array value of TRUE to request a row count. Retrieve count with call to get_row_count()
	 * @param $return_result			Optional boolean. Return result true/false? If false, a success boolean is returned. Default true.
	 * @param $where_and				Optional boolean. Use AND between WHERE true/false? If false, OR is used. Default true.
	 * @param $group_by					Optional array of GROUP BY statements
	 * @param $having					Optional HAVING statement as a string. (Leave out 'HAVING' at the begining)
	 * @param $debug					Optional boolean. If true, show debugging output. Default false
	 * @return mixed	2-D Array of results. If $limit is passed as one, 1-D array will be returned. If $limit is 1 and only a single column is requested, that exact value will be returned. true/false if return not requested.
	 */
	function select($table_name, $arr_where_parameters=array(), $arr_return_columns=null, 
			$arr_order_by_parameters=null, $is_desc=FALSE, $limit=null, 
			$return_result=true, $where_and=true, $group_by=array(), $having='', $debug=false){
		// build query and arr_parameters
		$columns = '';
		$count_rows = '';
		if (!$arr_return_columns){
			$columns .= "*";
		}
		else {
			if (!is_array($arr_return_columns)){
				$columns .= $arr_return_columns;
			}
			else {
				$columns .= implode(", ",$arr_return_columns);
			}
		}
		if (strpos($table_name, '.') === FALSE){
			$table_name = "`$table_name`";
		}
		// Request counting of rows if 3rd param of $limit is exactly TRUE
		if(is_array($limit) && $limit[2] === true){
			$count_rows = 'SQL_CALC_FOUND_ROWS';
		}
		
		$qry = "SELECT $count_rows $columns FROM $table_name";
		
		$arr_parameters = array();
		if (is_array($arr_where_parameters) && count($arr_where_parameters)){
			$qry .= " WHERE";
			$where_and = $where_and ? 'AND' : ' OR'; // Don't remove the space before OR. It's needed because of our bad code.
			foreach ($arr_where_parameters as $column_name=>$value){
				if (!is_array($value)){
					if ($value === ':NULL:'){
						$qry .= " $column_name IS NULL $where_and";
					}
					else if ($value === ':NOTNULL:'){
						$qry .= " $column_name IS NOT NULL $where_and";
					}
					else{
						$qry .= " $column_name = ? $where_and";
						$arr_parameters[] = $value;
					}
				}
				else{
					$qry .= " $column_name IN (". implode(',',array_fill(0, count($value), '?')).") $where_and";
					$arr_parameters = array_merge($arr_parameters, $value);
				}
			}
			
			$qry = substr($qry, 0, -3);
		}
		if (count($group_by)){
			$qry .= ' GROUP BY '.implode(',',$group_by);
		}
		if ($having){
			if (strtoupper(substr($having, 0, 6)) != 'HAVING'){
				$qry .= ' HAVING';
			}
			$qry .= ' '.$having;
		}
		
		if ($arr_order_by_parameters){
			$asc_desc = $is_desc ? 'DESC' : 'ASC';
			if (!is_array($arr_order_by_parameters)){
				$qry .= " ORDER BY ".$arr_order_by_parameters;
			}
			else {
				$qry .= " ORDER BY ".implode(' '.$asc_desc.", ", $arr_order_by_parameters);
			}
			$qry .= ' '.$asc_desc;
		}
		if ($limit){
			if (is_array($limit)){
				$qry .= " LIMIT ".$limit[0].", ".$limit[1];
				$intval = intval($limit[1]);
			}
			else {
				$qry .= " LIMIT $limit";
				$intval = intval($limit);
			}
		}
		
		$result = $this->run_unnamed_query($qry, $arr_parameters, $return_result, $debug);
		
		// Return single result if user specified a column and 
		// there is only one row and one column
		if($return_result && $arr_return_columns && 
				(count($arr_return_columns) == 1 || is_string($arr_return_columns))
			&& @$intval == 1){
			foreach($result as $row){
				foreach ($row as $value){
					$result = $value;
				}
			}
		}
		else if ($return_result && @$intval == 1 && count($result) == 1){
			$result = $result[0];
		}
		
		return $result;
	}
	
	/**
	 * Run an update query
	 * 
	 * @param $table_name Name of table
	 * @param $arr_where_parameters Array of column conditionals (column => matched_value). Use :NULL: for 'IS NULL'
	 * @param $arr_update_parameters Array of columns to update (column => new_value). NOTE: If parameter is blank, value will be set to null
	 * @param $where_and Optional boolean. Use AND between WHERE true/false? If false, OR is used. Default true.
	 * @param $debug Optional boolean. If true, show debugging output. Default false
	 * @return boolean True on success, False on failure
	 */
	function update($table_name, $arr_where_parameters = array(), $arr_update_parameters = array(),
			$where_and=true, $debug=false){
		if (strpos($table_name, '.') === FALSE){
			$table_name = "`$table_name`";
		}
		$qry = "UPDATE $table_name SET";
		$arr_parameters = array();
		
		foreach ($arr_update_parameters as $update_column => $update_value){
			if ($update_value === '' || $update_value === NULL){
				$qry .= " `".$update_column."` = NULL,";
			}
			else if ($update_value === 'NOW()'){
				$qry .= " `".$update_column."` = NOW(),";
			}
			else {
				$arr_parameters[] = $update_value;
				$qry .= " `".$update_column."`=?,";
			}
		}
		
		$where_and = $where_and ? 'AND' : ' OR'; // Don't remove the space before OR. It's needed because of our bad code.
		$qry = substr($qry, 0, -1)." WHERE";
		foreach ($arr_where_parameters as $where_column => $where_value){
			if (!is_array($where_value)){
				if ($where_value === ':NULL:'){
					$qry .= " $where_column IS NULL $where_and";
				}
				else if ($where_value === ':NOTNULL:'){
					$qry .= " $where_column IS NOT NULL $where_and";
				}
				else{
					$qry .= ' '.$where_column.'=? '.$where_and;
					$arr_parameters[] = $where_value;
				}
			}
			else{
				$qry .= " $where_column IN (". implode(',',array_fill(0, count($where_value), '?')).") $where_and";
				$arr_parameters = array_merge($arr_parameters, $where_value);
			}
		}
		$qry = substr($qry, 0, -3);

		return $this->run_unnamed_query($qry, $arr_parameters, false, $debug);
	}
	
	/**
	 * Get number of rows select() would have returned had it not been limited.
	 * This function will only work if you passed a 3rd value of TRUE in the $limit var of select()
	 * @param boolean $debug
	 * @return type
	 */
	function get_row_count($debug=false){
		$qry = 'SELECT FOUND_ROWS() as rows';
		
		$result = $this->run_unnamed_query($qry, array(), true, $debug);
		
		return isset($result[0]['rows']) ? $result[0]['rows'] : $result;
	}
	
	/**
	 * Run unnamed query, returning result.
	 * @param $query_with_placeholders	Query as a string with placeholders
	 * @param $arr_parameters			Array of unnamed parameters to place in query
	 * @param $return_result			Optional boolean. Return results on completion? If false, a success boolean is returned. Default false.
	 * @param $debug					Optional boolean. If true, show debugging output. Default false
	*/
	function run_unnamed_query($query_with_placeholders, $arr_parameters, $return_result=false, $debug=false){
		if (!$arr_parameters){
			$arr_parameters = array();
		}
		if ($debug){
			//$this->log->logInfo("Debug Qry: $query_with_placeholders", $arr_parameters);
		}
		$dataset_container = $this->prepare($query_with_placeholders);
		$start_time = microtime(true);
		try{
			$final_success = $dataset_container->execute(array_values($arr_parameters));
		} catch(PDOException $e) {
			// Error handled below
		}
		if ($debug){
			//$this->log->logInfo('Query runtime: '.(microtime(true)-$start_time).' seconds');
		}
		
		if ($final_success){
			$this->result = $dataset_container;
			$this->error = null;
			if($return_result){
				return $this->get_all_rows();
			}
			else{
				return true;
			}
		}
		else {
			$errors = $dataset_container->errorInfo();
			$this->result = null;
			$this->error = $errors[2];
			//$this->log_mysql_error($errors[2], $query_with_placeholders,
			//	$arr_parameters);
			return false;
		}
	}
	
	/**
	 * Run query containing no parameters
	 * 
	 * @param $query The query
	 * @param $return_result If true, we return the result rows; otherwise, default false
	 * @return True on success and false on failure unless $return_result
	 */
	function run_static_query($query, $return_result=false){
		$attempted_query_result = $this->query($query);
		if ($attempted_query_result){
			$this->result = $attempted_query_result;
			$this->error = null;
			if($return_result){
				return $this->get_all_rows();
			}
			else{
				return true;
			}
		}
		else {
			$errors = $attempted_query_result->errorInfo();
			$this->result = null;
			$this->error = $errors[2];
			//$this->log_mysql_error($errors[2], $query);
			return false;
		}
	}
	
	/** Run named query, returning result
	 * 
	 * We expect the parameters and expected type values to have their keys as
	 * the parameter name.
	 */
	function run_named_query($query_with_placeholders, $arr_parameters,
			$arr_expected_types=null, $return_result=false, $debug=false){
		$dataset_container = $this->prepare($query_with_placeholders);
		
		$arr_parameter_names = ($arr_parameters) ? array_keys($arr_parameters) : array();
		
		if ($debug){
			//$this->log->logInfo("Debug Qry: $query_with_placeholders", $arr_parameters);
		}
		
		foreach ($arr_parameter_names as $parameter_name){
			$param = $arr_parameters[$parameter_name];
			$expected_type = $arr_expected_types[$parameter_name];
			
			// if we didn't pass an expected type, we don't need one
			if (!is_null($expected_type)){
				$success = $dataset_container->bindValue(
				":".$parameter_name, $param, $expected_type);
			}
			else {
				$success = $dataset_container->bindValue(
				":".$parameter_name, $param);
			}

			if (!$success){
				$this->result = null;
				$this->error = self::get_fail_message($query_with_placeholders,
				 $arr_parameters) . " - bind failed for $parameter_name.";
				$this->log->logError($this->error);
				return false;
			}
		}
		
		$start_time = microtime(true);
		try {
			$final_success = $dataset_container->execute();
		} catch(PDOException $e) {
			// Error handled below
		}
		if ($debug){
			//$this->log->logInfo('Query runtime: '.(microtime(true)-$start_time).' seconds');
		}
		
		if ($final_success){
			$this->result = $dataset_container;
			$this->error = null;
			if($return_result){
				return $this->get_all_rows();
			}
			else {
				return true;
			}
		}
		else {
			$errors = $dataset_container->errorInfo();
			$this->result = null;
			$this->error = $errors[2];
			//$this->log_mysql_error($errors[2], $query_with_placeholders,
			//	$arr_parameters);
			return false;
		}
	}
	
	/**
	 * Get the next row to our result: fetch_both is both indexes
	 * @param $associative	true for resulting columns as associative array,
	 * false for numeric. Default true.
	 * 
	 */
	function get_next_row($associative=true){
		$fetchAs = $associative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
		return ($this->result) ? $this->result->fetch($fetchAs) : null;
	}
	
	/**
	 * Get all rows to our result
	 * @param $associative	true for resulting columns as associative array,
	 * false for numeric. Default true.
	 */
	function get_all_rows($associative=true){
		$fetchAs = $associative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
		return ($this->result) ? $this->result->fetchAll($fetchAs) : null;
	}
	
	/**
	 * Returns the number of rows affected by the last SQL statement
	 * @return int
	 */
	function get_num_rows(){
		return ($this->result) ? $this->result->rowCount() : 0;
	}
	
	/**
	 * Get last ID inserted into DB. Returns string 0 if nothing inserted/updated
	 * @return mixed String of id on success, -1 if no results found
	 */
	function get_last_insert_id(){
		return ($this->result) ? $this->lastInsertId() : -1;
	}
	
	/**
	 * Write MYSQL error to custom log with error messsage, query, parameters shown
	 */
	/*private function log_mysql_error($error, $query, $arr_parameters = array()){
		$to_write_a_p = array();
		foreach ($arr_parameters as $k => $v){
			$to_write_a_p[] = $k." => ".$v;
		}
		//$this->log->logError("MySQL Error: $error\nQuery: \"$query\"", $to_write_a_p);
		if (($start = strpos($error, 'Data too long for column')) !== false){
			echo "Database error: ".substr($error, $start, strpos(substr($error, $start+25), ' ')+25).' ';
		}
		else{
			echo "A Database error occurred. ";
		}
	}*/
	
}
