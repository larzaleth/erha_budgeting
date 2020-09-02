<link href="<?=base_url();?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        
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
				<i class="fa fa-circle"></i>Import CompBen Employee Existing
			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
		<!-- BEGIN PAGE BASE CONTENT -->
		<!-- BEGIN DASHBOARD STATS 1-->
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box green-sharp">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-settings font-light"></i>
							<span class="caption-subject bold"> Import CompBen Employee Existing </span> 
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<form action="<?=base_url();?>m_compben_employee/upload" class="form-horizontal" enctype="multipart/form-data" method="POST">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-md-3">File</label>
									<div class="col-md-3">
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="input-group input-large">
												<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
													<i class="fa fa-file fileinput-exists"></i>&nbsp;
													<span class="fileinput-filename"> </span>
												</div>
												<span class="input-group-addon btn default btn-file">
													<span class="fileinput-new"> Select file </span>
													<span class="fileinput-exists"> Change </span>
													<input type="file" id="file" name="file">
												</span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-offset-2 col-md-10">
										<button type="submit" id="submit" class="btn green">Import</button>
										<a href="#" class="btn blue-sharp" onclick="template();">Download Template</a>
										<a href="<?=base_url();?>m_compben_employee" class="btn default">Cancel</a>
									</div>
								</div>
							</div>
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

<script src="<?=base_url();?>/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

<script type="text/javascript">

	function template() {
		
		$.redirectPost('<?=base_url();?>m_compben_employee/get_template', {});
	
	}

	$(function() {
		  
		$('#submit').click(function(event) {

			if($('#file').val() == "") {
				alert("Please fill file upload!");
				$('#file').click();
				return false;
			} else {
				return true;
			}
			
		});
	  
	});

</script>