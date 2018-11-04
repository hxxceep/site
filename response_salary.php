
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



	function getRecords($params) {
		$rp = isset($params['rowCount']) ? 1000 : 1000;
		$datewhere ="";
		if (isset($params['current'])) { $page  = $params['current']; } else { $page=1; };
        $start_from = ($page-1) * $rp;

		$sql = $sqlRec = $sqlTot = $where = '';

		if( !empty($params['searchPhrase']) ) {
		//	$where .=" DATE_FORMAT(`salary_month`,'%Y-%m') like '".$params['searchPhrase']."' ";
			$where .=" salary_staff LIKE '%".$params['searchPhrase']."%' 	";
	   }


		 /*
	   if( !empty($params['sort']) ) {
			$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
		}
		*/

		if (empty($_GET["d"]) || trim($_GET["d"]) =="" ){

				$datewhere= date("Y-m");
		}else{

				$datewhere =$_GET["d"];
		}



			   // getting total number records without any search
		$sql = "SELECT concat('WK',staff) as id , (select staff_name_chi from staff where staff_id= staff)  as salary_staff , ";
		$sql .= "(select staff_remark from staff where staff_id= staff)  as salary_staffcomment , ";
		$sql .="sum(m.salary_paid) as salary_paid, sum(`salary_OS`+ `salary_add`  - `salary_minus`) as salary_monthly,sum(`salary_OS` -`salary_transfer` - `salary_cashcheck`- 'salary_paid' + `salary_add` - `salary_check` - `salary_cash`-`salary_jclub`  - `salary_minus`) as salary_remian, ";
		$sql .="sum(salary_tax) as salary_tax, DATE_FORMAT(`salary_month`,'%Y-%m') as salary_month,";
		$sql .="sum(salary_check) as salary_check , sum(salary_cashcheck) as salary_cashcheck,  ";
		$sql .="sum(salary_cash) as salary_cash, sum(	salary_transfer) as salary_transfer, ";
		$sql .= "sum(salary_jclub) as salary_jclub , sum(salary_add) as salary_add, sum(`salary_minus`) as salary_minus ,";
		$sql .=" max(salary_comment) as salary_comment";
		$sql .=" FROM (SELECT * FROM `salary`  ";
    $sql .=" union all ";
		$sql .="SELECT * FROM `salary_paid`) m ";
		$sqlTot .= $sql;
		$sqlRec .= $sql;



		if ($rp!=-1)

		$sqlRec .= " where  DATE_FORMAT(`salary_month`,'%Y-%m')  = '".$datewhere.$where."'	 group by `staff` , DATE_FORMAT(`salary_month`,'%Y-%m')";

		//print $sqlRec	 ; die();
		$qtot = mysqli_query($this->conn, $sqlTot) or die("error to fetch tot employees data"  .$sqlTot);
		$queryRecords = mysqli_query($this->conn, $sqlRec) or die("error to fetch employees data");

		while( $row = mysqli_fetch_assoc($queryRecords) ) {
			$data[] = $row;
		}

		$json_data = array(
			"current"            => intval($params['current']),
			"rowCount"            => 100,
			"total"    => intval($qtot->num_rows),
			"rows"            => $data   // total data array
			);

		return $json_data;
	}
	function updateEmployee($params) {

		$staffkey = explode(",",$_POST["id"]);
		//print_R($_POST);die;
		$sql = "insert into salary_paid (staff, ". $staffkey[1] .", salary_month) values(";
		$sql .="'" .mysqli_real_escape_string($this->conn,str_replace("WK","",$staffkey[0])). "',";
		$sql .= "'" .mysqli_real_escape_string($this->conn,$_POST["value"]) ."', ";
		$sql .= "'" .mysqli_real_escape_string($this->conn,$_GET["d"]) ."-01')" ;
		$sql .="ON DUPLICATE KEY UPDATE ". $staffkey[1] ."= '".mysqli_real_escape_string($this->conn,$_POST["value"])."'";

		echo $result = mysqli_query($this->conn, $sql) or die("error to update employee data");
	}

	function deleteEmployee($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `company` WHERE cid='".mysqli_real_escape_string($this->conn,str_replace("WK","",$params["id"]))."'";

		echo $result = mysqli_query($this->conn, $sql) or die("error to delete employee data");
	}
}
?>
