<?php 

	/* Gabriel Conceição - PDO Simulation | Workaround for webserver no support
	   https://github.com/gabrielpconceicao/pdo-simulation-class */

	class PDO { 
		public $_conn = '';
		public $_query = '';
		public $_request = '';
		public $_data = array();
		public $_arguments = '';
		public $_charset = '';

		const FETCH_ASSOC = 'FETCH_ASSOC';
		const PARAM_STR = 'PARAM_STR';
		const PARAM_INT = 'PARAM_INT';

		function __construct( $args ) {
			$this->_arguments = explode(";", $args );

			foreach( $this->_arguments as $key => $val ){
				if( strpos( $val, 'charset=') !== false )
					$this->_charset = str_replace( 'charset=', '', $val);
			}
		}

		public function bindValue( $field, $data, $type ) {
			$data = ($type == 'PARAM_INT' && gettype( $data ) != 'string') ? $data : sprintf("'%s'", $data);
			$this->_query = str_replace( $field, $data, $this->_query );
		}
 
 		public function bindParam( $field, $data, $type ) { 
 			$data = ($type == 'PARAM_INT' && gettype( $data ) != 'string') ? $data : sprintf("'%s'", $data);
 			$this->_query = str_replace( $field, $data, $this->_query );
		} 

		public function prepare( $q ) { 
			$this->_conn = mysql_connect( 'localhost', 'root', 'pass' );
			mysql_set_charset( $this->_charset, $this->_conn);
						
			mysql_query("SET NAMES '".$this->_charset."'");
			mysql_query("SET CHARACTER SET ".$this->_charset );
			mysql_query("SET COLLATION_CONNECTION = '".$this->_charset."_unicode_ci'");

			mysql_select_db( 'database' );  

			$this->_query = $q;
			return $this;
		}

		public function execute() { 
			$this->_data = array();
			$this->_request = mysql_query( $this->_query );

			while ( $row = mysql_fetch_assoc( $this->_request ) ) {  
				array_push( $this->_data, $row );
			}
			
			$this->_request = mysql_query( $this->_query );

			return $this->_data;
		} 

		public function fetch() { 
			return mysql_fetch_assoc( $this->_request );
			
		}
		
	    public function fetchAll() { 
			return $this->_data;
			
		} 

		public function setAttribute(){
			return $this;
		}

		public function rowCount(){
			return mysql_num_rows( $this->_request );
		}
	}
?>
