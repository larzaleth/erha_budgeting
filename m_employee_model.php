<?php

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

class m_employee_model extends CI_Model {
	
	function get_list() {

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$per_page = (isset($_REQUEST['datatable_per_page'])?$_REQUEST['datatable_per_page']:20);
		$search = (isset($_REQUEST['datatable_search'])?$_REQUEST['datatable_search']:'');
		$base_url = '';
		
// 		$id_structure = (isset($_REQUEST['id_structure'])?$_REQUEST['id_structure']:'0.0.0.0');
// 		$arr = explode(".", $id_structure);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
// 		$sql = "select a.nik, a.name, a.sbu
// 				  , a.job_position, a.location, a.region
// 				from (
// 				    select e.nik, e.name
// 				      , ifnull(get_list_sbu_parent(m.id_structure), '') sbu
// 				      , ifnull(jp.name, '') job_position, ifnull(l.name, '') location, ifnull(r.name, '') region
// 				    from m_employee e
// 				      , t_mpp m
// 				      left outer join m_job_position jp
// 				        on jp.id = m.id_job_position and jp.year = '$year'
// 				      left outer join m_region r
// 				        on r.id = m.id_region and r.year = '$year'
// 				      left outer join m_location l
// 				        on l.id = r.id_location
// 				      , m_structure s
// 				    where e.status = 1
// 				      and m.nik_employee = e.nik 
// 				      and m.job_remark = 'employee' 
// 				      and m.year = '$year' 
// 				      and m.id_structure like '".$arr[0].".%'
// 				      and s.id = m.id_structure 
// 				      and s.year = m.year
// 				  ) a
// 				where concat(a.nik, a.name, a.sbu, a.job_position, a.location, a.region) like '%$search%'";
		$sql = "select a.nik, a.name, a.sbu
				  , a.job_position, a.location, a.region
				from (
				    select e.nik, e.name
				      , ifnull(get_list_sbu_parent(m.id_structure), '') sbu
				      , ifnull(jp.name, '') job_position, ifnull(l.name, '') location, ifnull(r.name, '') region
				    from m_employee e
				      , t_mpp m
				      left outer join m_job_position jp
				        on jp.id = m.id_job_position and jp.year = '$year'
				      left outer join m_region r
				        on r.id = m.id_region and r.year = '$year'
				      left outer join m_location l
				        on l.id = r.id_location
				      , m_structure s
				    where e.status = 1
				      and m.nik_employee = e.nik
				      and m.job_remark = 'employee'
				      and m.year = '$year'
				      and s.id = m.id_structure
				      and s.year = m.year
				  ) a
				where concat(a.nik, a.name, a.sbu, a.job_position, a.location, a.region) like '%$search%'";
		write_log($this, __METHOD__, "sql : $sql");
		if($search == "") {
// 			$total_rows = $this->db->query("select 1 
// 											from m_employee e
// 											  , t_mpp m
// 											  , m_structure s
// 											where e.status = 1
// 											  and m.nik_employee = e.nik 
// 											  and m.job_remark = 'employee' 
// 											  and m.year = '$year'
// 											  and m.id_structure like '".$arr[0].".%'
// 											  and s.id = m.id_structure
// 											  and s.year = m.year")->num_rows();
			$total_rows = $this->db->query("select 1
											from m_employee e
											  , t_mpp m
											  , m_structure s
											where e.status = 1
											  and m.nik_employee = e.nik
											  and m.job_remark = 'employee'
											  and m.year = '$year'
											  and s.id = m.id_structure
											  and s.year = m.year")->num_rows();
		} else {
			$total_rows = $this->db->query($sql)->num_rows();
		}
		write_log($this, __METHOD__, "total : " . $total_rows);
		
		write_log($this, __METHOD__, "page : $page | per_page : $per_page | search : $search");

		$data['page'] = $page;
		$data['per_page'] = $per_page;
		$data['total_rows'] = $total_rows;

		$config = pagination($base_url, $total_rows, $per_page);
		$this->pagination->initialize($config['config']);
		$data['pagination'] = $this->pagination->create_links();

		$sql = $sql . " limit $page, $per_page";
		write_log($this, __METHOD__, "sql : $sql");
		$data['result'] = $this->db->query($sql)->result_array();

		return $data;

	}
	
	function get_list_modal() {

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$per_page = (isset($_REQUEST['modal_datatable_per_page'])?$_REQUEST['modal_datatable_per_page']:20);
		$search = (isset($_REQUEST['modal_datatable_search'])?$_REQUEST['modal_datatable_search']:'');
		$base_url = '';
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
		$total_rows = $this->db->query("select 1 from m_structure where id <> '0.0.0.0' and year = '$year' and concat(id, ' - ', get_list_sbu_parent(id)) like '%$search%'")->num_rows();
		write_log($this, __METHOD__, "page : $page | per_page : $per_page | search : $search");

		$data['page'] = $page;
		$data['per_page'] = $per_page;
		$data['total_rows'] = $total_rows;

		$config = pagination($base_url, $total_rows, $per_page);
		$this->pagination->initialize($config['config']);
		$data['pagination'] = $this->pagination->create_links();

		$sql = "select a.id, a.name
				from (
				    select s.id
				      , concat(s.id, ' - ', get_list_sbu_parent(s.id)) name
				    from m_structure s
				    where s.id <> '0.0.0.0' and s.year = '$year'
				  ) a
				where a.name like '%$search%'
				order by a.id
				limit $page, $per_page";
		write_log($this, __METHOD__, "sql : $sql");

		$data['result'] = $this->db->query($sql)->result_array();

		return $data;

	}
	
	function save() {
	
		$user_login = $this->session->userdata("id");
		$nik = (isset($_REQUEST['nik'])?trim($_REQUEST['nik']):'');
		$name = (isset($_REQUEST['name'])?str_replace("'","''",$_REQUEST['name']):'');
		$id_structure = (isset($_REQUEST['id_structure'])?$_REQUEST['id_structure']:'');
		$id_job_position = (isset($_REQUEST['id_job_position'])?$_REQUEST['id_job_position']:'');
		$id_region = (isset($_REQUEST['id_region'])?$_REQUEST['id_region']:'');
		$job_status = (isset($_REQUEST['job_status'])?$_REQUEST['job_status']:'');
		
		$sql = "select 1 from m_employee where trim(nik) = '$nik'";
		$exist = $this->db->query($sql)->num_rows();
		
		if($exist > 0) {
			
			$this->session->set_flashdata('status', '6');
			$this->session->set_flashdata('message', "Duplicate entry $nik!");
			
		} else {
			
			$sql = "insert into m_employee(nik, name, job_status, created_by, created_date, modified_by, modified_date)
					values('$nik', '$name', '$job_status', '$user_login', sysdate(), '$user_login', sysdate())";
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
		
			$sql = "select value year from m_parameter where name = 'Active Budget'";
			$data2 = $this->db->query($sql)->row_array();
			$year = $data2['year'];
			
			$sql = "select 1 from t_mpp where nik_employee = '$nik' and job_remark = 'employee' and year = '$year'";
			$exist = $this->db->query($sql)->num_rows();
	
			if($exist > 0) {
		
				$sql = "update t_mpp
						set id_job_position = '$id_job_position', id_region = '$id_region', id_structure = '$id_structure'
						  , job_status = '$job_status', approve_by = '$user_login', approve_date = sysdate()
						where nik_employee = '$nik' and job_remark = 'employee' and year = '$year'";
	
			} else {
		
				$sql = "insert into t_mpp(
						  nik_employee, id_job_position, id_region, id_structure, job_status, job_remark
						  , year, version, version_parent, created_by, created_date, approve_by, approve_date
						  , jan, jan_amount, feb, feb_amount, mar, mar_amount, apr, apr_amount
						  , may, may_amount, jun, jun_amount, jul, jul_amount, aug, aug_amount
						  , sep, sep_amount, oct, oct_amount, nov, nov_amount, `dec`, dec_amount, promotion, note)
						values(
						  '$nik', '$id_job_position', '$id_region', '$id_structure', '$job_status', 'employee'
						  , '$year', '', '', '$user_login', sysdate(), '$user_login', sysdate()
						  , 0, 0, 0, 0, 0, 0, 0, 0
						  , 0, 0, 0, 0, 0, 0, 0, 0
						  , 0, 0, 0, 0, 0, 0, 0, 0, '', ''
						)";
	
			}
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
		
			$this->session->set_flashdata('status', '1');
		
		}
	
		redirect('/m_employee');
	
	}
	
	function update() {
		
		$user_login = $this->session->userdata("id");
		$nik = (isset($_REQUEST['nik'])?trim($_REQUEST['nik']):'');
		$name = (isset($_REQUEST['name'])?str_replace("'","''",$_REQUEST['name']):'');
		$id_structure = (isset($_REQUEST['id_structure'])?$_REQUEST['id_structure']:'');
		$id_job_position = (isset($_REQUEST['id_job_position'])?$_REQUEST['id_job_position']:'');
		$id_region = (isset($_REQUEST['id_region'])?$_REQUEST['id_region']:'');
		$job_status = (isset($_REQUEST['job_status'])?$_REQUEST['job_status']:'');
		
		$sql = "update m_employee
				set name = '$name', job_status = '$job_status', modified_by = '$user_login', modified_date = sysdate()
				where nik = '$nik'";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
			
		$sql = "select 1 from t_mpp where nik_employee = '$nik' and job_remark = 'employee' and year = '$year'";
		$exist = $this->db->query($sql)->num_rows();
		
		if($exist > 0) {
			
			$sql = "update t_mpp
					set id_job_position = '$id_job_position', id_region = '$id_region', id_structure = '$id_structure'
					  , job_status = '$job_status', approve_by = '$user_login', approve_date = sysdate()
					where nik_employee = '$nik' and job_remark = 'employee' and year = '$year'";
		
		} else {
			
			$sql = "insert into t_mpp(
					  nik_employee, id_job_position, id_region, id_structure, job_status, job_remark
					  , year, version, version_parent, created_by, created_date, approve_by, approve_date
					  , jan, jan_amount, feb, feb_amount, mar, mar_amount, apr, apr_amount
					  , may, may_amount, jun, jun_amount, jul, jul_amount, aug, aug_amount
					  , sep, sep_amount, oct, oct_amount, nov, nov_amount, `dec`, dec_amount, promotion, note)
					values(
					  '$nik', '$id_job_position', '$id_region', '$id_structure', '$job_status', 'employee'
					  , '$year', '', '', '$user_login', sysdate(), '$user_login', sysdate()
					  , 0, 0, 0, 0, 0, 0, 0, 0
					  , 0, 0, 0, 0, 0, 0, 0, 0
					  , 0, 0, 0, 0, 0, 0, 0, 0, '', ''
					)";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		
		$this->session->set_flashdata('status', '2');
		
		redirect('/m_employee');
		
	}
	
	function confirm_delete() {
	
		$user_login = $this->session->userdata("id");
		$nik = (isset($_REQUEST['nik'])?$_REQUEST['nik']:'');
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
		$sql = "delete from t_mpp
				where nik_employee = '$nik' and job_remark = 'employee' and year = '$year'";
		$result = $this->db->query($sql);
		
		$sql = "update m_employee set status = '2', modified_by = '$user_login', modified_date = sysdate() where nik = '$nik'";
		$result = $this->db->query($sql);
	
		$this->session->set_flashdata('status', '3');
	
		redirect('/m_employee');
	
	}
	
	function get_template() {
	
		// declare variable
		$col_start = 'A';
		$row_start = 1;
		$title = "Template Employee Existing";
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
	
		$objPHPExcel = $this->excel->properties($title);
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
	
		/*** SHEET 1 ***/
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Template');
		$excel = $objPHPExcel->getActiveSheet();
	
		// header & parameter
		$row = $row_start;
	
		// declare column
		$col_header = array('NIK', 'Employee Name', 'ID Structure', 'ID Job Position', 'ID Region', 'Job Status');
	
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row));
		$excel->getColumnDimension('A')->setWidth(15);
		$excel->getColumnDimension('B')->setWidth(25);
		$excel->getColumnDimension('C')->setWidth(12);
		$excel->getColumnDimension('D')->setWidth(14);
		$excel->getColumnDimension('E')->setWidth(11);
		$excel->getColumnDimension('F')->setWidth(11);
		$excel->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
	
		/*** SHEET 2 ***/
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1)->setTitle('Structure');
		$excel = $objPHPExcel->getActiveSheet();
	
		// header & parameter
// 		$this->excel->title($excel, 'A', $row=$row_start, "List of Structure:2");
		$row=$row_start;
		$objRichText = new PHPExcel_RichText();
		$text = $objRichText->createTextRun('List of Structure ');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ) );
		$text = $objRichText->createTextRun('(not for edit)');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
		$excel->setCellValue("A1", $objRichText)->mergeCells('A1:C1');
	
		// declare column
		$col_header = array('ID', 'Name', 'Type');
		$col_body = array('', '', '');
		$col_footer = array('', '', '');
	
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row+=2));
	
		// set body column
		$sql = "select s.id, s.name, st.name type,
				CAST(SPLIT_STR(s.id, '.', 1) as SIGNED) as id1,
				CAST(SPLIT_STR(s.id, '.', 2) as SIGNED) as id2, 
				CAST(SPLIT_STR(s.id, '.', 3) as SIGNED) as id3, 
				CAST(SPLIT_STR(s.id, '.', 4) as SIGNED) as id4
				from m_structure s
				  , m_structure_type st
				where s.id <> '0.0.0.0'
				  and s.year = '$year'
				  and st.id = s.id_structure_type
				order by id1, id2, id3, id4";
		$data = $this->db->query($sql)->result_array();
		
		$data2 = [];
		$i = 0;
		foreach($data as $val){
			$data2[$i]["id"] = $val["id"];
			$data2[$i]["name"] = $val["name"];
			$data2[$i]["type"] = $val["type"];
			$i++;
		}
		
		$total = $this->excel->col_body($excel, $col_body, $col_footer, $col_start, ++$row, $data2, true);
	
		// set footer column
		$this->excel->col_footer($excel, $col_footer, $col_start, $row, $data, $total);
		
		/*** SHEET 3 ***/
		$objPHPExcel->createSheet(2);
		$objPHPExcel->setActiveSheetIndex(2)->setTitle('Job Position');
		$excel = $objPHPExcel->getActiveSheet();
		
		// header & parameter
// 		$this->excel->title($excel, 'A', $row=$row_start, "List of Job Position:3");
		$row=$row_start;
		$objRichText = new PHPExcel_RichText();
		$text = $objRichText->createTextRun('List of Job Position ');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ) );
		$text = $objRichText->createTextRun('(not for edit)');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
		$excel->setCellValue("A1", $objRichText)->mergeCells('A1:C1');
		
		// declare column
		$col_header = array('ID', 'Name', 'Level');
		$col_body = array('', '', '');
		$col_footer = array('', '', '');
		
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row+=2));
		
		// set body column
		$sql = "select jp.id, jp.name, jl.name level
				from m_job_position jp
				  , m_job_level jl
				where jp.year = '$year' and jl.id = jp.id_job_level
				order by jp.id";
		$data = $this->db->query($sql)->result_array();
		$total = $this->excel->col_body($excel, $col_body, $col_footer, $col_start, ++$row, $data, true);
		
		// set footer column
		$this->excel->col_footer($excel, $col_footer, $col_start, $row, $data, $total);
		
		/*** SHEET 4 ***/
		$objPHPExcel->createSheet(3);
		$objPHPExcel->setActiveSheetIndex(3)->setTitle('Region');
		$excel = $objPHPExcel->getActiveSheet();
		
		// header & parameter
// 		$this->excel->title($excel, 'A', $row=$row_start, "List of Region:2");
		$row=$row_start;
		$objRichText = new PHPExcel_RichText();
		$text = $objRichText->createTextRun('List of Region ');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ) );
		$text = $objRichText->createTextRun('(not for edit)');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
		$excel->setCellValue("A1", $objRichText)->mergeCells('A1:D1');
		
		// declare column
		$col_header = array('ID', 'Name', 'Location');
		$col_body = array('', '', '');
		$col_footer = array('width:4', '', '');
		
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row+=2));
		
		// set body column
		$sql = "select r.id, r.name, l.name location
				from m_region r
				  , m_location l
				where r.year = '$year'
				  and l.id = r.id_location
				  and l.year = r.year
				order by r.id";
		$data = $this->db->query($sql)->result_array();
		$total = $this->excel->col_body($excel, $col_body, $col_footer, $col_start, ++$row, $data, true);
		
		// set footer column
		$this->excel->col_footer($excel, $col_footer, $col_start, $row, $data, $total);
		
		/*** SHEET 5 ***/
		$objPHPExcel->createSheet(4);
		$objPHPExcel->setActiveSheetIndex(4)->setTitle('Job Status');
		$excel = $objPHPExcel->getActiveSheet();
		
		// header & parameter
// 		$this->excel->title($excel, 'A', $row=$row_start, "List of Job Status:2");
		$row=$row_start;
		$objRichText = new PHPExcel_RichText();
		$text = $objRichText->createTextRun('List of Job Status ');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ) );
		$text = $objRichText->createTextRun('(not for edit)');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
		$excel->setCellValue("A1", $objRichText)->mergeCells('A1:D1');
		
		// declare column
		$col_header = array('Job Status');
		$col_body = array('');
		$col_footer = array('');
		
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row+=2));
		
		// set body column
		$sql = "select 'Permanent' status
				union all
				select 'Contract' status";
		$data = $this->db->query($sql)->result_array();
		$total = $this->excel->col_body($excel, $col_body, $col_footer, $col_start, ++$row, $data, true);
		
		// set footer column
		$this->excel->col_footer($excel, $col_footer, $col_start, $row, $data, $total);
	
		$this->excel->save($objPHPExcel);
		exit;
	
	}
	
// 	function get_excel() {

// 		$search = (isset($_REQUEST['datatable_search'])?$_REQUEST['datatable_search']:'');
// 		$id_structure = (isset($_REQUEST['id_structure'])?$_REQUEST['id_structure']:'0.0.0.0');
// 		$arr = explode(".", $id_structure);

// 		// declare variable
// 		$col_start = 'A';
// 		$row_start = 1;
// 		$title = "Employee Existing";

// 		$objPHPExcel = $this->excel->properties($title);
// 		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');

// 		/*** SHEET 1 ***/
// 		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Employee Existing');
// 		$excel = $objPHPExcel->getActiveSheet();

// 		// header & parameter
// 		$row = $row_start;

// 		// declare column
// 		$col_header = array('NIK', 'Employee', 'ID Structure', 'SBU', 'ID Job Position', 'Job Position', 'Location', 'ID Region', 'Region', 'Job Status');
// 		$col_body = array('text', '', '', '', '', '', '', '', '', '');
// 		$col_footer = array('', '', 'width:11', '', 'width:13', '', '', 'width:9', '', '');

// 		// set header column
// 		$this->excel->col_header1($excel, $col_header, $col_start, ($row));
		
// 		$sql = "select value year from m_parameter where name = 'Active Budget'";
// 		$data2 = $this->db->query($sql)->row_array();
// 		$year = $data2['year'];

// 		// set body column
// 		$sql = "select a.nik, a.employee
// 				  , a.id_structure, a.sbu
// 				  , a.id_job_position, a.job_position, a.location
// 				  , a.id_region, a.region, a.job_status
// 				from (
// 				    select e.nik, e.name employee
// 				      , m.id_structure, ifnull(get_list_sbu_parent(m.id_structure), '') sbu
// 				      , m.id_job_position, ifnull(jp.name, '') job_position, ifnull(l.name, '') location
// 				      , m.id_region, ifnull(r.name, '') region, e.job_status
// 				    from m_employee e
// 				      , t_mpp m
// 				      left outer join m_job_position jp
// 				        on jp.id = m.id_job_position and jp.year = '$year'
// 				      left outer join m_region r
// 				        on r.id = m.id_region and r.year = '$year'
// 				      left outer join m_location l
// 				        on l.id = r.id_location
// 				      , m_structure s
// 				    where e.status = 1
// 				      and m.nik_employee = e.nik 
// 				      and m.job_remark = 'employee' 
// 				      and m.year = '$year'
// 				      and m.id_structure like '".$arr[0].".%'
// 				      and s.id = m.id_structure 
// 				      and s.year = m.year
// 				  ) a
// 				where concat(a.nik, a.employee, a.sbu, a.job_position, a.location, a.region) like '%$search%'";
// 		write_log($this, __METHOD__, "sql : $sql");
// 		$data = $this->db->query($sql)->result_array();
// 		$total = $this->excel->col_body($excel, $col_body, $col_footer, $col_start, ++$row, $data, true);

// 		// set footer column
// 		$this->excel->col_footer($excel, $col_footer, $col_start, $row, $data, $total);

// 		$this->excel->save($objPHPExcel);
// 		exit;

// 	}

	function get_excel() {

		$search = (isset($_REQUEST['datatable_search'])?$_REQUEST['datatable_search']:'');
		
		ini_set('memory_limit', '-1');
		require_once 'publics/export/Spout/Autoloader/autoload.php';
		
		$title = "Employee Existing";
		$writer = WriterFactory::create(Type::XLSX);
		$writer->openToBrowser("$title.xlsx");
		$sheet = $writer->getCurrentSheet();
		$sheet->setName("$title");
		
		$style_header = (new StyleBuilder())->setFontName('Calibri')->setFontSize(10)->setFontBold()->build();
		$style_body = (new StyleBuilder())->setFontName('Calibri')->setFontSize(10)->build();
		$writer->addRowWithStyle(['NIK', 'Employee', 'ID Structure', 'SBU', 'ID Job Position', 'Job Position', 'Location', 'ID Region', 'Region', 'Job Status'], $style_header);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];

		// set body column
		$sql = "select a.nik, a.employee
				  , a.id_structure, a.sbu
				  , a.id_job_position, a.job_position, a.location
				  , a.id_region, a.region, a.job_status
				from (
				    select e.nik, e.name employee
				      , m.id_structure, ifnull(get_list_sbu_parent(m.id_structure), '') sbu
				      , m.id_job_position, ifnull(jp.name, '') job_position, ifnull(l.name, '') location
				      , m.id_region, ifnull(r.name, '') region, e.job_status
				    from m_employee e
				      , t_mpp m
				      left outer join m_job_position jp
				        on jp.id = m.id_job_position and jp.year = '$year'
				      left outer join m_region r
				        on r.id = m.id_region and r.year = '$year'
				      left outer join m_location l
				        on l.id = r.id_location
				      , m_structure s
				    where e.status = 1
				      and m.nik_employee = e.nik
				      and m.job_remark = 'employee'
				      and m.year = '$year'
				      and s.id = m.id_structure
				      and s.year = m.year
				  ) a
				where concat(a.nik, a.employee, a.sbu, a.job_position, a.location, a.region) like '%$search%'";
		write_log($this, __METHOD__, "sql : $sql");
		
		$data = $this->db->query($sql)->result_array();
		foreach($data as $row) {
			$writer->addRowWithStyle([$row['nik'], $row['employee'], $row['id_structure'], $row['sbu'], intval($row['id_job_position']), $row['job_position'], $row['location'], intval($row['id_region']), $row['region'], $row['job_status']], $style_body);
		}
				  
		$writer->close();
		
	}
	
}	