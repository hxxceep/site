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
	 case 'year':
		$empCls->getYearSalary($params);
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

		$header = "公司,時間,單價,聯絡資料,備註,OT,";

		for($j=1;$j<=31;$j++){
			$header .= $j.",";
		}

			$cur_m = explode("-",$params['m']);
			$c_month = intval ($cur_m[1]);
			$c_year =intval ($cur_m[0]);


			$select =	"select gg.cid, comname,comtime, gg.start, count(gg.start) gc ,company_price  , company_comment ,company_contact ,company_name from (";
			$select .= " select * from (SELECT  substring_index(`title`,',',1) comname , substring_index(`title`,',',-1) comtime, day(`start`) as `start`  FROM `calendar` WHERE month(`start`) = ".$c_month." && YEAR(`start`) = ".$c_year." ) cal";
			$select .= " left join ";
			$select .= " (select * from company) com on cal.comname = com.`company_place` and cal.comtime = com.company_time order by cid , start) gg";
			$select .= " group by comname, gg.start";

			$select =	 "select ff.* , cc.ot from (select gg.cid, comname,comtime, gg.start, count(gg.start) gc ,company_price , company_comment ,company_contact ,company_name ,title from ";
			$select .= "( select * from (SELECT substring_index(`title`,',',1) comname , substring_index(`title`,',',-1) comtime, day(`start`) as `start` , `title` FROM `calendar` ";
			$select .= "WHERE month(`start`) = ".$c_month." && YEAR(`start`) = ".$c_year." ) cal left join ";
			$select .= "(select * from company) com on cal.comname = com.`company_place` and cal.comtime = com.company_time order by cid , start) gg ";
			$select .= "group by comname, gg.start) ff left join ";
			$select .= "(select sum(s.salary_OT) OT , pid , salary_month ,c.title from salary s left join calendar c on s.pid = c.id  ";
			$select .= "where s.`salary_OT` >0 and month(`start`) = ".$c_month." && YEAR(`start`) = ".$c_year." group by title ) cc on ff.title = cc.title ";

			$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );

				$array_com = array();
				$array_sum = array();

				while( $row = mysqli_fetch_row( $export ) )
				{
						$array_com[$row[0]] = $row[1]."," .$row[2]."," .$row[5].",".$row[6].",".$row[7].",".$row[10];
						$array_sum[$row[0].",".$row[3]] = $row[4];
				}

				//var_dump($array_com);
				//var_dump($array_sum);
				$data = "";

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
	$header = "員工,姓名,支票,現票,現金,轉帳,馬會,補錢,扣錢,備註,OT,";

	for($j=1;$j<=31;$j++){
		$header .= $j.",";
	}

	$cur_m = explode('-',$params['m']);
	$c_month = intval ($cur_m[1]);
	$c_year =intval ($cur_m[0]);



  $select2 =	" SELECT `staff`,`salary_check`,`salary_cashcheck`,`salary_paid`,`salary_transfer`,`salary_jclub` ,`salary_add`,`salary_minus`,`salary_comment`  FROM `salary_paid` WHERE year(`salary_month`) = ".$c_year." AND month(`salary_month`) =".$c_month;
	$arry_paid = array();
	$export2 = mysqli_query ( $this->conn, $select2 ) or die ( "Sql error : " . mysql_error( ) );

	while( $row2 = mysqli_fetch_row( $export2 ) )
	{
		$arry_paid[$row2[0]]= ",".$row2[1].",".$row2[2].",".$row2[3].",".$row2[4].",".$row2[5].",".$row2[6].",".$row2[7].",".$row2[8];
	}


	$select =	"select `staff` ,(select staff_name_chi from staff where staff.staff_id = s.staff) as `chinam`, `salary_OS` , day(`salary_month`) as `salary_month` ,`salary_OT` from salary s where MONTH(`salary_month`)= ".$c_month." && year(`salary_month`) = ".$c_year."";
	$select .= " order by CONVERT(SUBSTRING_INDEX(s.staff,'-',-1),UNSIGNED INTEGER) ,`salary_month` ASC";
	$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );
	//print $select ;die();
	 	$alert_array = array();
		$alert_array_OT = array();

				while( $row = mysqli_fetch_row( $export ) )
				{
						$alert_array["WK".$row[0].",".$row[1]][$row[3]] = $row[2];
						$alert_array_OT["WK".$row[0].",".$row[1]] += $row[4];
				}
				$data ="";
				//print_r($alert_array_OT);
				//die();
				//$data = str_replace( "\r" , "" , $data );
				//var_dump($alert_array);
				foreach($alert_array as $name => $value) {
					$sid = str_replace("WK", "" ,explode(",",$name)[0]);//	$data.=	$arry_paid[$row[0]];

					if(isset($arry_paid[$sid])){
					$data .= "\n".$name. $arry_paid[$sid].",".$alert_array_OT[$name];
					}else{
					$data .= "\n".$name .str_repeat(",",9 );
					}
					$i = 0;
			   	foreach ($value as $key2 => $value2) {

						while (intval ($key2) > $i ){
							$data .= ",";
							$i++;
						}
			   		$data .= $value2;
			   	}
			}


		//	print $data;
 			$this->exporttext($header,$data);
	}


	function getYearSalary($params){
			$header = "員工,姓名,支票,現票,現金,轉帳,馬會,補錢,扣錢,備註,OT,";
			$cur_m = explode('-',$params['m']);
			$c_month = intval ($cur_m[1]);
			$c_year =intval ($cur_m[0]);

			$data ="";
			$select = "SELECT sp.staff, (select staff_name_chi from staff where staff.staff_id = sp.staff) as `chinam` , sum(sp.salary_check) , sum(sp.salary_cashcheck), sum(sp.salary_paid) , sum(sp.salary_transfer), sum(sp.salary_jclub) ,sum(sp.salary_add) , sum(sp.salary_minus) FROM `salary_paid` sp where year(`salary_month`) =2019 group by staff";
			$export = mysqli_query ( $this->conn, $select ) or die ( "Sql error : " . mysql_error( ) );
				while( $row = mysqli_fetch_row( $export ) )
				{
						$data .= "\n". $row[0] .",". $row[1] .",". $row[2] .",". $row[3] .",". $row[4] .",". $row[5]
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
