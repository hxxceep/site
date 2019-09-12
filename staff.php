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
        <h1>員工介面</h1>
        <div class="col-sm-12">
		<div class="well clearfix">
			   <?php include 'menu.php'; ?>
			<div class="pull-right"><button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">
			<span class="glyphicon glyphicon-plus"></span> 新增</button></div></div>
		<table data-resizable="true" id="staff_grid" class="table table-condensed table-hover table-striped" width="60%" cellspacing="0" data-toggle="bootgrid">
			<thead>
				<tr>
					<th data-column-id="staff_id" data-type="numeric" data-identifier="true" >員工編號</th>
					<th data-column-id="staff_name">姓名(英)</th>
					<th data-column-id="staff_name_chi">姓名(中)</th>
					<th data-column-id="staff_phone">電話</th>
					<th data-column-id="staff_hkid">身份證</th>
          <th data-column-id="staff_district">居住地區</th>
          <th data-column-id="staff_paymethod">付款方法</th>
					<th data-column-id="staff_sex">性别</th>
					<th data-column-id="staff_dob">生日</th>
					<th data-column-id="staff_remark">備註</th>
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
                <h4 class="modal-title">添加員工</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_add">
									<input type="hidden" value="add" name="action" id="action">
                  <div class="form-group">
                    <label for="name" class="control-label">員工編號:</label>
                    <input type="text" class="form-control " id="staff_id" name="staff_id"  READONLY />
                  </div>
                  <div class="form-group">
                    <label for="staff" class="control-label">姓名(英):</label>
                    <input type="text" class="form-control" id="staff_name" name="staff_name" 	/>
                  </div>
				  <div class="form-group">
                    <label for="staff" class="control-label">姓名(中):</label>
                    <input type="text" class="form-control" id="staff_name_chi" name="staff_name_chi" 	/>
                  </div>

									<div class="form-group">
                    <label for="sex" class="control-label">性别</label>
										<select class="form-control"  id="staff_sex" name="staff_sex">
											<option></option>
											<option>M</option>
											<option>F</option>
										</select>
                  </div>
									<div class="form-group">
										<label for="staff" class="control-label">電話:</label>
										<input type="text" class="form-control" id="staff_phone" name="staff_phone"/>
									</div>
									<div class="form-group">
										<label for="staff" class="control-label">身份證:</label>
										<input type="text" class="form-control" id="staff_hkid" name="staff_hkid" />
									</div>

									<div class="form-group">
							  		<label for="sel1">居住地區:</label>
									  <select class="form-control"  id="staff_district" name="staff_district">
											<option></option>
											<option>中西區</option>
											<option>東區</option>
											<option>南區</option>
											<option>灣仔</option>
											<option>九龍城</option>
											<option>觀塘</option>
											<option>深水埗</option>
											<option>黃大仙</option>
											<option>油尖旺</option>
											<option>離島</option>
											<option>葵青</option>
											<option>北區</option>
											<option>西貢</option>
											<option>沙田</option>
											<option>大埔</option>
											<option>荃灣</option>
											<option>屯門</option>
											<option>元朗</option>
											<option>天水圍</option>
									  </select>
									</div>
									<div class="form-group">
										<label for="staff" class="control-label">生日</label>
												<input type="text" class="form-control" id="staff_dob" name="staff_dob"/>
									</div>
									<div class="form-group">
										<label for="staff" class="control-label">付款方法:</label>
												<input type="text" class="form-control" id="staff_paymethod" name="staff_paymethod"/>
									</div>
									<div class="form-group">
										<label for="remark" class="control-label">備註:</label>
												<input type="text" class="form-control" id="staff_remark" name="staff_remark"/>
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
                <h4 class="modal-title">Edit staff</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_edit">
				<input type="hidden" value="edit" name="action" id="action">
				<input type="hidden" value="0" name="edit_id" id="edit_id">
											<div class="form-group">
												<label for="name" class="control-label">員工編號:</label>
												<input type="text" class="form-control " id="edit_staff_id" name="edit_staff_id"  READONLY />
											</div>
											<div class="form-group">
												<label for="staff" class="control-label">姓名(英):</label>
												<input type="text" class="form-control" id="edit_staff_name" name="edit_staff_name" 	/>
											</div>
											<div class="form-group">
												<label for="staff" class="control-label">姓名(中):</label>
												<input type="text" class="form-control" id="edit_staff_name_chi" name="edit_staff_name_chi" 	/>
											</div>
											<div class="form-group">
												<label for="sex" class="control-label">性别</label>
												<select class="form-control"  id="edit_staff_sex" name="edit_staff_sex">
													<option></option>
													<option>M</option>
													<option>F</option>
												</select>
											</div>
											<div class="form-group">
												<label for="staff" class="control-label">電話:</label>
												<input type="text" class="form-control" id="edit_staff_phone" name="edit_staff_phone"/>
											</div>
											<div class="form-group">
												<label for="staff" class="control-label">身份證:</label>
												<input type="text" class="form-control" id="edit_staff_hkid" name="edit_staff_hkid" />
											</div>

											<div class="form-group">
									  		<label for="staff">居住地區:</label>
											  <select class="form-control" id="edit_staff_district" name="edit_staff_district"/>
												<option></option>
												<option>中西區</option>
												<option>東區</option>
												<option>南區</option>
												<option>灣仔</option>
												<option>九龍城</option>
												<option>觀塘</option>
												<option>深水埗</option>
												<option>黃大仙</option>
												<option>油尖旺</option>
												<option>離島</option>
												<option>葵青</option>
												<option>北區</option>
												<option>西貢</option>
												<option>沙田</option>
												<option>大埔</option>
												<option>荃灣</option>
												<option>屯門</option>
												<option>元朗</option>
												<option>天水圍</option>
											  </select>
											</div>

											<div class="form-group">
												<label for="staff" class="control-label">生日</label>
														<input type="text" class="form-control" id="edit_staff_dob" name="edit_staff_dob"/>
											</div>
											<div class="form-group">
												<label for="staff" class="control-label">付款方法:</label>
														<input type="text" class="form-control" id="edit_staff_paymethod" name="edit_staff_paymethod"/>
											</div>
											<div class="form-group">
												<label for="remark" class="control-label">備註:</label>
														<input type="text" class="form-control" id="edit_staff_remark" name="edit_staff_remark"/>
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
	var grid = $("#staff_grid").bootgrid({
		ajax: true,
		rowSelect: true,
		post: function ()
		{
			/* To accumulate custom parameter with the request object */
			return {
				id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
			};
		},

		url: "response_staff.php",
		formatters: {
		        "commands": function(column, row)
		        {
		            return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.staff_id + "\"><span class=\"glyphicon glyphicon-edit\"></span></button> " +
		                "<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.staff_id + "\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
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

		$('#edit_model').modal('show');
					if($(this).data("row-id") !="") {

                      // collect the data
                      $('#edit_staff_id').val(ele.siblings(':first').html()); // in case we're changing the key
                      $('#edit_staff_name').val(ele.siblings(':nth-of-type(2)').html());
											$('#edit_staff_name_chi').val(ele.siblings(':nth-of-type(3)').html());
                      $('#edit_staff_phone').val(ele.siblings(':nth-of-type(4)').html());
                      $('#edit_staff_hkid').val(ele.siblings(':nth-of-type(5)').html());
											$('#edit_staff_district').val(ele.siblings(':nth-of-type(6)').html());
											$('#edit_staff_paymethod').val(ele.siblings(':nth-of-type(7)').html());
											$('#edit_staff_dob').val(ele.siblings(':nth-of-type(9)').html());
											$('#edit_staff_remark').val(ele.siblings(':nth-of-type(10)').html());
											$('#edit_staff_sex').val(ele.siblings(':nth-of-type(8)').html());
					} else {
					 alert('Now row selected! First select row, then click edit button');
					}
    }).end().find(".command-delete").on("click", function(e)
    {

		var conf = confirm('確定刪除?');
					//alert(conf);
                    if(conf){
                                $.post('response_staff.php', { id: $(this).data("row-id"), action:'delete'}
                                    , function(){
                                        // when ajax returns (callback),
										$("#staff_grid").bootgrid('reload');
                                });
								//$(this).parent('tr').remove();
								//$("#staff_grid").bootgrid('remove', $(this).data("row-id"))
                    }
    });
});

function ajaxAction(action) {
				data = $("#frm_"+action).serializeArray();
				$.ajax({
				  type: "POST",
				  url: "response_staff.php",
				  data: data,
				  dataType: "json",
				  success: function(response)
				  {
					$('#'+action+'_model').modal('hide');
					$("#staff_grid").bootgrid('reload');
					$('#frm_add').trigger("reset");
					},
					error:function(response) {
	 					alert(response.responseText);
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
