<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_compben_employee extends CI_Controller {

	public function __construct() {

		parent::__construct();
		is_logged_in($this);
		$this->load->model('excel');
		$this->load->model('m_compben_employee_model');
		$this->load->model('compben_employee_summary_model');
		
	}
	
	public function index() {
		
		// Content
		$data['content'] = 'm_compben_employee/list';
		
		$id_group = $this->session->userdata("id_group");
		
// 		$sql = "select s.id, s.name
// 				from m_parameter p
// 				  , m_structure s
// 				where p.name = 'Active Budget'
// 				  and s.year = p.value
// 				  and s.id_structure_type = 1
// 				  and s.id <> '0.0.0.0'";
// 		$data['structures'] = $this->db->query($sql)->result();
		
		$query = $this->db->query("select group_concat(p.name) priv
										from m_user_priv up
										  , m_priv p
										where up.id_user_group = '$id_group'
										  and p.id = up.id_priv
										  and p.name like '%compben rate employee'")->row_array();
		$data['priv'] = $query['priv'];
		
		$this->load->view('inc/template', $data);
		
	}
	
	function get_list() {
	
		$data = $this->m_compben_employee_model->get_list();
		echo json_encode($data);
	
	}
	
	function add() {
	
		// Content
		$data['content'] = 'm_compben_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$data['compben'] = $this->db->query("select id, name from m_compben where status = '1'")->result();
		
		$this->load->view('inc/template', $data);
	
	}
	
	function edit() {
	
		// Content
		$data['content'] = 'm_compben_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$id = $this->uri->segment(3);
		$sql = "select cr.id, cr.nik_employee nik, concat(cr.nik_employee,' - ',e.name) name, cr.id_compben, cr.rate
				from m_compben_rate cr
				  , m_employee e
				where cr.id = '$id'
				  and e.nik = cr.nik_employee";
		$data['result'] = $this->db->query($sql)->result();
		
		$data['compben'] = $this->db->query("select id, name from m_compben where status = '1'")->result();
		
		$this->load->view('inc/template', $data);
		
	}
	
	function delete() {
	
		// Content
		$data['content'] = 'm_compben_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$id = $this->uri->segment(3);
		$sql = "select cr.id, cr.nik_employee nik, concat(cr.nik_employee,' - ',e.name) name, cr.id_compben, cr.rate
				from m_compben_rate cr
					, m_employee e
				where cr.id = '$id'
					and e.nik = cr.nik_employee";
		$data['result'] = $this->db->query($sql)->result();
		
		$data['compben'] = $this->db->query("select id, name from m_compben where status = '1'")->result();
		
		$this->load->view('inc/template', $data);
		
	}
	
	function save() {
		
		$this->m_compben_employee_model->save();
	
	}
	
	function update() {
		
		$this->m_compben_employee_model->update();
	
	}
	
	function confirm_delete() {
		
		$this->m_compben_employee_model->confirm_delete();
	
	}
	
	function get_list_modal() {
	
		$data = $this->m_compben_employee_model->get_list_modal();
		echo json_encode($data);
	
	}
	
	function import() {
	
		// Content
		$data['content'] = 'm_compben_employee/import';
	
		$this->load->view('inc/template', $data);
	
	}
	
	public function get_template() {
	
		$this->m_compben_employee_model->get_template();
	
	}
	
	function upload() {
		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '1024';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		
		if (!$this->upload->do_upload('file')) {

			$error = array('error' => $this->upload->display_errors());
			
			$this->session->set_flashdata('status', '4');
			$this->session->set_flashdata('message', $error);
			
			redirect('/m_compben_employee');
		
		} else {
			
			require_once 'publics/export/PHPExcel/IOFactory.php';
			
			$data = $this->upload->data();
			
			$file_path = $data['full_path'];
			$inputFileName = $file_path;
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
			write_log($this, __METHOD__, "arrayCount : $arrayCount");
			
			$sql = "select p.value year from m_parameter p where p.name = 'Active Budget'";
			$data = $this->db->query($sql)->row_array();
			$year = $data['year'];
			
			$error_count = 0;
			$message = "";
			for($i = 2; $i <= $arrayCount; $i++) {
					
				$nik = $allDataInSheet[$i]["A"];
				if($nik <> "") {
					
					$sql = "select 1
							from m_employee e
							  , t_mpp m
							  , m_structure s
							where e.nik = '$nik'
							  and e.status = '1'
							  and m.nik_employee = e.nik
							  and m.job_remark = 'employee'
							  and m.year = '$year'
							  and s.id = m.id_structure and s.year = m.year";
					$qty = $this->db->query($sql)->num_rows();
					if($qty == 0) {
						$error_count++;
						if($error_count <= 3) $message .= "<br/>* data line $i is not valid (NIK = $nik)";
						else $message .= "<br/>* ...";
					}
					if($error_count >= 4) break;
					
					$id_compben = $allDataInSheet[$i]["C"];
					$sql = "select 1 from m_compben where status = '1' and id = '$id_compben'";
					$qty = $this->db->query($sql)->num_rows();
					if($qty == 0) {
						$error_count++;
						if($error_count <= 3) $message .= "<br/>* data line $i is not valid (ID CompBen = $id_compben)";
						else $message .= "<br/>* ...";
					}
					if($error_count >= 4) break;
					
					$rate = str_replace(",", "", str_replace("Rp ", "", $allDataInSheet[$i]["D"]));
					if(!is_numeric($rate)) {
						$error_count++;
						if($error_count <= 3) $message .= "<br/>* data line $i is not valid (Rate = $rate)";
						else $message .= "<br/>* ...";
					}
					if($error_count >= 4) break;
					
				}
				
			}
			
			if($error_count > 0) {
			
				$this->session->set_flashdata('status', '4');
				$this->session->set_flashdata('message', $error = array('error' => $message));
			
			} else {

				$sql = "delete from m_compben_rate where nik_employee is not null and year = '$year'";
				$result = $this->db->query($sql);
					
				$sql = "select ifnull(max(id), 0) + 1 max_id from m_compben_rate";
				$data = $this->db->query($sql)->row_array();
				$max_id = $data["max_id"];
					
				$j = 0;
				for($i = 2; $i <= $arrayCount; $i++) {
				
					$nik = $allDataInSheet[$i]["A"];
					$full_name = $allDataInSheet[$i]["B"];
					$id_compben = $allDataInSheet[$i]["C"];
					$rate = str_replace(",","",$allDataInSheet[$i]["D"]);
					write_log($this, __METHOD__, "nik : $nik | full_name : $full_name | id_compben : $id_compben | rate : $rate");
					
					if($nik <> "") {
					
	 					$sql = "select id
	 							from m_compben_rate
	 							where nik_employee = '$nik' and id_compben = '$id_compben' and year = '$year'";
	 					$qty = $this->db->query($sql)->num_rows();
						
	 					if($qty > 0) { // if data exist then update
							
	 						$data = $this->db->query($sql)->row_array();
	 						$id = $data['id'];
							
	 						$data = array(
	 								'rate' => str_replace(",", "", str_replace("Rp ", "", $rate)),
	 								'modified_by' => $this->session->userdata("id"),
	 								'modified_date' => date("Y-m-d H:m:s")
	 						);
	 						$result = $this->db->where('id', $id)->update('m_compben_rate', $data);
							
	 					} else { // if data not exist then insert
							
							$data = array(
								'id' => $max_id + $j,
								'nik_employee' => $nik,
								'id_compben' => $id_compben,
								'rate' => str_replace(",", "", str_replace("Rp ", "", $rate)),
								'year' => $year,
								'created_by' => $this->session->userdata("id"),
								'created_date' => date("Y-m-d H:m:s"),
								'modified_by' => $this->session->userdata("id"),
								'modified_date' => date("Y-m-d H:m:s")
							);
							$result = $this->db->insert('m_compben_rate',array_filter($data));
						
	 					}
	
						$j++;
	
					}
					
				}
				
				$this->session->set_flashdata('status', '5');
				$this->session->set_flashdata('message', $arrayCount - 1);
			
			}
				
			redirect('/m_compben_employee');
			
		}
		
	}
	
	public function get_excel() {
	
		$this->m_compben_employee_model->get_excel();
	
	}
	
	public function index_summary() {
	
		// Content
		$data['content'] = 'compben_employee_summary/list';
	
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$data['year'] = $data2['year'];
	
		$data['id_structure'] = (isset($_REQUEST['structures'])?$_REQUEST['structures']:'');
		$data['structures'] = $this->db->query("select id, name, CAST(SPLIT_STR(m_structure.id, '.', 1) as SIGNED) as id1,CAST(SPLIT_STR(m_structure.id, '.', 2) as SIGNED) as id2, CAST(SPLIT_STR(m_structure.id, '.', 3) as SIGNED) as id3, CAST(SPLIT_STR(m_structure.id, '.', 4) as SIGNED) as id4 from m_structure where id_structure_type = 1 and id <> '0.0.0.0' and year = '" . $data['year'] . "' order by id1 ASC, id2 ASC, id3 ASC, id4 ASC")->result();
	
		$data['header'] = $this->compben_employee_summary_model->get_header();
	
		$data['list'] = null;
	
		$this->load->view('inc/template', $data);
	
	}
	
	function get_list_summary() {
	
		$data['content'] = 'compben_employee_summary/list';
	
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$data['year'] = $data2['year'];
	
		$data['id_structure'] = (isset($_REQUEST['structures'])?$_REQUEST['structures']:'');
		$data['structures'] = $this->db->query("select id, name, CAST(SPLIT_STR(m_structure.id, '.', 1) as SIGNED) as id1,CAST(SPLIT_STR(m_structure.id, '.', 2) as SIGNED) as id2, CAST(SPLIT_STR(m_structure.id, '.', 3) as SIGNED) as id3, CAST(SPLIT_STR(m_structure.id, '.', 4) as SIGNED) as id4 from m_structure where id_structure_type = 1 and id <> '0.0.0.0' and year = '" . $data['year'] . "' order by id1 ASC, id2 ASC, id3 ASC, id4 ASC")->result();
	
		$data['header'] = $this->compben_employee_summary_model->get_header();
	
		$child = $data["header"]["0"]["child"];
	
		$data['list'] = "";
		$list = $this->compben_employee_summary_model->get_lists($data['header']);
		foreach($list as $row) {
				
			$data['list'] .= '<tr class="gradeX odd" role="row">';
			foreach($row as $id => $val) {
				if(intval($val) > 0){
					$val = number_format(floatval($val),2,'.',',');
				}
				$data['list'] .= '<td class="sorting_1" style="padding-left:10px;min-width:130px">'.$val.'</td>';
			}
			$data['list'] .= '</tr>';
				
		}
	
		$this->load->view('inc/template', $data);
	
	}
	
	public function get_excel_summary() {
	
		$this->compben_employee_summary_model->get_excel();
	
	}
	 
}
