<?php
class ControllerProduccionMaquina extends Controller {
	private $error = array();

	public function index() {
		//VISUALIZAR ERRORES
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		//
		$this->load->language('produccion/Maquina');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/Maquina');
		//CREA LA TABLA SI NO EXISTE
		//$this->model_produccion_Maquina->bajaMaquina();
		$this->model_produccion_Maquina->creaMaquina();
		
		//			
		$this->getList();
	}
	public function sincro(){
		$this->load->language('produccion/Maquina');
		$this->load->model('produccion/Maquina');
		$this->model_produccion_Maquina->traeMaquina();
		$this->session->data['success'] = $this->language->get('text_success');
		$url = $this->filtrar($this->request->get,"gral");
		$this->response->redirect($this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true));
	}
	
	public function filtrar($get,$accion="gral"){
	
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}	
		
		$url='';

		//generico
		if (isset($get['filter_Maquina_id'])) {
			$url .= '&filter_Maquina_id=' . $get['filter_Maquina_id'];
		}
		if (isset($get['filter_status'])) {
			$url .= '&filter_status=' . $get['filter_status'];
		}
		//fin generico
		
		if ($accion=="gral"){
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			
		}
		if ($accion=="column"){
			if ($order == 'ASC') {
				$url .= '&order=DESC';
			} else {
				$url .= '&order=ASC';
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
		}
		if ($accion=="nopage"){
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}			
		}
		
		if ($accion=="form"){
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		}
		return $url;
	}	

	public function add() {
		
		//VISUALIZAR ERRORES
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		//		
		$this->load->language('produccion/Maquina');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/Maquina');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			

			
			$this->model_produccion_Maquina->addMaquina($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}
	
	public function edit() {
		$this->load->language('produccion/Maquina');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/Maquina');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			/*
			echo "<pre>";
			print_r($this->request->post);
			echo "</pre>";
			die;
			*/
			$this->model_produccion_Maquina->editMaquina($this->request->get['Maquina_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get);
			$this->response->redirect($this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('produccion/Maquina');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/Maquina');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $Maquina_id) {
				$this->model_produccion_Maquina->deleteMaquina($Maquina_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getList();
	}
	
	
	public function copy() {
		$this->load->language('produccion/Maquina');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/Maquina');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $Maquina_id) {
				$this->model_produccion_Maquina->copyMaquina($Maquina_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {

		if (isset($this->request->get['filter_Maquina_id'])) {
			$filter_Maquina_id = $this->request->get['filter_Maquina_id'];
		} else {
			$filter_Maquina_id = '';
		}
		if (isset($this->request->get['filter_denomina'])) {
			$filter_denomina = $this->request->get['filter_denomina'];
		} else {
			$filter_denomina = '';
		}
		if (isset($this->request->get['filter_nota'])) {
			$filter_nota = $this->request->get['filter_nota'];
		} else {
			$filter_nota = '';
		}		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '1';
		}


		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_limit_admin');
		}		
		$data['limit']=$limit;
		
		$url = $this->filtrar($this->request->get,"gral");

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('produccion/Maquina/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['delete'] = $this->url->link('produccion/Maquina/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['copy'] = $this->url->link('produccion/Maquina/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['Maquinas'] = array();
		$filter_data = array(
			'filter_Maquina_id'         => $filter_Maquina_id,
			'filter_denomina'   			=> $filter_denomina,
			'filter_status'            => $filter_status,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
 
		$Maquina_total = $this->model_produccion_Maquina->getTotalMaquinas($filter_data);

		$this->load->model('user/user');
		$results = $this->model_produccion_Maquina->getMaquinas($filter_data);

		foreach ($results as $result) {
			
			
			$user_id_added=$this->model_user_user->getUser($result['user_id_added']);
			
			$user_id_added=$user_id_added['username'];
			if ($result['user_id_modified']!=0){
				$user_id_modified=$this->model_user_user->getUser($result['user_id_modified']);
				$user_id_modified=$user_id_modified['username'];
			}else{
				$user_id_modified="";
			}
			if ($result['user_id_delete']!=0){
				$user_id_delete=$this->model_user_user->getUser($result['user_id_delete']);
				$user_id_delete=$user_id_delete['username'];
			}else{
				$user_id_delete="";
			}

			$data['Maquinas'][] = array(
				'Maquina_id'    	=> $result['Maquina_id'],
				'denomina'    		=> $result['denomina'],
				'code'    			=> $result['code'],
				'modelo'    			=> $result['modelo'],
				'indice'    			=> $result['indice'],
				'date_added'        => $result['date_added']?date('d-m-Y', strtotime($result['date_added'])):"",
				'status'         	=> ($result['status']=='1' ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_modified'     => $result['date_modified']==""?"":date('d-m-Y', strtotime($result['date_modified'])),
				'date_delete'     	=> $result['date_delete']==""?"":date('d-m-Y', strtotime($result['date_delete'])),
				'user_id_added'		=> $user_id_added,
				'user_id_modified'	=> $user_id_modified,
				'user_id_delete'	=> $user_id_delete,
				'edit'           	=> $this->url->link('produccion/Maquina/edit', 'user_token=' . $this->session->data['user_token'] . '&Maquina_id=' . $result['Maquina_id'] . $url, true),
				'clonar'           	=> $this->url->link('produccion/Maquina/clonar', 'user_token=' . $this->session->data['user_token'] . '&Maquina_id=' . $result['Maquina_id'] . $url, true)				
			);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = $this->filtrar($this->request->get,"column");
		$data['sort_Maquina_id'] = $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . '&sort=Maquina_id' . $url, true);
		
		$data['sort_denomina'] = $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . '&sort=denomina' . $url, true);
		
		$data['sort_code'] = $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url, true);		
		
		$url = $this->filtrar($this->request->get,"nopage");
		
		$pagination = new Pagination();
		$pagination->total = $Maquina_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($Maquina_total) ? (($page - 1) *  $limit) + 1 : 0, ((($page - 1) *  $limit) > ($Maquina_total -  $limit)) ? $Maquina_total : ((($page - 1) *  $limit) +  $limit), $Maquina_total, ceil($Maquina_total /  $limit));

		$data['filter_Maquina_id'] = $filter_Maquina_id;
		$data['filter_denomina'] = $filter_denomina;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('produccion/Maquina_list', $data));
	}

	protected function getForm() {
		
				//VISUALIZAR ERRORES
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		
		$data['text_form'] = !isset($this->request->get['Maquina_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['user_token'] = $this->session->data['user_token'];

		//ERRORES
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = $this->filtrar($this->request->get,"gral");
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['Maquina_id'])) {
			$data['action'] = $this->url->link('produccion/Maquina/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('produccion/Maquina/edit', 'user_token=' . $this->session->data['user_token'] . '&Maquina_id=' . $this->request->get['Maquina_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('produccion/Maquina', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['Maquina_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$info = $this->model_produccion_Maquina->getMaquina($this->request->get['Maquina_id']);
		}

		if (isset($this->request->get['Maquina_id'])) {
			$data['Maquina_id'] = $this->request->get['Maquina_id'];
		} else {
			$data['Maquina_id'] = 0;
		}

		if (isset($this->request->post['denomina'])) {
			$data['denomina'] = $this->request->post['denomina'];
		} elseif (!empty($info)) {
			$data['denomina'] = $info['denomina'];
		} else {
			$data['denomina'] = '';
		}		
		
		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($info)) {
			$data['code'] = $info['code'];
		} else {
			$data['code'] = '';
		}		

		if (isset($this->request->post['modelo'])) {
			$data['modelo'] = $this->request->post['modelo'];
		} elseif (!empty($info)) {
			$data['modelo'] = $info['modelo'];
		} else {
			$data['modelo'] = '';
		}	
		if (isset($this->request->post['indice'])) {
			$data['indice'] = $this->request->post['indice'];
		} elseif (!empty($info)) {
			$data['indice'] = $info['indice'];
		} else {
			$data['indice'] = '';
		}		
		
		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($info)) {
			$data['date_added'] = date("d-m-Y",strtotime($info['date_added']));
		} else {
			$data['date_added'] = date('d-m-Y');
		}		
		if (isset($this->request->post['date_modified'])) {
			$data['date_modified'] = $this->request->post['date_modified'];
		} elseif (!empty($info)) {
			$data['date_modified'] = $info['date_modified']==""?"":date("d-m-Y",strtotime($info['date_modified']));
		} else {
			$data['date_modified'] = "";
		}
		if (isset($this->request->post['date_delete'])) {
			$data['date_delete'] = $this->request->post['date_delete'];
		} elseif (!empty($info)) {
			$data['date_delete'] = $info['date_delete']==""?"":date("d-m-Y",strtotime($info['date_delete']));
		} else {
			$data['date_delete'] = "";
		}	
		
		if (isset($this->request->post['user_id_added'])) {
			$data['user_id_added'] = $this->request->post['user_id_added'];
		} elseif (!empty($info)) {
			$data['user_id_added'] = $info['user_id_added'];
		} else {
			$data['user_id_added'] = $this->user->getId();
		}		
		if (isset($this->request->post['user_id_modified'])) {
			$data['user_id_modified'] = $this->request->post['user_id_modified'];
		} elseif (!empty($info)) {
			$data['user_id_modified'] = $info['user_id_modified'];
		} else {
			$data['user_id_modified'] = "";
		}
		if (isset($this->request->post['user_id_delete'])) {
			$data['user_id_delete'] = $this->request->post['user_id_delete'];
		} elseif (!empty($info)) {
			$data['user_id_delete'] = $info['user_id_delete'];
		} else {
			$data['user_id_delete'] = "";
		}
		
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($info)) {
			$data['status'] = $info['status'];
		} else {
			$data['status'] = true;
		}
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('produccion/Maquina_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'produccion/Maquina')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['denomina']) < 1) || (utf8_strlen(trim($this->request->post['denomina'])) > 200)) {
			$this->error['denomina'] = $this->language->get('error_denomina');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'produccion/Maquina')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'produccion/Maquina')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	
		
	public function download_xlsx() {
	
		if (isset($this->request->get['filter_Maquina_id'])) {
			$filter_Maquina_id = $this->request->get['filter_Maquina_id'];
		} else {
			$filter_Maquina_id = '';
		}
		if (isset($this->request->get['filter_denomina'])) {
			$filter_denomina = $this->request->get['filter_denomina'];
		} else {
			$filter_denomina = '';
		}

		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '1';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = date("Y-m-d",strtotime($this->request->get['filter_date_added']));
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($get['page'])) {
			$page = $get['page'];
		}else{
			$page = 1;
		}
		
		if (isset($get['limit'])) {
			$limit = $get['limit'];
		}else{
			$limit = $this->config->get('config_limit_admin');
		}
		$data['Maquinas'] = array();
		$filter_data = array(
			'filter_Maquina_id'        => $filter_Maquina_id,
			'filter_denomina'           => $filter_denomina,
			'filter_nota'           => $filter_nota,
			'filter_status'            => $filter_status,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
		$this->load->model('produccion/Maquina');
		$results = $this->model_produccion_Maquina->getMaquinas($filter_data);
	
	
		//XLSX
		$archivo = "dirsis/download/xlsx_".uniqid().".xlsx";

		require_once 'dirsis/phpexcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->
		getProperties()
					->setCreator("dirsis.com.ar")
					->setLastModifiedBy("dirsis.com.ar")
					->setTitle("Exportar XLSX")
					->setSubject("Excel")
					->setMaquina("reportes");
		/* Datos Hojas */
		$row=1;
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$row,  "id")
					->setCellValue('B'.$row,  "Denominacion")
					->setCellValue('C'.$row,  "Nota")
					->setCellValue('D'.$row,  "Estado")
					->setCellValue('E'.$row,  "Fecha Alta");
		$row++;	
		foreach ($results as $result) {
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$row,  $result['Maquina_id'])
					->setCellValue('B'.$row,  $result['denomina'])
					->setCellValue('C'.$row,  $result['nota'])
					->setCellValue('D'.$row,  $result['status'])
					->setCellValue('E'.$row,  date('d-m-Y', strtotime($result['date_added'])));
			$row++;
		}
		foreach(range('A','D') as $columnID) {
    		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($archivo, __FILE__);
		$this->response->setOutput(HTTPS_SERVER.$archivo);	
		
	}		
	public function upload_xlsx() {
		$json=array();
		if ( isset( $_FILES[ 'file' ][ 'name' ] ) ) {
			$code = sha1(uniqid(mt_rand(), true)).".xlsx";
			if (is_file(DIR_UPLOAD . $code)) {
				unlink(DIR_UPLOAD . $code);
			}
			move_uploaded_file( $_FILES[ "file" ][ "tmp_name" ], DIR_UPLOAD . $code );
			$json[]=array("Archivo" => DIR_UPLOAD . $code);
			
			$json=$this->leer_archivo(DIR_UPLOAD . $code,0,1);
			
			$this->session->data['success'] = "Se Editaron:".$json['edit']."/ Se Crearon:".$json['new']." con exito!";
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	public function leer_archivo( $archivo,$hoja = 0,$linea = 1 ) {
		ini_set('max_execution_time', 36000);
		ini_set('memory_limit', '12G');
		require_once 'dirsis/phpexcel/Classes/PHPExcel.php';
		$inputFileName = $archivo;
		$reader = PHPExcel_IOFactory::createReaderForFile($inputFileName);
		$reader->setReadDataOnly(true);
		$excel = $reader->load($inputFileName);
		$sheet = $excel->getActiveSheet();
		$data = $sheet->toArray();
		$this->load->model('produccion/Maquina');
		$edit=$new=$linea=0;
		foreach ($data as $in_ar){
			if (!empty($in_ar[1]) and $linea>0){
				$dato=array(
					"denomina" => $in_ar[1],
					"nota" => $in_ar[2],
					"code" => $in_ar[3],
					"status" => $in_ar[4],
					"date_added" => date("Y-m-d",strtotime($in_ar[3]))
				);
				if ((int)$in_ar[0]>0){
					//EDITAR
					$this->model_produccion_Maquina->editMaquina($in_ar[0],$dato);
					$edit++;
				}else{
					//NUEVO
					$this->model_produccion_Maquina->addMaquina($dato);			
					$new++;
				}
			}
			$linea++;
		}
		return array("archivo" => $archivo, "edit" => $edit,"new" => $new);
	}	
		
}