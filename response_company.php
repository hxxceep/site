
<?php
	//include connection file
	include_once("connection.php");

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
	 $empCls->getEmployees($params);
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

	public function getEmployees($params) {

		$this->data = $this->getRecords($params);

		echo json_encode($this->data);
	}
		function insertEmployee($params) {
		$data = array();;
		$sql = "INSERT INTO `company` (`company_name`, `company_place`, `company_time`, `company_price`, `company_contact`, `company_comment`) VALUES";
		$sql .= "('" ;
		$sql .=		mysqli_real_escape_string($this->conn,$params["company_name"]) . "', '" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["company_place"]) . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["company_time"])  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["company_price"])  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["company_contact"])  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["company_comment"]) ;
		$sql .= 	"');  ";
//print($sql);die;
		echo $result = mysqli_query($this->conn, $sql) or die("error to insert employee data");

	}


	function getRecords($params) {
		$rp = isset($params['rowCount']) ? $params['rowCount'] : 10;

		if (isset($params['current'])) { $page  = $params['current']; } else { $page=1; };
        $start_from = ($page-1) * $rp;

		$sql = $sqlRec = $sqlTot = $where = '';

		if( !empty($params['searchPhrase']) ) {
			$where .=" WHERE ";
			$where .=" ( company_name LIKE '".$params['searchPhrase']."%' ";
			$where .=" OR company_place LIKE '".$params['searchPhrase']."%' ";
			$where .=" OR company_contact LIKE '".$params['searchPhrase']."%' )";
	   }
	   if( !empty($params['sort']) ) {
			$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
		}
	   // getting total number records without any search
		$sql = "SELECT * FROM `company` ";
		$sqlTot .= $sql;
		$sqlRec .= $sql;

		//concatenate search sql if value exist
		if(isset($where) && $where != '') {

			$sqlTot .= $where;
			$sqlRec .= $where;
		}
		if ($rp!=-1)
		$sqlRec .= " LIMIT ". $start_from .",".$rp;


		$qtot = mysqli_query($this->conn, $sqlTot) or die("error to fetch tot employees data"  .$sqlTot);
		$queryRecords = mysqli_query($this->conn, $sqlRec) or die("error to fetch employees data");

		while( $row = mysqli_fetch_assoc($queryRecords) ) {
			$data[] = $row;
		}

		$json_data = array(
			"current"            => intval($params['current']),
			"rowCount"            => 10,
			"total"    => intval($qtot->num_rows),
			"rows"            => $data   // total data array
			);

		return $json_data;
	}
	function updateEmployee($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "Update company set ";
		$sql .="company_name = '". 		mysqli_real_escape_string($this->conn,$params["edit_company_name"]) . "',";
		$sql .="company_place= '" . 	mysqli_real_escape_string($this->conn,$params["edit_company_place"]) ."',";
		$sql .="company_time=  '" . 	mysqli_real_escape_string($this->conn,$params["edit_company_time"]) ."',";
		$sql .="company_price= '" . 	mysqli_real_escape_string($this->conn,$params["edit_company_price"]) ."',";
		$sql .="company_contact='" . 	mysqli_real_escape_string($this->conn,$params["edit_company_contact"]) ."',";
		$sql .="company_comment='" . 	mysqli_real_escape_string($this->conn,$params["edit_company_comment"]) ."' ";
		$sql .="WHERE cid='".$_POST["edit_id"]."'";
		//print_R($_POST);
		//print_R($sql);die;
		echo $result = mysqli_query($this->conn, $sql) or die("error to update employee data");
	}

	function deleteEmployee($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `company` WHERE cid='".mysqli_real_escape_string($this->conn,$params["id"])."'";

		echo $result = mysqli_query($this->conn, $sql) or die("error to delete employee data");
	}
}
?>
