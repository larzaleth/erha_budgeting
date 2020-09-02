<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="<?=base_url();?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

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
				<a href="<?=base_url();?>m_employee">Employee Existing</a>
				<i class="fa fa-circle"></i><?=$func;?> Employee Existing
			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE BASE CONTENT -->
		<!-- BEGIN DASHBOARD STATS 1-->
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<?php if($func == "Delete") { ?>
				<div class="alert alert-danger">
					Are you sure want to delete this record?
				</div>
				<?php } ?>
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box green-sharp">
					<div class="portlet-title">
						<div class="caption font-light">
							<i class="icon-settings font-light"></i>
							<span class="caption-subject bold"> <?=$func;?> Employee Existing </span>
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<?php 
						$nik = '';
						$name = "";
						$id_structure = "";
						$name_structure = "";
						$id_job_level = "";
						$id_job_position = "";
						$id_location = "";
						$id_region = "";
						$job_status = "";
						if($func <> "Add") {
							foreach($result as $row) {
								$nik = $row->nik;
								$name = $row->name;
								$id_structure = $row->id_structure;
								$name_structure = $row->name_structure;
								$id_job_level = $row->id_job_level;
								$id_job_position = $row->id_job_position;
								$id_location = $row->id_location;
								$id_region = $row->id_region;
								$job_status = $row->job_status;
							}
						}
						?>
						<form action="<?=base_url();?>m_employee/<?=($func=="Add"?"save":($func=="Edit"?"update":"confirm_delete"))?>" class="form-horizontal" method="POST">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">NIK</label>
									<div class="col-md-4">
										<input type="text" class="form-control" placeholder="" id="nik" name="nik" value="<?=$nik;?>" <?=($func=="Add"?"":"readonly")?>>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Employee Name</label>
									<div class="col-md-4">
										<input type="text" class="form-control" placeholder="" id="name" name="name" value="<?=$name;?>" <?=($func=="Delete"?"disabled":"")?>>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Organisation Structure</label>
									<div class="col-md-4">
										<div class="input-group">
											<input type="hidden" id="id_structure" name="id_structure" value="<?=$id_structure;?>">
											<input type="text" class="form-control" placeholder="" readonly id="name_structure" name="name_structure" value="<?=$name_structure;?>" <?=($func=="Delete"?"disabled":"")?> style="width: 500px">
											<span class="input-group-btn">
												<button class="btn default" type="button" id="modal_search" href="#form_modal2" data-toggle="modal" <?=($func=="Delete"?"disabled":"")?>>
													<i class="fa fa-search"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Job Level</label>
									<div class="col-md-4">
										<select type="text" class="form-control" placeholder="" id="id_job_level" name="id_job_level" <?=($func=="Delete"?"disabled":"")?>>
											<?php foreach($job_level as $row) { ?>
											<option value="<?=$row->id?>" <?=($row->id==$id_job_level?"selected":"")?>><?=$row->name?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Job Position</label>
									<div class="col-md-4">
										<select type="text" class="form-control" placeholder="" id="id_job_position" name="id_job_position" <?=($func=="Delete"?"disabled":"")?>>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Location Name</label>
									<div class="col-md-4">
										<select type="text" class="form-control" placeholder="" id="id_location" name="id_location" <?=($func=="Delete"?"disabled":"")?>>
											<?php foreach($location as $row) { ?>
											<option value="<?=$row->id?>" <?=($row->id==$id_location?"selected":"")?>><?=$row->name?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Region</label>
									<div class="col-md-4">
										<select type="text" class="form-control" placeholder="" id="id_region" name="id_region" <?=($func=="Delete"?"disabled":"")?>>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Job Status</label>
									<div class="col-md-4">
										<select type="text" class="form-control" placeholder="" name="job_status" <?=($func=="Delete"?"disabled":"")?>>
											<option value="Permanent" <?=($job_status=="Permanent"?"selected":"")?>>Permanent</option>
											<option value="Contract" <?=($job_status=="Contract"?"selected":"")?>>Contract</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-offset-2 col-md-10">
										<button type="submit" id="submit" class="btn green">Submit</button>
										<a href="<?=base_url();?>m_employee" class="btn default">Cancel</a>
									</div>
								</div>
							</div>
						</form>
						<!-- END FORM-->
						
						<div id="form_modal2" class="modal fade" role="dialog" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h4 class="modal-title"><b>Search SBU</b></h4>
									</div>
									<div class="modal-body">
										<input type="hidden" id="modal_datatable_page" value="0">
										<div class="col-md-6 col-sm-6" style="padding-left: 0px; line-height: 0px; width: 350px">
											<div id="sample_editable_1_filter" class="dataTables_filter">
												<label>
													<div class="input-group">
				                                        <input type="search" id="modal_datatable_search" class="form-control input-sm input-small input-inline" placeholder="Search for..." aria-controls="sample_editable_1">&nbsp;
			                                            <a href="#" id="modal_datatable_searchs_search" class="btn btn-success btn-sm" type="button" style="padding: 3px 6px"><i class="fa fa-search"></i> Search</a>&nbsp;
			                                            <a href="#" id="modal_datatable_searchs_clear" class="btn btn-danger btn-sm" type="button" style="padding: 3px 6px"><i class="fa fa-remove"> Clear Search</i></a>
				                                    </div>
			                                    </label>
											</div>
										</div>
										<div class="col-md-6 col-sm-6" style="padding-right: 0px; line-height: 0px; width: 210px">
											<div class="dataTables_length" id="sample_editable_1_length" style="float: right;">
												<label>
													Entries per page : <select id="modal_datatable_per_page" name="modal_datatable_per_page" aria-controls="sample_editable_1" class="form-control input-sm input-xsmall input-inline">
														<option value="20">20</option>
														<option value="50">50</option>
														<option value="100">100</option>
													</select>
												</label>
											</div>
										</div>
										<br><br><hr style="margin: 5px">
										<table class="table table-striped table-bordered table-hover order-column" id="modal_datatable">
											<thead>
												<tr style="background-color:#2ab4c0; font-weight: bold;">
													<th style="text-align: center" width="30px"> Action</th>
													<th style="text-align: center"> SBU </th>
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
						
					</div>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?=base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script type="text/javascript">

	function set_sbu(id, name) {
		
		$("#id_structure").val(id);
		$("#name_structure").val(name);
		
	}

	function get_list_modal() {
	
		 var oTable = $('#modal_datatable').dataTable({
	       // Internationalisation. For more info refer to http://datatables.net/manual/i18n
	       "language": {
	           "aria": {
	               "sortAscending": ": activate to sort column ascending",
	               "sortDescending": ": activate to sort column descending"
	           },
	           "emptyTable": "No data available in table",
	           "info": "Showing _START_ to _END_ of _TOTAL_ entries",
	           "infoEmpty": "No entries found",
	           "infoFiltered": "(filtered from _MAX_ total entries)",
	           "lengthMenu": "_MENU_ entries",
	           "search": "Search:",
	           "zeroRecords": "Loading..."
	       },
	       buttons: [
	       ],
	       "bDestroy": true,
	       serverSide: true,
	       ordering: false,
	       searching: false,
	       scrollY: 200,
	       scrollX: true,
	       scroller: {
	           loadingIndicator: true
	       },
	       ajax: function ( data, callback, settings ) {
	
	         $.post('<?=base_url();?>m_employee/get_list_modal/' + $("#modal_datatable_page").val()
				,{
	        	 	modal_datatable_per_page: $("#modal_datatable_per_page :selected").val()
					, modal_datatable_search: $('#modal_datatable_search').val()
				}
				,function(data) {

					var page = Number(data['page']);
					var per_page = Number(data['per_page']);
					var total_rows = Number(data['total_rows']);
					var pagination = data['pagination'];
					var data = data['result'];
					
					var out = [];
					for(var i = 0; i < data.length; i++) {
						out.push( [ "<a href='#' class='btn btn-info btn-sm' data-dismiss='modal' onclick=\"set_sbu('"+data[i].id+"', '"+data[i].name+"')\"> Select </a>", data[i].name ] );
					}

					$(".informations").html("Showing " + (page + 1) + " to " + (page+per_page>total_rows?total_rows:(page+per_page)) + " of <strong>" + total_rows + "</strong> entries");
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
								$(this).attr("onclick", "$('#modal_datatable_page').val('"+page+"'); get_list_modal();");
								
							}
							
						});

						$('tr:odd td').addClass('active');
							
		            }, 50 );
	
				},'json');
	           
	       },
	       "dom": "<'row' <'col-md-12'B>><'table-scrollable't><'row'<'col-md-12'i>>"
	   });
	   
	}

	$(function() {

		$("#modal_datatable_per_page").val(20);
		$('#modal_datatable_page').val('');

		$('#modal_search').click(function() {

			get_list_modal();

		});

	  	$('#id_job_level').change(function() {

	  		get_job_position('');
			
		});

		function get_job_position(id_job_position) {

			$.post('<?=base_url();?>m_employee/get_job_position'
			, {
				'id_job_level': $('#id_job_level').val()
			}
			, function(data){

				$('#id_job_position').html('');
				for(var i = 0; i < data.length; i++) {
					$('#id_job_position').append('<option value="'+data[i]['id']+'" '+(data[i]['id']==id_job_position?'selected':'')+'>'+data[i]['name']+'</option>');
				}
			
			},'json');

		};

		get_job_position(<?=$id_job_position?>);

	  	$('#id_location').change(function() {

	  		get_region('');
			
		});

		function get_region(id_region) {

			$.post('<?=base_url();?>m_employee/get_region'
			, {
				'id_location': $('#id_location').val()
			}
			, function(data){

				$('#id_region').html('');
				for(var i = 0; i < data.length; i++) {
					$('#id_region').append('<option value="'+data[i]['id']+'" '+(data[i]['id']==id_region?'selected':'')+'>'+data[i]['name']+'</option>');
				}
			
			},'json');

		};

		get_region(<?=$id_region?>);

		var regex = new RegExp("^[0-9]+$");
		$('#submit').click(function() {

			if(!regex.test($('#nik').val())) {
				
				alert("NIK is not valid!");
				$('#nik').focus();
				return false;
				
			} else if($('#name').val() == "") {
	
				alert('Please fill Employee Name!');
				$('#name').focus();
				return false;

			} if($('#id_structure').val() == "") {
				
				alert("Please fill Organisation Structure!");
				$('#modal_search').click();
				return false;

			} if($('#id_job_position option:selected').val() == undefined) {
				
				alert("Please select Job Position!");
				return false;
				
			} if($('#id_region option:selected').val() == undefined) {
				
				alert("Please select Region!");
				return false;
				
			} else {
	
				return true;
	
			}

		});

	});

</script>
