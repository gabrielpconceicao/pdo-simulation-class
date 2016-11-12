<?php 

	/* Gabriel Conceição - PDO Simulation | Workaround for PDO webserver no support - gabriel_7340@hotmail.com */

	class PDO { 
		public $_conn = '';
		public $_query = '';
		public $_data = '';

		/* PDO Constants */
		const FETCH_ASSOC = '';
		const PARAM_STR = '';
		const PARAM_INT = '';

		public function bindValue( $field, $data ) { 
			$this->_query = str_replace( $field, "'".$data."'", $this->_query );
		}
		
		public function bindParam( $field, $data ) { 
			$this->_query = str_replace( $field, "'".$data."'", $this->_query );
		} 
	
		public function prepare( $q ) { 
			$this->_conn = mysql_connect( 'localhost', 'user', 'pass' );
	
			mysql_set_charset('utf8', $this->_conn);
			mysql_select_db( 'database' );  

			$this->_query = $q;
			return $this;
		} 

		public function execute() { 

			$this->_data = mysql_query( $this->_query );
			return $this->_data;
		} 

		public function fetch() { 
			while ( $row = mysql_fetch_array( $this->_data ) ) {  
				return $row;
			} 
		} 

		public function setAttribute(){
			return $this;
		}

		public function rowCount(){
			return mysql_num_rows( $this->_data );
		}
	}  
?>
