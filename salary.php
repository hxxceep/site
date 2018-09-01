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
</head>
<body>
	<div class="container">
      <div class="">
        <h1>薪金介面</h1>
        <div class="col-sm-12">
		<div class="well clearfix">
			   <?php include 'menu.php'; ?>
			<div class="pull-right"></div></div>
		<table id="salary_grid" class="table table-condensed table-hover table-striped" width="60%" cellspacing="0" data-toggle="bootgrid">
			<thead>
				<tr>
				  <th data-column-id="id" data-type="numeric" >員工編號</th>
					<th data-column-id="salary_staff" data-identifier="true">姓名</th>
					<th data-column-id="salary_monthly">全月總金額</th>
					<th data-column-id="salary_paid">已付金額</th>
          <th data-column-id="salary_remian">餘數</th>
          <th data-column-id="salary_tax">報稅金額</th>
					<th data-column-id="salary_month">年/月份</th>
					<th data-column-id="commands" data-formatter="commands" data-sortable="false">命令</th>
				</tr>
			</thead>
		</table>
    </div>
      </div>
    </div>

<div id="add_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add salary</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_add">
				<input type="hidden" value="add" name="action" id="action">
                  <div class="form-group">
                    <label for="name" class="control-label">姓名:</label>
                    <input type="text" class="form-control " id="name" name="name"  READONLY />
                  </div>
                  <div class="form-group">
                    <label for="salary" class="control-label">全月總金額:</label>
                    <input type="text" class="form-control" id="salary_monthly" name="salary_monthly" READONLY	/>
                  </div>
									<div class="form-group">
										<label for="salary" class="control-label">已付金額:</label>
										<input type="text" class="form-control" id="salary_paid" name="salary_paid"/>
									</div>
									<div class="form-group">
										<label for="salary" class="control-label">餘數:</label>
										<input type="text" class="form-control" id="salary_remian" name="salary_remian" READONLY/>
									</div>
									<div class="form-group">
										<label for="salary" class="control-label">報稅金額:</label>
												<input type="text" class="form-control" id="salary_tax" name="salary_tax"/>
									</div>
									<div class="form-group">
										<label for="salary" class="control-label">月份:</label>
												<input type="text" class="form-control" id="salary_month" name="salary_month"/>
									</div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_add" class="btn btn-primary">Save</button>
            </div>
			</form>
        </div>
    </div>
</div>
<div id="edit_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">修改薪金</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_edit">
				<input type="hidden" value="edit" name="action" id="action">
				<input type="hidden" value="0" name="edit_id" id="edit_id">

								<div class="form-group">
									<label for="name" class="control-label">員工編號:</label>
									<input type="text" class="form-control " id="edit_staffid" name="edit_staffid"  READONLY />
								</div>
										<div class="form-group">
											<label for="name" class="control-label">姓名:</label>
											<input type="text" class="form-control " id="edit_staffname" name="edit_staffname"  READONLY />
										</div>
										<div class="form-group">
											<label for="salary" class="control-label">全月總金額:</label>
											<input type="text" class="form-control" id="edit_salary_monthly" name="edit_salary_monthly" READONLY	/>
										</div>
										<div class="form-group">
											<label for="salary" class="control-label">已付金額:</label>
											<input type="text" class="form-control" id="edit_salary_paid" name="edit_salary_paid" autocomplete="off"/>
										</div>
										<div class="form-group">
											<label for="salary" class="control-label">餘數:</label>
											<input type="text" class="form-control" id="edit_salary_remian" name="edit_salary_remian" READONLY/>
										</div>
										<div class="form-group">
											<label for="salary" class="control-label">報稅金額:</label>
													<input type="text" class="form-control" id="edit_salary_tax" name="edit_salary_tax" autocomplete="off"/>
										</div>
										<div class="form-group">
											<label for="salary" class="control-label">月份:</label>
													<input type="text" class="form-control" id="edit_month" name="edit_month" READONLY/>
										</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閂</button>
                <button type="button" id="btn_edit" class="btn btn-primary">儲存</button>
            </div>
			</form>
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
		post: function ()
		{
			/* To accumulate custom parameter with the request object */
			return {
				id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
			};
		},

		url: "response_salary.php",
		formatters: {
		        "commands": function(column, row)
		        {
		            return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"glyphicon glyphicon-edit\"></span></button> "
		        }
		    }
   }).on("loaded.rs.jquery.bootgrid", function()
{
    /* Executes after data is loaded and rendered */
    grid.find(".command-edit").on("click", function(e)
    {
        //alert("You pressed edit on row: " + $(this).data("row-id"));
			var ele =$(this).parent();
			var g_id = $(this).parent().siblings(':first').html();
            var g_name = $(this).parent().siblings(':nth-of-type(1)').html();


		//console.log(grid.data());//
		$('#edit_model').modal('show');
					if($(this).data("row-id") >0) {

                                // collect the data
                                $('#edit_id').val(ele.siblings(':first').html()); // in case we're changing the key
																$('#edit_staffid').val(ele.siblings(':first').html());
                                $('#edit_staffname').val(ele.siblings(':nth-of-type(2)').html());
                                $('#edit_salary_monthly').val(ele.siblings(':nth-of-type(3)').html());
																$('#edit_salary_paid').val(ele.siblings(':nth-of-type(4)').html());
                                $('#edit_salary_remian').val(ele.siblings(':nth-of-type(5)').html());
																$('#edit_salary_tax').val(ele.siblings(':nth-of-type(6)').html());
																$('#edit_month').val(ele.siblings(':nth-of-type(7)').html());
					} else {
					 alert('Now row selected! First select row, then click edit button');
					}
    }).end().find(".command-delete").on("click", function(e)
    {

		var conf = confirm('Delete ' + $(this).data("row-id") + ' items?');
					alert(conf);
                    if(conf){
                                $.post('response_salary.php', { id: $(this).data("row-id"), action:'delete'}
                                    , function(){
                                        // when ajax returns (callback),
										$("#salary_grid").bootgrid('reload');
                                });
								//$(this).parent('tr').remove();
								//$("#salary_grid").bootgrid('remove', $(this).data("row-id"))
                    }
    });
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
					$('#'+action+'_model').modal('hide');
					$("#salary_grid").bootgrid('reload');
				  }
				});
			}

			$( "#command-add" ).click(function() {
			  $('#add_model').modal('show');
			});
			$( "#btn_add" ).click(function() {
			  ajaxAction('add');
			});
			$( "#btn_edit" ).click(function() {
			  ajaxAction('edit');
			});
});

$(function(){
  $('table').resizableColumns();
})
</script>
