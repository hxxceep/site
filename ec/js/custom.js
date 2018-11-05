/************************
    Pickers
*************************/
$('#calendarModal').on('show.bs.modal', function() {

	$("#startDate").datepicker({dateFormat: "yy-mm-dd", minDate: new Date()});
	$("#endDate").datepicker({dateFormat: "yy-mm-dd", minDate: new Date()});

	$("#colorp").spectrum({
		preferredFormat: "hex",
		showPaletteOnly: true,
		togglePaletteOnly: true,
		togglePaletteMoreText: 'more',
		togglePaletteLessText: 'less',
		color: '#587ca3',
		palette: [
			["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
			["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
			["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
			["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
			["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
			["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
			["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
			["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
		]
	});

	$('#startTime, #endTime').timepicker();

});



			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31
				&& (charCode < 48 || charCode > 57))
				return false;
				return true;
			}


			function isNumericKey(evt)
			{
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31
				&& (charCode < 48 || charCode > 57))
				return true;
				return false;
			}


			function addToDescription()
			{

				if  ( $("#astaff").val().indexOf(":")>0 != "" && 	$("#staff_salary").val()*1 + $("#staff_salary_OT").val()*1 > 0)
				{

					var total = parseInt($("#staff_salary").val()*1)+ parseInt($("#staff_salary_OT").val()*1)
					var staffname = $("#astaff").val().replace("," , "").split(";")[0];
					$("#stafflist").val($("#stafflist").val()  + staffname+ "," + total + ","+ (parseInt($("#staff_salary_OT").val()*1))+ '\r\n')
					$("#stafflist").val(	$("#stafflist").val().replace(/\n\s*\n/g, '\n'));
					$("#astaff").val("");

				}else{

					alert("格式錯誤");
				}


			}


			function confirmDelete( ){

				var conf = confirm('確定刪除?');

						return conf;

			}
/*
			function getJobPrice(){

				var time = $("#acompany").val();
				var company = $("#categorie").val();

				if (time!= undefined && company !=undefined && time != "" && company  != "")
				$.post( "/th/database.php", { "type":"price" ,"company": company, "time": time } ,function(data){

						if (jQuery.parseJSON(data) > 1){
								 console.log("done")
									$("#staff_salary").val(jQuery.parseJSON(data));
						}else{
							console.log("tyribng")
							setTimeout(function(){ getJobPrice(); }, 2000)
						}
					});
			}


		setTimeout(function(){ getJobPrice(); }, 6000);
*/
