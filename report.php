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
        <h1>報表介面</h1>
        <div class="col-sm-12">
          <div class="well clearfix">
         <?php include 'menu.php'; ?>

       </div>
       	<select id="period" class="form-control" ></select>
        <BR/>
        <a class="btn btn-primary btn-lg" href="response_report.php?action=&m=<?php echo date("Y-m");?>  "> 全部員工 </a> <BR/>
        <a class="btn btn-primary btn-lg" href="response_report.php?action=month&m=<?php echo date("Y-m");?> "> 每月薪金 </a><BR/>
        <a class="btn btn-primary btn-lg" href="response_report.php?action=company&m=<?php echo date("Y-m");?> "> 公司報表 </a><BR/>
        <a class="btn btn-primary btn-lg" href="response_report.php?action=year&m=<?php echo date("Y-m");?> "> 全年薪金 </a><BR/>
        <a class="btn btn-primary btn-lg" href="template.xls"> 報稅範本 </a><BR/>


     </div>
   </div>

   <script>

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

       }

       $( document ).ready(function() {

          genPeriod();
          $("#period option:last").attr("selected", "selected");
         $( "#period" ).change(function() {

           $(".btn").each(function(index){
             var newvalue =$( this ).attr("href").split("&m")[0] +"&m="+  $( "#period" ).val()
             $( this ).attr("href",newvalue)

           });

         });

       });


   </script>
</body>
</html>
