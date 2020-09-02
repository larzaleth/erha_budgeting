<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_employee extends CI_Controller {

	public function __construct() {

		parent::__construct();
		is_logged_in($this);
		$this->load->model('excel');
		$this->load->model('m_employee_model');
		
	}
	
	public function index() {
		
		// Content
		$data['content'] = 'm_employee/list';
		
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
										  and p.name like '%employee'")->row_array();
		$data['priv'] = $query['priv'];

		$this->load->view('inc/template', $data);
		
	}
	
	function get_list() {

		$data = $this->m_employee_model->get_list();
		echo json_encode($data);

	}
	
	function add() {
	
		// Content
		$data['content'] = 'm_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
		$data['job_level'] = $this->db->query("select id, name from m_job_level where id <> 1 and status = '1'")->result();
		$data['location'] = $this->db->query("select id, name from m_location where year = '$year'")->result();
		
		$this->load->view('inc/template', $data);
	
	}
	
	function edit() {

		// Content
		$data['content'] = 'm_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
		$nik = $this->uri->segment(3);
		$sql = "select e.nik, e.name
					, m.id_structure, concat(m.id_structure, ' - ', get_list_sbu_parent(m.id_structure)) name_structure
					, jp.id_job_level, m.id_job_position, r.id_location, m.id_region, e.job_status
				from m_employee e
				  left outer join t_mpp m
				    on m.nik_employee = e.nik and m.job_remark = 'employee' and m.year = '$year'
				  left outer join m_job_position jp
				    on jp.id = m.id_job_position
				  left outer join m_region r
				    on r.id = m.id_region
				where e.nik = '$nik' and e.status = 1";
		$data['result'] = $this->db->query($sql)->result();
		
		$data['job_level'] = $this->db->query("select id, name from m_job_level where id <> 1 and status = '1'")->result();
		$data['location'] = $this->db->query("select id, name from m_location where year = '$year'")->result();
		
		$this->load->view('inc/template', $data);
		
	}
	
	function delete() {
	
		// Content
		$data['content'] = 'm_employee/stage';
		$data['func'] = ucfirst(__FUNCTION__);
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
		
		$nik = $this->uri->segment(3);
		$sql = "select e.nik, e.name
					, m.id_structure, concat(m.id_structure, ' - ', get_list_sbu_parent(m.id_structure)) name_structure
					, jp.id_job_level, m.id_job_position, r.id_location, m.id_region, e.job_status
				from m_employee e
				  left outer join t_mpp m
				    on m.nik_employee = e.nik and m.job_remark = 'employee' and m.year = '$year'
				  left outer join m_job_position jp
				    on jp.id = m.id_job_position
				  left outer join m_region r
				    on r.id = m.id_region
				where e.nik = '$nik' and e.status = 1";
		$data['result'] = $this->db->query($sql)->result();
		
		$data['job_level'] = $this->db->query("select id, name from m_job_level where id <> 1 and status = '1'")->result();
		$data['location'] = $this->db->query("select id, name from m_location where year = '$year'")->result();
		
		$this->load->view('inc/template', $data);
		
	}
	
	function save() {
		
		$this->m_employee_model->save();
	
	}
	
	function update() {
		
		$this->m_employee_model->update();
	
	}
	
	function confirm_delete() {
		
		$this->m_employee_model->confirm_delete();
	
	}
	
	function get_job_position() {
	
		$id_job_level = (isset($_REQUEST['id_job_level'])?$_REQUEST['id_job_level']:1);
		$data = $this->db->query("select id, name from m_job_position where id_job_level = '$id_job_level'")->result_array();
		echo json_encode($data);
	
	}
	
	function get_region() {
		
		$sql = "select value year from m_parameter where name = 'Active Budget'";
		$data2 = $this->db->query($sql)->row_array();
		$year = $data2['year'];
	
		$id_location = (isset($_REQUEST['id_location'])?$_REQUEST['id_location']:1);
		$data = $this->db->query("select id, name from m_region where id_location = '$id_location' and year = '$year'")->result_array();
		echo json_encode($data);
	
	}
	
	function get_list_modal() {
		
		$data = $this->m_employee_model->get_list_modal();
		echo json_encode($data);
		
	}
	
	function import() {
	
		// Content
		$data['content'] = 'm_employee/import';
	
		$this->load->view('inc/template', $data);
	
	}
	
	public function get_template() {
	
		$this->m_employee_model->get_template();
	
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
				
			redirect('/m_employee');
	
		} else {
			
			$sql = "select value year from m_parameter where name = 'Active Budget'";
			$data2 = $this->db->query($sql)->row_array();
			$year = $data2['year'];
				
			require_once 'publics/export/PHPExcel/IOFactory.php';
				
			$data = $this->upload->data();
				
			$file_path = $data['full_path'];
			$inputFileName = $file_path;
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
			
			$error_count = 0; $error_max = 2000;
			$message = "";
			for($i = 2; $i <= $arrayCount; $i++) {
				
				$id_structure = $allDataInSheet[$i]["C"];
				$sql = "select 1 from m_structure where id <> '0.0.0.0' and year = '$year' and id = '$id_structure'";
				$qty = $this->db->query($sql)->num_rows();
				if($qty == 0) {
					$error_count++;
					if($error_count <= $error_max - 1) $message .= "<br/>* data line $i is not valid (ID Structure = $id_structure)";
					else $message .= "<br/>* ...";
				}
				if($error_count >= $error_max) break;
				
				$id_job_position = $allDataInSheet[$i]["D"];
				$sql = "select 1 from m_job_position where year = '$year' and id = '$id_job_position'";
				$qty = $this->db->query($sql)->num_rows();
				if($qty == 0) {
					$error_count++;
					if($error_count <= $error_max - 1) $message .= "<br/>* data line $i is not valid (ID Job Position = $id_job_position)";
					else $message .= "<br/>* ...";
				}
				if($error_count >= $error_max) break;
				
				$id_region = $allDataInSheet[$i]["E"];
				$sql = "select 1 from m_region where year = '$year' and id = '$id_region'";
				$qty = $this->db->query($sql)->num_rows();
				if($qty == 0) {
					$error_count++;
					if($error_count <= $error_max - 1) $message .= "<br/>* data line $i is not valid (ID Region = $id_region)";
					else $message .= "<br/>* ...";
				}
				if($error_count >= $error_max) break;
				
				$job_status = $allDataInSheet[$i]["F"];
				if($job_status <> "Permanent" && $job_status <> "Contract") {
					$error_count++;
					if($error_count <= $error_max - 1) $message .= "<br/>* data line $i is not valid (Job Status = $job_status)";
					else $message .= "<br/>* ...";
				}
				if($error_count >= $error_max) break;
				
			}
			
			if($error_count > 0) {
				
				$this->session->set_flashdata('status', '4');
				$this->session->set_flashdata('message', $error = array('error' => $message));
				
			} else {
			
				for($i = 2; $i <= $arrayCount; $i++) {
	
					$user_login = $this->session->userdata("id");
					$nik = $allDataInSheet[$i]["A"];
					$name = str_replace("'","''",$allDataInSheet[$i]["B"]);
					$id_structure = $allDataInSheet[$i]["C"];
					$id_job_position = $allDataInSheet[$i]["D"];
					$id_region = $allDataInSheet[$i]["E"];
					$job_status = $allDataInSheet[$i]["F"];
					
					$sql = "select 1 from m_employee where nik = '$nik'";
					$exist = $this->db->query($sql)->num_rows();
	
					if($exist > 0) {
		
						$sql = "update m_employee
								set name = '$name', job_status = '$job_status', status = '1'
								  , modified_by = '$user_login', modified_date = sysdate()
								where nik = '$nik'";
	
					} else {
		
						$sql = "insert into m_employee(nik, name, job_status, created_by, created_date, modified_by, modified_date)
								values('$nik', '$name', '$job_status', '$user_login', sysdate(), '$user_login', sysdate())";
	
					}
					$result = $this->db->query($sql);
					
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
					$result = $this->db->query($sql);
					
				}
				
				$this->session->set_flashdata('status', '5');
				$this->session->set_flashdata('message', $arrayCount - 1);
				
			}
	
			redirect('/m_employee');
				
		}
	
	}
	
	public function get_excel() {
	
		$this->m_employee_model->get_excel();
	
	}
	
}
