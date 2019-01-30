
<?php
	//include connection file
	include_once("connection.php");

	$db = new dbObj();
	$connString =  $db->getConnstring();

	$params = $_REQUEST;

	$action = isset($params['action']) != '' ? $params['action'] : '';
	$empCls = new staff($connString);

	switch($action) {
	 case 'add':
		$empCls->insertstaff($params);
	 break;
	 case 'edit':
		$empCls->updatestaff($params);
	 break;
	 case 'delete':
		$empCls->deletestaff($params);
	 break;
	 default:
	 $empCls->getstaffs($params);
	 return;
	}


	class staff {
	protected $conn;
	protected $data = array();
	function __construct($connString) {
		$this->conn = $connString;
		mysqli_query($this->conn, "SET NAMES 'utf8'");
		mysqli_query($this->conn, 'SET character_set_connection=utf8');
		mysqli_query($this->conn, 'SET character_set_client=utf8');
		mysqli_query($this->conn, 'SET character_set_results=utf8');
	}

	public function getstaffs($params) {

		$this->data = $this->getRecords($params);

		echo json_encode($this->data);
	}
		function insertstaff($params) {
		$data = array();;
		$sql = "INSERT INTO `staff`( `staff_name`,`staff_name_chi`, `staff_phone`, `staff_hkid`, `staff_sex`,`staff_dob`, `staff_district`, `staff_paymethod`,`staff_remark`) VALUES ";
		$sql .= "('" ;
		$sql .=		mysqli_real_escape_string($this->conn,str_replace(",",'',$params["staff_name"])) . "', '" ;
		$sql .=		mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_name_chi"])) . "', '" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_phone"])) . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_hkid"]))  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_sex"]))  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_dob"]))  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_district"]))  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,str_replace(" ","",$params["staff_paymethod"]))  . "','" ;
		$sql .=  	mysqli_real_escape_string($this->conn,$params["staff_remark"]) ;
		$sql .= 	"');  ";
//print($sql);die;
		echo $result = mysqli_query($this->conn, $sql) or die("{error:'重複名字'}");

	}


	function getRecords($params) {
		$rp = isset($params['rowCount']) ? $params['rowCount'] : 50;

		if (isset($params['current'])) { $page  = $params['current']; } else { $page=1; };
        $start_from = ($page-1) * $rp;

		$sql = $sqlRec = $sqlTot = $where = '';

		if( !empty($params['searchPhrase']) ) {
			$where .=" WHERE ";
			$where .=" ( staff_name LIKE '".$params['searchPhrase']."%' ";
			$where .=" OR staff_phone LIKE '".$params['searchPhrase']."%' ";
			$where .=" OR staff_district LIKE '".$params['searchPhrase']."%' ";
			$where .=" OR staff_paymethod LIKE '".$params['searchPhrase']."%' )";
	   }
	   if( !empty($params['sort']) ) {
			$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
		}
	   // getting total number records without any search
		$sql = "SELECT CONCAT('WK',`staff_id`) as staff_id, `staff_name`, `staff_name_chi`, `staff_phone`, `staff_hkid`, `staff_district`, `staff_paymethod`, `staff_remark`, `staff_sex`, `staff_dob` FROM `staff` ";
		$sqlTot .= $sql;
		$sqlRec .= $sql;

		//concatenate search sql if value exist
		if(isset($where) && $where != '') {

			$sqlTot .= $where;
			$sqlRec .= $where;
		}
//		if ($rp!=-1)
	//	$sqlRec .= " LIMIT ". $start_from .",".$rp;


		$qtot = mysqli_query($this->conn, $sqlTot) or die("error to fetch tot staffs data"  .$sqlTot);
		$queryRecords = mysqli_query($this->conn, $sqlRec) or die("error to fetch staffs data");

		while( $row = mysqli_fetch_assoc($queryRecords) ) {
			$data[] = $row;
		}

		$json_data = array(
			"current"            => intval($params['current']),
			"rowCount"            => 50,
			"total"    => intval($qtot->num_rows),
			"rows"            => $data   // total data array
			);

		return $json_data;
	}
	function updatestaff($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "Update staff set ";
		$sql .="staff_name = '". 		mysqli_real_escape_string($this->conn,str_replace(",",'',$params["edit_staff_name"])) . "',";
		$sql .="staff_name_chi = '". 		mysqli_real_escape_string($this->conn,$params["edit_staff_name_chi"]) . "',";
		$sql .="staff_phone= '" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_phone"]) ."',";
		$sql .="staff_hkid=  '" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_hkid"]) ."',";
		$sql .="staff_sex=  '" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_sex"]) ."',";
		$sql .="staff_dob=  '" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_dob"]) ."',";
		$sql .="staff_district= '" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_district"]) ."',";
		$sql .="staff_paymethod='" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_paymethod"]) ."', ";
		$sql .="staff_remark='" . 	mysqli_real_escape_string($this->conn,$params["edit_staff_remark"]) ."' ";
		$sql .=" WHERE staff_id='".mysqli_real_escape_string($this->conn,str_replace("WK","" ,$_POST["edit_staff_id"]))."'";
		//print_R($_POST);
		//print_R($sql);die;
		echo $result = mysqli_query($this->conn, $sql) or die("error to update staff data");
	}

	function deletestaff($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `staff` WHERE staff_id='".mysqli_real_escape_string($this->conn,str_replace("WK","" ,$params["id"]))."'";

		echo $result = mysqli_query($this->conn, $sql) or die("error to delete staff data");
	}
}
?>
