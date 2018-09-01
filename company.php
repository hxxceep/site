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
<script>
var url ='response_company.php'
</script>
</head>
<body>
	<div class="container">
      <div class="">
        <h1>客戶介面</h1>

        <div class="col-sm-12">
		<div class="well clearfix">
   <?php include 'menu.php'; ?>

	<div class="pull-right"><button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">


			<span class="glyphicon glyphicon-plus"></span>客戶 </button></div></div>
		<table id="company_grid" class="table table-condensed table-hover table-striped" width="60%" cellspacing="0" data-toggle="bootgrid">
			<thead>
				<tr>

					<th data-column-id="company_name"  data-identifier="true" >名稱</th>
					<th data-column-id="company_place" style="width: 300px;">工作地點</th>
					<th data-column-id="company_time">時間</th>
					<th data-column-id="company_price">單價</th>
          <th data-column-id="company_contact">聯絡資料</th>
          <th data-column-id="company_comment">備註</th>
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
                <h4 class="modal-title">増加客戶</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_add">
				<input type="hidden" value="add" name="action" id="action">

                  <div class="form-group">
                    <label for="name" class="control-label">名稱:</label>
                    <input type="text" class="form-control" id="company_name" name="company_name"/>
                  </div>
                  <div class="form-group">
                    <label for="salary" class="control-label">工作地點:</label>
                    <input type="text" class="form-control" id="company_place" name="company_place"/>
                  </div>
									<div class="form-group">
											<label for="salary" class="control-label">時間:</label>
											<input type="text" class="form-control" id="company_time" name="company_time"/>
										</div>
				  					<div class="form-group">
                    <label for="salary" class="control-label">聯絡資料:</label>
                    <input type="text" class="form-control" id="company_contact" name="company_contact"/>
                  </div>
									<div class="form-group">
	                    <label for="salary" class="control-label">單價:</label>
	                    <input type="text" class="form-control" id="company_price" name="company_price"/>
	                  </div>

									<div class="form-group">
	                    <label for="salary" class="control-label">備註:</label>
	                    <input type="text" class="form-control" id="company_comment" name="company_comment"/>
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
                <h4 class="modal-title">修改客戶</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_edit">
				<input type="hidden" value="edit" name="action" id="action">
				<input type="hidden" value="0" name="edit_id" id="edit_id">
									<div class="form-group">
										<label for="name" class="control-label">名稱:</label>
										<input type="text" class="form-control" id="edit_company_name" name="edit_company_name"/>
									</div>
									<div class="form-group">
										<label for="salary" class="control-label">工作地點:</label>
										<input type="text" class="form-control" id="edit_company_place" name="edit_company_place"/>
									</div>
									<div class="form-group">
											<label for="salary" class="control-label">時間:</label>
											<input type="text" class="form-control" id="edit_company_time" name="edit_company_time"/>
										</div>
								<div class="form-group">
										<label for="salary" class="control-label">聯絡資料:</label>
										<input type="text" class="form-control" id="edit_company_contact" name="edit_company_contact"/>
									</div>
									<div class="form-group">
											<label for="salary" class="control-label">單價:</label>
											<input type="text" class="form-control" id="edit_company_price" name="edit_company_price"/>
										</div>
									<div class="form-group">
											<label for="salary" class="control-label">備註:</label>
											<input type="text" class="form-control" id="edit_company_comment" name="edit_company_comment"/>
										</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_edit" class="btn btn-primary">Save</button>
            </div>
			</form>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
$( document ).ready(function() {
	var grid = $("#company_grid").bootgrid({
		ajax: true,
		rowSelect: true,
		post: function ()
		{
			/* To accumulate custom parameter with the request object */
			return {
				id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
			};
		},

		url: url,
		formatters: {
		        "commands": function(column, row)
		        {
		            return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.cid + "\"><span class=\"glyphicon glyphicon-edit\"></span></button> " +
		                "<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.cid + "\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
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
            var g_name = $(this).parent().siblings(':nth-of-type(2)').html();
console.log(g_id);
                    console.log(g_name);

		//console.log(grid.data());//
		$('#edit_model').modal('show');
					if($(this).data("row-id")>0) {

                                // collect the data
																$('#edit_id').val($(this).data("row-id"));
                                $('#edit_company_name').val(ele.siblings(':first').html()); // in case we're changing the key
                                $('#edit_company_place').val(ele.siblings(':nth-of-type(2)').html());
                                $('#edit_company_contact').val(ele.siblings(':nth-of-type(5)').html());
                                $('#edit_company_price').val(ele.siblings(':nth-of-type(4)').html());
																$('#edit_company_time').val(ele.siblings(':nth-of-type(3)').html());
																$('#edit_company_comment').val(ele.siblings(':nth-of-type(6)').html());
					} else {
					 alert('Now row selected! First select row, then click edit button');
					}
    }).end().find(".command-delete").on("click", function(e)
    {

		var conf = confirm('Delete ' + $(this).data("row-id") + ' items?');

					alert(conf);
                    if(conf){
                                $.post('response_company.php', { id: $(this).data("row-id"), action:'delete'}
                                    , function(){
                                        // when ajax returns (callback),
										$("#company_grid").bootgrid('reload');
                                });
								//$(this).parent('tr').remove();
								//$("#company_grid").bootgrid('remove', $(this).data("row-id"))
                    }
    });
});

function ajaxAction(action) {
				data = $("#frm_"+action).serializeArray();
				$.ajax({
				  type: "POST",
				  url: url,
				  data: data,
				  dataType: "json",
				  success: function(response)
				  {
					$('#'+action+'_model').modal('hide');
					$("#company_grid").bootgrid('reload');
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
