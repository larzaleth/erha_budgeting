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
				<a href="<?=base_url();?>m_compben_employee">CompBen Employee Existing</a>
				<i class="fa fa-circle"></i><?=$func;?> CompBen Employee Existing
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
						<div class="caption">
							<i class="icon-settings font-light"></i>
							<span class="caption-subject bold"> <?=$func;?> CompBen Employee Existing </span> 
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<?php 
						$id = 0;
						$nik = "";
						$name = "";
						$id_compben = "";
						$rate = "";
						if($func <> "Add") {
							foreach($result as $row) {
								$id = $row->id;
								$nik = $row->nik;
								$name = $row->name;
								$id_compben = $row->id_compben;
								$rate = $row->rate;
							}
						}
						?>
						<form action="<?=base_url();?>m_compben_employee/<?=($func=="Add"?"save":($func=="Edit"?"update":"confirm_delete"))?>" class="form-horizontal" method="POST">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">NIK / Name</label>
									<div class="col-md-4">
										<div class="input-group">
											<input type="hidden" class="form-control" placeholder="" name="id" value="<?=$id;?>">
											<input type="hidden" id="nik" name="nik" value="<?=$nik;?>">
											<input type="text" class="form-control" placeholder="" readonly id="name" name="name" value="<?=$name;?>" <?=($func=="Delete"?"disabled":"")?> style="width: 500px">
											<span class="input-group-btn">
												<button class="btn default" type="button" id="modal_search" href="#form_modal2" data-toggle="modal" <?=($func=="Delete"?"disabled":"")?>>
													<i class="fa fa-search"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">CompBen Name</label>
									<div class="col-md-4">
										<input type="hidden" class="form-control" placeholder="" name="id" value="<?=$id;?>">
										<select type="text" class="form-control" placeholder="" id="id_compben" name="id_compben" <?=($func=="Delete"?"disabled":"")?>>
											<?php foreach($compben as $row) { ?>
											<option value="<?=$row->id?>" <?=($row->id==$id_compben?"selected":"")?>><?=$row->name?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="width: 190px">Rate</label>
									<div class="col-md-4">
										<input type="text" class="form-control" placeholder="" id="rate" name="rate" value="<?=$rate;?>" <?=($func=="Delete"?"disabled":"")?> style="width: 100px">
										<span class="help-block" style="color:red"> ( Rate in nominal ) </span>
									</div>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-offset-2 col-md-10">
										<button type="submit" id="submit" class="btn green">Submit</button>
										<a href="<?=base_url();?>m_compben_employee" class="btn default">Cancel</a>
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
										<h4 class="modal-title"><b>Search Employee</b></h4>
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
													<th style="text-align: center"> NIK / Name </th>
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

<script src="<?=base_url();?>publics/scripts/autoNumeric-1.9.18.js" type="text/javascript"></script>

<script type="text/javascript">

	function set_employee(nik, name) {
		
		$("#nik").val(nik);
		$("#name").val(name);
		
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

	          $.post('<?=base_url();?>m_compben_employee/get_list_modal/' + $("#modal_datatable_page").val()
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
  						out.push( [ "<a href='#' class='btn btn-info btn-sm' data-dismiss='modal' onclick=\"set_employee('"+data[i].nik+"', '"+data[i].name+"')\"> Select </a>", data[i].name ] );
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

		$('#rate').css('text-align', 'right');
		$('#rate').autoNumeric('init', {
			lZero: 'deny'
			, aSep: ','
			, mDec: 0
		});
	  
		$('#modal_search').click(function() {

			get_list_modal();

		});

		$('#submit').click(function(event) {

			if($('#name').val() == "") {
				alert("Please fill NIK / Name!");
				$('#modal_search').click();
				return false;
			} else if($('#rate').val() == "") {
				alert("Please fill Rate!");
				$('#rate').focus();
				return false;
			} else {
				return true;
			}
			
		});
	  
	});

</script>