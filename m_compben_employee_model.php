<?php

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

class m_compben_employee_model extends CI_Model {
	
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
		
// 		$sql = "select a.id, a.nik, a.name, a.compben, a.structure, a.rate
// 				from (
// 				    select cr.id, cr.nik_employee nik, e.name, c.name compben
// 				      , concat(m.id_structure,' - ',s.name) structure, cr.rate
// 				    from m_compben_rate cr
// 				      , m_employee e
// 				      , t_mpp m
// 				      , m_structure s
// 				      , m_compben c
// 				    where cr.year = '$year'
// 				      and e.nik = cr.nik_employee
// 				      and m.nik_employee = e.nik 
// 				      and m.job_remark = 'employee' 
// 				      and m.year = cr.year
// 				      and m.id_structure like '".$arr[0].".%'
// 				      and s.id = m.id_structure and s.year = cr.year
// 				      and c.id = cr.id_compben
// 				  ) a
// 				where concat(a.nik, a.name, a.compben) like '%$search%'";
		$sql = "select a.id, a.nik, a.name, a.compben, a.structure, a.rate
				from (
				    select cr.id, cr.nik_employee nik, e.name, c.name compben
				      , concat(m.id_structure,' - ',s.name) structure, cr.rate
				    from m_compben_rate cr
				      , m_employee e
				      , t_mpp m
				      , m_structure s
				      , m_compben c
				    where cr.year = '$year'
				      and e.nik = cr.nik_employee
				      and m.nik_employee = e.nik
				      and m.job_remark = 'employee'
				      and m.year = cr.year
				      and s.id = m.id_structure and s.year = cr.year
				      and c.id = cr.id_compben
				  ) a
				where concat(a.nik, a.name, a.compben) like '%$search%'";
		write_log($this, __METHOD__, "sql : $sql");
		$total_rows = $this->db->query($sql)->num_rows();
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
	
	function save() {
		
		$nik = $this->input->post('nik');
		$name = $this->input->post('name');
		$id_compben = $this->input->post('id_compben');
		
		$sql = "select p.value year
				from m_parameter p
				where p.name = 'Active Budget'";
		$data = $this->db->query($sql)->row_array();
		$year = $data['year'];
		
		$sql = "select 1 from m_compben_rate where nik_employee = '$nik' and id_compben = '$id_compben' and year = '$year'";
		$exist = $this->db->query($sql)->num_rows();
		
		if($exist > 0) {
			
			$sql = "select name from m_compben where id = '$id_compben'";
			$data = $this->db->query($sql)->row_array();
			$compben = $data['name'];
				
			$this->session->set_flashdata('status', '6');
			$this->session->set_flashdata('message', "Duplicate entry $name & $compben!");
				
		} else {
		
			$sql = "select ifnull(max(id), 0) + 1 max_id from m_compben_rate";
			$data = $this->db->query($sql)->row_array();
			$max_id = $data["max_id"];
		
			$data = array(
				'id' => $max_id,
				'nik_employee' => $nik,
				'id_compben' => $this->input->post('id_compben'),
				'rate' => str_replace(",", "", $this->input->post('rate')),
				'year' => $year,
				'created_by' => $this->session->userdata("id"),
				'created_date' => date("Y-m-d H:m:s"),
				'modified_by' => $this->session->userdata("id"),
				'modified_date' => date("Y-m-d H:m:s")
			);
			$this->db->insert('m_compben_rate',array_filter($data));
		
			$this->session->set_flashdata('status', '1');
		
		}
	
		redirect('/m_compben_employee');
	
	}
	
	function update() {
		
		$id = $this->input->post('id');
		$nik = $this->input->post('nik');
		$name = $this->input->post('name');
		$id_compben = $this->input->post('id_compben');
		
		$sql = "select p.value year
				from m_parameter p
				where p.name = 'Active Budget'";
		$data = $this->db->query($sql)->row_array();
		$year = $data['year'];

		$sql = "select 1 from m_compben_rate where id <> $id and nik_employee = '$nik' and id_compben = '$id_compben' and year = '$year'";
		$exist = $this->db->query($sql)->num_rows();

		if($exist > 0) {
			
			$sql = "select name from m_compben where id = '$id_compben'";
			$data = $this->db->query($sql)->row_array();
			$compben = $data['name'];

			$this->session->set_flashdata('status', '6');
			$this->session->set_flashdata('message', "Duplicate entry $name & $compben!");

		} else {
		
			$data = array(
				'nik_employee' => $nik,
				'id_compben' => $this->input->post('id_compben'),
				'rate' => str_replace(",", "", $this->input->post('rate')),
				'modified_by' => $this->session->userdata("id"),
				'modified_date' => date("Y-m-d H:m:s")
			);
			$this->db->where('id', $id)->update('m_compben_rate', $data);
			
			$this->session->set_flashdata('status', '2');
		
		}
		
		redirect('/m_compben_employee');
		
	}
	
	function confirm_delete() {
	
		$data = array(
			'id' => $this->input->post('id')
		);
		$this->db->delete('m_compben_rate', $data);
	
		$this->session->set_flashdata('status', '3');
	
		redirect('/m_compben_employee');
	
	}
	
	function get_list_modal() {
	
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$per_page = (isset($_REQUEST['modal_datatable_per_page'])?$_REQUEST['modal_datatable_per_page']:20);
		$search = (isset($_REQUEST['modal_datatable_search'])?$_REQUEST['modal_datatable_search']:'');
		$base_url = '';
		
		$sql = "select p.value year
				from m_parameter p
				where p.name = 'Active Budget'";
		$data = $this->db->query($sql)->row_array();
		$year = $data['year'];
		
		$sql = "select a.nik, a.name
				from (
						select e.nik, concat(e.nik,' - ',e.name) name
						from m_employee e
						  , t_mpp m
						  , m_structure s
						where e.status = '1'
						  and m.nik_employee = e.nik
						  and m.job_remark = 'employee'
						  and m.year = '$year'
						  and s.id = m.id_structure and s.year = m.year
					) a
				where a.name like '%$search%'
				order by a.nik";
		$total_rows = $this->db->query($sql)->num_rows();
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
	
	function get_template() {
		
		// declare variable
		$col_start = 'A';
		$row_start = 1;
		$title = "Template CompBen Employee";
	
		$objPHPExcel = $this->excel->properties($title);
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		
		/*** SHEET 1 ***/
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Template');
		$excel = $objPHPExcel->getActiveSheet();
		
		// header & parameter
		$row = $row_start;
		
		// declare column
		$col_header = array('NIK', 'Full Name', 'ID CompBen', 'Rate');
		
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row));
		$excel->getColumnDimension('A')->setWidth(13);
		$excel->getColumnDimension('B')->setWidth(30);
		$excel->getColumnDimension('C')->setWidth(13);
		$excel->getColumnDimension('D')->setWidth(17);
		$excel->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$excel->getStyle('D')->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* (#,##0.00);_("Rp"* "-"??_);_(@_)');
		
		
		/*** SHEET 2 ***/
		$objPHPExcel->createSheet(1);
		$objPHPExcel->setActiveSheetIndex(1)->setTitle('CompBen');
		$excel = $objPHPExcel->getActiveSheet();
		
		// header & parameter
// 		$this->excel->title($excel, 'A', $row=$row_start, "List of CompBen Category:2");
		$row=$row_start;
		$objRichText = new PHPExcel_RichText();
		$text = $objRichText->createTextRun('List of CompBen Category ');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ) );
		$text = $objRichText->createTextRun('(not for edit)');
		$text->getFont()->setBold(true)->setSize(13)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
		$excel->setCellValue("A1", $objRichText)->mergeCells('A1:C1');
		
		// declare column
		$col_header = array('ID', 'Name');
		$col_body = array('', '');
		$col_footer = array('', '');
		
		// set header column
		$this->excel->col_header1($excel, $col_header, $col_start, ($row+=2));
	
		// set body column
		$sql = "select id, name from m_compben where status = '1' order by id";
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
// 		$title = "CompBen Employee Existing";
		
// 		$objPHPExcel = $this->excel->properties($title);
// 		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		
// 		/*** SHEET 1 ***/
// 		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Compben Employee Existing');
// 		$excel = $objPHPExcel->getActiveSheet();
		
// 		// header & parameter
// 		$row = $row_start;
		
// 		// declare column
// 		$col_header = array('NIK', 'Name', 'ID Compben', 'Compben', 'SBU', 'Rate');
// 		$col_body = array('text', '', '', '', '', 'number');
// 		$col_footer = array('', '', 'width:11', '', '', '');
		
// 		// set header column
// 		$this->excel->col_header1($excel, $col_header, $col_start, ($row));
		
// 		$sql = "select value year from m_parameter where name = 'Active Budget'";
// 		$data2 = $this->db->query($sql)->row_array();
// 		$year = $data2['year'];
		
// 		// set body column
// 		$sql = "select a.nik, a.name, a.id_compben, a.compben, a.structure, a.rate
// 				from (
// 				    select cr.nik_employee nik, e.name, cr.id_compben, c.name compben
// 				      , concat(m.id_structure,' - ',s.name) structure, cr.rate
// 				    from m_compben_rate cr
// 				      , m_employee e
// 				      , t_mpp m
// 				      , m_structure s
// 				      , m_compben c
// 				    where cr.year = '$year'
// 				      and e.nik = cr.nik_employee
// 				      and m.nik_employee = e.nik 
// 				      and m.job_remark = 'employee' 
// 				      and m.year = cr.year
// 				      and m.id_structure like '".$arr[0].".%'
// 				      and s.id = m.id_structure and s.year = cr.year
// 				      and c.id = cr.id_compben
// 				  ) a
// 				where concat(a.nik, a.name, a.compben) like '%$search%'";
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
		
		$title = "CompBen Employee Existing";
		$writer = WriterFactory::create(Type::XLSX);
		$writer->openToBrowser("$title.xlsx");
		$sheet = $writer->getCurrentSheet();
		$sheet->setName("$title");
		
		$style_header = (new StyleBuilder())->setFontName('Calibri')->setFontSize(10)->setFontBold()->build();
		$style_body = (new StyleBuilder())->setFontName('Calibri')->setFontSize(10)->build();
		$writer->addRowWithStyle(['NIK', 'Name', 'ID Compben', 'Compben', 'SBU', 'Rate'], $style_header);		
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];

		// set body column
		$sql = "select a.nik, a.name, a.id_compben, a.compben, a.structure, a.rate
				from (
				    select cr.nik_employee nik, e.name, cr.id_compben, c.name compben
				      , concat(m.id_structure,' - ',s.name) structure, cr.rate
				    from m_compben_rate cr
				      , m_employee e
				      , t_mpp m
				      , m_structure s
				      , m_compben c
				    where cr.year = '$year'
				      and e.nik = cr.nik_employee
				      and m.nik_employee = e.nik
				      and m.job_remark = 'employee'
				      and m.year = cr.year
				      and s.id = m.id_structure and s.year = cr.year
				      and c.id = cr.id_compben
				  ) a
				where concat(a.nik, a.name, a.compben) like '%$search%'";
		write_log($this, __METHOD__, "sql : $sql");
		
		$data = $this->db->query($sql)->result_array();
		foreach($data as $row) {
			$writer->addRowWithStyle([$row['nik'], $row['name'], intval($row['id_compben']), $row['compben'], $row['structure'], floatval($row['rate'])], $style_body);
		}
		
		$writer->close();

	}
	
}	