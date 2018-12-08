<?php
	//include connection file
	include_once("connection.php");
	include_once('session.php');
	$db = new dbObj();
	$connString =  $db->getConnstring();

	$params = $_REQUEST;

	$action = isset($params['action']) != '' ? $params['action'] : '';
	$empCls = new Employee($connString);
	echo "\xEF\xBB\xBF";
	switch($action) {
	 case 'month':
		$empCls->getAllStaffMonthSalary($params);
	 break;
	 case 'company':
		$empCls->getAllCompanyDayCount($params);
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



/*
	select `staff` ,(select staff_name_chi from staff where staff.staff_id = s.staff) `salary_OS` ,`salary_month` from salary s where MONTH(`salary_month`)= 11
	order by CONVERT(SUBSTRING_INDEX(s.staff,'-',-1),UNSIGNED INTEGER) ASC



	select comname,comtime, gg.start, count(gg.start) gc ,company_price  , company_comment ,company_contact ,company_name from (

	select * from
		(SELECT substring_index(`title`,',',1) comname , substring_index(`title`,',',-1) comtime, day(`start`) as `start`  FROM `calendar` WHERE month(`start`) = 11) cal
		left join
		(select * from company) com on cal.comname = com.`company_place` and cal.comtime = com.company_time
	    order by cid , start
	) gg

	group by comname, gg.start
*/

	function getAllCompanyDayCount($params){

		$header = "公司,時間,單價,聯絡資料,備註,";

		for($j=1;$j<=31;$j++){
			$header .= $j.",";
		}

			$cur_m = explode("-",$param['m']);
			$c_moneth = intval ($cur_m[1]);
			$c_yaer =intval ($cur_m[0]);


			$select =	"select gg.cid, comname,comtime, gg.start, count(gg.start) gc ,company_price  , company_comment ,company_contact ,company_name from (";
			$select .= " select * from (SELECT  substring_index(`title`,',',1) comname , substring_index(`title`,',',-1) comtime, day(`start`) as `start`  FROM `calendar` WHERE month(`start`) = ".$c_moneth." && year(`start`) = ".$c_yaer." ) cal";
			$select .= " left join ";
			$select .= " (select * from company) com on cal.comname = com.`company_place` and cal.comtime = com.company_time order by cid , start) gg";
			$select .= " group by comname, gg.start";

			$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );

			//	$array_com = array();
			//	$array_sum = array();

				while( $row = mysqli_fetch_row( $export ) )
				{
						$array_com[$row[0]] = $row[1]."," .$row[2]."," .$row[5].",".$row[6].",".$row[7];
						$array_sum[$row[0].",".$row[3]] = $row[4];
				}

				//var_dump($array_com);
				//var_dump($array_sum);
				$date = "";
					foreach($array_com as $key => $value) {
							$data .= "\n".$value.",";
							for($j=1;$j<=31;$j++){
								if(isset( $array_sum[$key.",".$j])){
									$data .= $array_sum[$key.",".$j].",";
								}else{
									$data .=",";
								}
							}

					}
					$this->exporttext($header,$data);

	}


	function getAllStaffMonthSalary($params)
	{
	$header = "員工,英名,";

	for($j=1;$j<=31;$j++){
		$header .= $j.",";
	}

	$select =	"select `staff` ,(select staff_name_chi from staff where staff.staff_id = s.staff) as `chinam`, `salary_OS` , day(`salary_month`) as `salary_month` from salary s where MONTH(`salary_month`)= ".$c_moneth." && year(`salary_month`) = ".$c_yaer."";
	$select .= " order by CONVERT(SUBSTRING_INDEX(s.staff,'-',-1),UNSIGNED INTEGER) ,`salary_month` ASC";
	$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );

	 	$alert_array = array();

				while( $row = mysqli_fetch_row( $export ) )
				{
						$alert_array["WK".$row[0].",".$row[1]][$row[3]] = $row[2];
				}
				$data ="";
				//$data = str_replace( "\r" , "" , $data );
				//var_dump($alert_array);
				foreach($alert_array as $name => $value) {
					$data .= "\n".$name;
					$i = 0;
			   	foreach ($value as $key2 => $value2) {

						while (intval ($key2) > $i ){
							$data .= ",";
							$i++;
						}

			   		$data .= $value2;
			   	}
			}

 			$this->exporttext($header,$data);
	}



	function getRecords($params) {

		$select = "SELECT * FROM staff";
		$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );

	//	while ($property = mysqli_fetch_field($export)) {
  //  $header .= $property->name . ",";
	//	}
		$header = "員工,英名, 中名,電話,身分證,居住地區,付款方法,備註";

		$this->exportCSV($header,$export);


	}


	function exporttext($header,$data){

		header("Content-type: text/csv");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header(sprintf( 'Content-Disposition: attachment; filename=my-csv-%s.csv', date( 'dmY-His' ) ) );
		header("Content-Transfer-Encoding: binary");


		//echo mb_convert_encoding($data , "Big5" , "UTF-8");
			print "$header\n$data";

	}


function exportCSV($header, $export){
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
