<?php 	include('session.php'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
<link href="dist/jquery.bootgrid.css" rel="stylesheet" />
<script src="dist/jquery-1.11.1.min.js"></script>
<script src="dist/bootstrap.min.js"></script>
<script src="dist/jquery.bootgrid.min.js"></script>
<script src="dist/jQuery.resizableColumns.min.js"></script>
<style>
.bootgrid-table2{
display: block;
width: 1200px;
overflow-x: scroll;
}
</style>
</head>
<body>

	<div class="container">
      <div class="">
        <h1>薪金介面</h1>
        <div class="col-sm-12">
		<div class="well clearfix">
			   <?php include 'menu.php'; ?>
			<div class="pull-right"></div></div>

			<div id="periodHTML" class="actions btn-group">
				<select id="period" class="form-control"></select>
		  </div>

<div class="table-responsive">
		<table id="salary_grid" class="table table-condensed table-hover table-striped" width="100%" cellspacing="0" data-toggle="bootgrid">
			<thead>
				<tr>
				  <th data-column-id="id" data-type="numeric"  data-identifier="true" >員工編號</th>
					<th data-column-id="salary_staff">姓名</th>
					<th data-column-id="salary_staffcomment">員工備註</th>
					<th data-column-id="salary_monthly" >總金</th>
					<th data-column-id="salary_check" data-type="numeric" data-formatter="textbox">支票</th>
					<th data-column-id="salary_cashcheck" data-type="numeric" data-formatter="textbox">現票</th>
					<th data-column-id="salary_paid" data-type="numeric" data-formatter="textbox">現金</th>
					<th data-column-id="salary_transfer" data-type="numeric" data-formatter="textbox">轉帳</th>
					<th data-column-id="salary_jclub" data-type="numeric" data-formatter="textbox">馬會</th>
					<th data-column-id="salary_add" data-type="numeric" data-formatter="textbox">補錢</th>
					<th data-column-id="salary_minus" data-type="numeric" data-formatter="textbox">扣錢</th>
					<th data-column-id="salary_comment" data-formatter="comment">備註</th>
          <th data-column-id="salary_remian">餘數</th>
					<th data-column-id="salary_month">年/月份</th>
				</tr>
			</thead>
		</table>
	</div>
    </div>
      </div>
    </div>

</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {
	var grid = $("#salary_grid").bootgrid({
		ajax: true,
		rowSelect: true,
		rowCount:-1,
		post: function ()
		{
			return {
				id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
			};
		},

		url: "response_salary.php?d=" + dataperiod,
		formatters: {
        "textbox": function(column, row)
        {
            return "<input id='"+ row.id  + column.id +"' name='"+ row.id +"," + column.id +"' onblur='updateSalary(this)' type='text' size='3' onkeypress='return isNumberKey(event)' maxlength='5' value="+row[column.id]+"></input>"
        },
				"comment": function(column, row)
        {
            return "<input id='"+ row.id + column.id +"' name='"+ row.id +"," + column.id +"' onblur='updateSalary(this)' type='text' size='10' maxlength='250' value="+row[column.id]+"></input>"
        }
    }
   }).on("loaded.rs.jquery.bootgrid", function()
{

});

function ajaxAction(action) {

				data = $("#frm_"+action).serializeArray();
				$.ajax({
				  type: "POST",
				  url: "response_salary.php",
				  data: data,
				  dataType: "json",
				  success: function(response)
				  {
					$("#salary_grid").bootgrid('reload');
				  }
				});
			}


});

$(function(){
  $('table').resizableColumns();
})

function updateSalary(o)
{

			$("#"+o.id).css("border","1px solid red");
		$.ajax({
			type: "POST",
			url: "response_salary.php?action=edit&d="+dataperiod,
			data: { id: o.name, value: o.value },
			dataType: "json",
			success: function(response)
			{
				$("#"+o.id).css("border","0.5px solid grey");
				console.log(response);
			}
		});

}




function monthDiff(d1, d2) {
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth() + 1;
    months += d2.getMonth();
    return months <= 0 ? 0 : months;
}

function genPeriod(){
var d =new Date("2018-5-01")
var cd = new Date();
var a = [];
var c = monthDiff(d,cd)+1;

for (i=0 ;i<c;i++){
d.setMonth(d.getMonth()+1)
a.push(d.getFullYear() +"-"+ ("0" + (d.getMonth() + 1)).slice(-2));
}
	$.each(a, function(key, value) {
	    $('#period').append($("<option/>", {
	        value: value,
	        text: value
	    }));
	});
$('#period').val(dataperiod)
}

function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31
				&& (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
<?php
if (empty($_GET["d"]) || trim($_GET["d"]) =="" ){

		$datewhere= date("Y-m");
}else{

		$datewhere =$_GET["d"];
}


echo "var dataperiod='". $datewhere."';\r\n";
?>
$( document ).ready(function() {
	genPeriod();
	jQuery("#periodHTML").detach().appendTo('.actionBar');
	$("#periodHTML").change(function () {
			var url = window.location.href.split('?')[0];
			window.location.href = url + "?d=" + $("#periodHTML option:selected").text();
	});
});

</script>
