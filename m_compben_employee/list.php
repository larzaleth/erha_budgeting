<link href="<?=base_url();?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">
		<!-- BEGIN PAGE HEAD-->
		<div class="page-head">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><strong>Master Data</strong></h1>
			</div>
			<!-- END PAGE TITLE -->
			<!-- BEGIN PAGE TOOLBAR -->
			<div class="page-toolbar"></div>
			<!-- END PAGE TOOLBAR -->
		</div>
		<!-- END PAGE HEAD-->
		
		<!-- BEGIN PAGE BREADCRUMB -->
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="<?=base_url();?>m_compben_employee">CompBen Employee Existing</a>
				<i class="fa fa-circle"></i>List CompBen Employee Existing
			</li>
		</ul>
		<?php 
		$status = $this->session->flashdata('status');
		$message = $this->session->flashdata('message');
		if(!empty($status)) {
			echo '<div id="message" class="alert alert-'.($status=="4"||$status=="6"?"danger":"success").'">';
			if($status == "1") {
				echo 'Data successfully added!';
			} else if($status == "2") {
				echo 'Data successfully edited!';
			} else if($status == "3") {
				echo 'Data successfully deleted!';
			} else if($status == "4") {
				echo "Data failed uploaded! ".$message['error'];
			} else if($status == "5") {
				echo "Data successfully uploaded ($message rows)!";
			} else if($status == "6") {
				echo $message;
			}
			echo '</div>';
		}
		?>
		<!-- END PAGE BREADCRUMB -->
		
		<!-- BEGIN PAGE BASE CONTENT -->
		<!-- BEGIN DASHBOARD STATS 1-->
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box green-sharp">
					<div class="portlet-title">
						<div class="caption font-light">
							<i class="icon-settings font-light"></i>
							<span class="caption-subject bold"> Master CompBen Employee Existing</span>
						</div>
						<div class="actions">
							<a href="<?=(!(strpos($priv,"Add Compben Rate Employee")===false)?base_url()."m_compben_employee/add":"#")?>" class="btn btn-warning btn-sm" <?=(!(strpos($priv,"Add Compben Rate Employee")===false)?"":"disabled")?>>
							<i class="fa fa-plus"></i> Add New </a>
							<a href="<?=(!(strpos($priv,"Import Compben Rate Employee")===false)?base_url()."m_compben_employee/import":"#")?>" class="btn btn-primary btn-sm" <?=(!(strpos($priv,"Import Compben Rate Employee")===false)?"":"disabled")?>>
							<i class="fa fa-plus"></i> Import </a>
							<a href="#" id="excel" class="btn btn-primary btn-sm" <?=(!(strpos($priv,"Export Compben Rate Employee")===false)?"":"disabled")?>>
							<i class="fa fa-file-excel-o"></i> Export </a>
							<a href="<?=(!(strpos($priv,"Import Compben Rate Employee")===false)?base_url()."m_compben_employee/index_summary":"#")?>" class="btn btn-primary btn-sm" <?=(!(strpos($priv,"Import Compben Rate Employee")===false)?"":"disabled")?>>
							<i class="fa fa-file-excel-o"></i> Summary </a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<input type="hidden" id="datatable_page" value="0">
							<input type="hidden" id="priv" value="<?=$priv?>">
<!-- 							<div class="col-md-6 col-sm-6" tyle="width: 220px;"> -->
<!-- 								<div id="sample_editable_1_filter" class="dataTables_filter"> -->
<!-- 									<label> -->
<!-- 										SBU : <select id="structures" name="structures" aria-controls="sample_editable_1" class="form-control input-sm input-xsmall input-inline" tyle="width: 120px !important"> -->
<!-- 											<option value='%'>All</option> -->
											<?php 
// 											foreach($structures as $row) {
// 												$id = $row->id;
// 												$name = $row->name;
// 												echo "<option value='$id'>$name</option>";
// 											}
											?>
<!-- 										</select> -->
<!-- 									</label> -->
<!-- 								</div> -->
<!-- 							</div> -->
							<div class="col-md-6 col-sm-6" style="width: 400px;">
								<div id="sample_editable_1_filter" class="dataTables_filter">
									<label>
										<div class="input-group">
	                                        <input type="search" id="datatable_searchs" class="form-control input-sm input-small input-inline" placeholder="Search for..." aria-controls="sample_editable_1">&nbsp;
                                            <a href="#" id="datatable_searchs_search" class="btn btn-success btn-sm" type="button" style="padding: 3px 6px"><i class="fa fa-search"></i> Search</a>&nbsp;
                                            <a href="#" id="datatable_searchs_clear" class="btn btn-danger btn-sm" type="button" style="padding: 3px 6px"><i class="fa fa-remove"></i> Clear Search</a>
	                                    </div>
									</label>
								</div>
							</div>
							<div class="col-md-6 col-sm-6" style="width: 250px; float: right;">
								<div class="dataTables_length" id="sample_editable_1_length" style="float: right;">
									<label>
										Entries per page : <select id="datatable_per_pages" aria-controls="sample_editable_1" class="form-control input-sm input-xsmall input-inline">
											<option value="20">20</option>
											<option value="50">50</option>
											<option value="100">100</option>
										</select>
									</label>
								</div>
							</div>
						</div>
						<hr style="margin: 10px 0">
						<table border="0" style="width: 100%"><tr>
							<td><div class="informations"></div></td>
							<td style="text-align: right"><div class="paginations"></div></td>
						</tr></table>
						<table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
							<thead>
								<tr style="background-color:#2ab4c0;">
									<th style="text-align: center" width="50px"> NIK </th>
									<th style="text-align: center"> Name </th>
									<th style="text-align: center"> CompBen </th>
									<th style="text-align: center"> SBU </th>
									<th style="text-align: center"> Rate </th>
									<th style="text-align: center" width="90px"> Action</th>
								</tr>
							</thead>
						</table>
						<table border="0" style="width: 100%"><tr>
							<td><div class="informations"></div></td>
							<td style="text-align: right"><div class="paginations"></div></td>
						</tr></table>
					</div>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
		<!-- END PAGE BASE CONTENT -->
	</div>
	<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->

<script src="<?=base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script type="text/javascript">

	$("#datatable_per_pages").val(20);
	$('#datatable_page').val('');

	//prefix:'Rp. ';suffix:,-;groupSeparator:.;decimalSeparator:,;precision:0
	function currency(num, formats) {
		
		num = (num==null||num==""?0:Number(num));
		
		var prefix = '';
		var suffix = '';
		var groupSeparator = '';
		var decimalSeparator = '';
		var precision = '';
		
		if(formats != undefined) {
			var format = formats.split(";");
			for(i = 0; i < format.length; i++) {
				var subFormat = format[i].split(":");
				if(subFormat[0].trim() == 'prefix') prefix = subFormat[1].trim().replace(/\'/g,'')
				else if(subFormat[0].trim() == 'suffix') suffix = subFormat[1].trim().replace(/\'/g,'')
				else if(subFormat[0].trim() == 'groupSeparator') groupSeparator = subFormat[1].trim().replace(/\'/g,'')
				else if(subFormat[0].trim() == 'decimalSeparator') decimalSeparator = subFormat[1].trim().replace(/\'/g,'')
				else if(subFormat[0].trim() == 'precision') precision = subFormat[1].trim().replace(/\'/g,'')
			}
		}
		
		num = num.toFixed(Math.max(0, ~~precision));
		var re = '\\d(?=(\\d{3})+' + (precision > 0 ? '\\D' : '$') + ')';
		var result = (decimalSeparator ? num.replace('.', decimalSeparator) : num).replace(new RegExp(re, 'g'), '$&' + groupSeparator);
		
		return prefix + result + suffix;
		
	}

 	$('#excel').click(function() {

 		if($("#priv").val().indexOf("Export Compben Rate Employee") > -1) {
// 			$.redirectPost('?=base_url();?>m_compben_employee/get_excel', {id_structure: $("#structures :selected").val(), datatable_search: $('#datatable_searchs').val()});
			$.redirectPost('<?=base_url();?>m_compben_employee/get_excel', {datatable_search: $('#datatable_searchs').val()});
 		}
	
	});

//  	$("#structures").change(function() {

//  		$('#message').remove();
//  		$('#datatable_page').val('');
// 		get_list();
		
// 	});

	function get_list() {

		var table = $('#datatable');
	    var oTable = table.dataTable({
	
	        // Internationalisation. For more info refer to http://datatables.net/manual/i18n
	        "language": {
	            "aria": {
	                "sortAscending": ": activate to sort column ascending",
	                "sortDescending": ": activate to sort column descending"
	            },
	            "emptyTable": "No data available in table",
	            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
	            "infoEmpty": "No entries found",
	            "infoFiltered": "(filtered1 from _MAX_ total entries)",
	            "lengthMenu": "_MENU_ entries",
	            "search": "Search:",
	            "zeroRecords": "No matching records found"
	        },
	        
	
	        // Or you can use remote translation file
	        //"language": {
	        //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
	        //},
	
	        // setup buttons extension: http://datatables.net/extensions/buttons/
	        buttons: [
	//             { extend: 'print', className: 'btn dark btn-outline' },
	//             { extend: 'pdf', className: 'btn green btn-outline' },
	//             { extend: 'csv', className: 'btn purple btn-outline ' }
	        ],
	        
	        // scroller extension: http://datatables.net/extensions/scroller/
	        "bDestroy": true,
	        scrollY: 600,
	//         scrollY: true,
	        deferRender:    true,
	        scroller:       true,
	        deferRender:    true,
	        scrollX:        true,
	//         scrollCollapse: true,        
	        ordering: false,
	        searching: false,
	        stateSave: true,
	        serverSide: true,
	        scroller: {
	            loadingIndicator: true
	        },
	        ajax: function ( data, callback, settings ) {

		         $.post('<?=base_url();?>m_compben_employee/get_list/' + $("#datatable_page").val()
 				,{
// 		        	 id_structure: $("#structures :selected").val(), 
 	        	 	datatable_per_page: $("#datatable_per_pages :selected").val()
 					, datatable_search: $('#datatable_searchs').val()
 				}
 				,function(data) {

 					var page = Number(data['page']);
 					var per_page = Number(data['per_page']);
 					var total_rows = Number(data['total_rows']);
 					var pagination = data['pagination'];
 					var data = data['result'];
 					
 					var out = [];
 					for(var i = 0; i < data.length; i++) {
						out.push( [ data[i].nik, data[i].name, data[i].compben, data[i].structure
									, currency(data[i].rate, "groupSeparator:,;decimalSeparator:.;precision:0")
									, "<a href='"+($("#priv").val().indexOf("Edit Compben Rate Employee")>-1?"<?=base_url();?>m_compben_employee/edit/"+data[i].id:"#")+"' class='btn btn-info btn-sm' <?=(!(strpos($priv,"Edit Compben Rate Employee")===false)?"":"disabled")?>><i class='fa fa-pencil'></i> Edit </a>"
									+ "<a href='"+($("#priv").val().indexOf("Delete Compben Rate Employee")>-1?"<?=base_url();?>m_compben_employee/delete/"+data[i].id:"#")+"' class='btn btn-danger btn-sm' <?=(!(strpos($priv,"Delete Compben Rate Employee")===false)?"":"disabled")?>><i class='fa fa-remove'></i> Delete </a>" ] );
 					}

 					$(".informations").html("Showing " + currency((page + 1), "groupSeparator:,") + " to " + (page+per_page>total_rows?currency(total_rows, "groupSeparator:,"):currency((page+per_page), "groupSeparator:,")) + " of <strong>" + currency(total_rows, "groupSeparator:,") + "</strong> entries");
 					$(".paginations").html(pagination);
 	
 					setTimeout( function () {
 	 					
 						callback( {
 		                    data: out,
 		                    recordsTotal: data.length,
 		                    recordsFiltered: data.length
 						} );

 						$(".paginations a").each(function() {

 							if($(this).attr('href') != undefined) {

 								var page = $(this).attr('href').replace("\/", "");
 								$(this).attr("href", "#");
 								$(this).attr("onclick", "$('#message').remove();$('#datatable_page').val('"+page+"');get_list();");
 								
 							}
 							
 						});

 						$('tr:odd td').addClass('active');
 						$('#datatable tbody tr td:nth-child(5)').each(function() {
							$(this).css("text-align", "right");
						});
 						
 		            }, 50 );
 	
 				},'json');
	        
	        },

	//         "order": [
	//             [0, 'asc']
	//         ],
	        
	//         "lengthMenu": [
	//             [10, 15, 20, -1],
	//             [10, 15, 20, "All"] // change per page values here
	//         ],
	        // set the initial value
	        "pageLength": 10,
	
	//         "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
	//         "dom": "<'row' <'col-md-12'B>><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
			"dom": "<'row' <'col-md-12'B>><'table-scrollable't><'row'<'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
	//         "dom": "<'row' <'col-md-12'B>><'table-scrollable't><'row'<'col-md-12'i>>",
	
	        // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
	        // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
	        // So when dropdowns used the scrollable div should be removed. 
	        //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
	    });

	}

	get_list();

</script>
