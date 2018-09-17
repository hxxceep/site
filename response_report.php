<?php
	//include connection file
	include_once("connection.php");
	include_once('session.php');
	$db = new dbObj();
	$connString =  $db->getConnstring();

	$params = $_REQUEST;

	$action = isset($params['action']) != '' ? $params['action'] : '';
	$empCls = new Employee($connString);

	switch($action) {
	 case 'add':
		$empCls->insertEmployee($params);
	 break;
	 case 'edit':
		$empCls->updateEmployee($params);
	 break;
	 case 'delete':
		$empCls->deleteEmployee($params);
	 break;
	 default:
	 $empCls->getRecords($params);
	 return;
	}


	class Employee {
	protected $conn;
	protected $data = array();
	function __construct($connString) {
		$this->conn = $connString;
		mysqli_query($this->conn, "SET NAMES 'utf8'");
		mysqli_query($this->conn, 'SET character_set_connection=utf8');
		mysqli_query($this->conn, 'SET character_set_client=utf8');
		mysqli_query($this->conn, 'SET character_set_results=utf8');
	}


	function getRecords($params) {
echo "\xEF\xBB\xBF";
		$select = "SELECT * FROM staff";
		$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );

		while ($property = mysqli_fetch_field($export)) {
    $header .= $property->name . ",";
		}

		while( $row = mysqli_fetch_row( $export ) )
		{
		    $line = '';
		    foreach( $row as $value )
		    {
		        if ( ( !isset( $value ) ) || ( $value == "" ) )
		        {
		            $value = ",";
		        }
		        else
		        {
		            $value = str_replace( '"' , '""' , $value );
		            $value = '"' . $value . '"' . ",";
		        }
		        $line .= $value;
		    }
		    $data .= trim( $line ) . "\n";
		}
		//$data = str_replace( "\r" , "" , $data );

		if ( $data == "" )
		{
		    $data = "\n(0) Records Found!\n";
		}


//	header("Content-type: application/octet-stream");


// force download
header("Content-type: text/csv");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

 header(sprintf( 'Content-Disposition: attachment; filename=my-csv-%s.csv', date( 'dmY-His' ) ) );
 header("Content-Transfer-Encoding: binary");


//echo mb_convert_encoding($data , "Big5" , "UTF-8");
	print "$header\n$data";
	}




}
?>
