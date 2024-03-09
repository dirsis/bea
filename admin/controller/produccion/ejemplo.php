<?php
class ControllerProduccionEjemplo extends Controller {
	private $error = array();

	public function index() {
		//VISUALIZAR ERRORES
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		//
		$this->load->language('produccion/ejemplo');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/ejemplo');
		//CREA LA TABLA SI NO EXISTE
		//$this->model_produccion_ejemplo->bajaEjemplo();
		$this->model_produccion_ejemplo->creaEjemplo();
		
		//			
		$this->getList();
	}
	public function sincro(){
		$this->load->language('produccion/ejemplo');
		$this->load->model('produccion/ejemplo');
		$this->model_produccion_ejemplo->traeEjemplo();
		$this->session->data['success'] = $this->language->get('text_success');
		$url = $this->filtrar($this->request->get,"gral");
		$this->response->redirect($this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true));
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
		if (isset($get['filter_ejemplo_id'])) {
			$url .= '&filter_ejemplo_id=' . $get['filter_ejemplo_id'];
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
		$this->load->language('produccion/ejemplo');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/ejemplo');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			

			
			$this->model_produccion_ejemplo->addEjemplo($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}
	
	public function edit() {
		$this->load->language('produccion/ejemplo');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/ejemplo');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			/*
			echo "<pre>";
			print_r($this->request->post);
			echo "</pre>";
			die;
			*/
			$this->model_produccion_ejemplo->editEjemplo($this->request->get['ejemplo_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get);
			$this->response->redirect($this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('produccion/ejemplo');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/ejemplo');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ejemplo_id) {
				$this->model_produccion_ejemplo->deleteEjemplo($ejemplo_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		$this->getList();
	}
	
	
	public function copy() {
		$this->load->language('produccion/ejemplo');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('produccion/ejemplo');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $ejemplo_id) {
				$this->model_produccion_ejemplo->copyEjemplo($ejemplo_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->filtrar($this->request->get,"gral");
			$this->response->redirect($this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}
	
	protected function getList() {

		if (isset($this->request->get['filter_ejemplo_id'])) {
			$filter_ejemplo_id = $this->request->get['filter_ejemplo_id'];
		} else {
			$filter_ejemplo_id = '';
		}
		if (isset($this->request->get['filter_descrip'])) {
			$filter_descrip = $this->request->get['filter_descrip'];
		} else {
			$filter_descrip = '';
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
			'href' => $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('produccion/ejemplo/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['delete'] = $this->url->link('produccion/ejemplo/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['copy'] = $this->url->link('produccion/ejemplo/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['ejemplos'] = array();
		$filter_data = array(
			'filter_ejemplo_id'         => $filter_ejemplo_id,
			'filter_descrip'   			=> $filter_descrip,
			'filter_status'            => $filter_status,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
 
		$ejemplo_total = $this->model_produccion_ejemplo->getTotalEjemplos($filter_data);

		$this->load->model('user/user');
		$results = $this->model_produccion_ejemplo->getEjemplos($filter_data);

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

			$data['ejemplos'][] = array(
				'ejemplo_id'    	=> $result['ejemplo_id'],
				'descrip'    		=> $result['descrip'],
				'code'    			=> $result['code'],
				'number'    			=> $result['number'],
				'date_added'        => $result['date_added']?date('d-m-Y', strtotime($result['date_added'])):"",
				'status'         	=> ($result['status']=='1' ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_modified'     => $result['date_modified']==""?"":date('d-m-Y', strtotime($result['date_modified'])),
				'date_delete'     	=> $result['date_delete']==""?"":date('d-m-Y', strtotime($result['date_delete'])),
				'user_id_added'		=> $user_id_added,
				'user_id_modified'	=> $user_id_modified,
				'user_id_delete'	=> $user_id_delete,
				'edit'           	=> $this->url->link('produccion/ejemplo/edit', 'user_token=' . $this->session->data['user_token'] . '&ejemplo_id=' . $result['ejemplo_id'] . $url, true),
				'clonar'           	=> $this->url->link('produccion/ejemplo/clonar', 'user_token=' . $this->session->data['user_token'] . '&ejemplo_id=' . $result['ejemplo_id'] . $url, true)				
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
		$data['sort_ejemplo_id'] = $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . '&sort=ejemplo_id' . $url, true);
		
		$data['sort_descrip'] = $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . '&sort=descrip' . $url, true);
		
		$data['sort_code'] = $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url, true);		
		
		$url = $this->filtrar($this->request->get,"nopage");
		
		$pagination = new Pagination();
		$pagination->total = $ejemplo_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ejemplo_total) ? (($page - 1) *  $limit) + 1 : 0, ((($page - 1) *  $limit) > ($ejemplo_total -  $limit)) ? $ejemplo_total : ((($page - 1) *  $limit) +  $limit), $ejemplo_total, ceil($ejemplo_total /  $limit));

		$data['filter_ejemplo_id'] = $filter_ejemplo_id;
		$data['filter_descrip'] = $filter_descrip;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('produccion/ejemplo_list', $data));
	}

	protected function getForm() {
		
				//VISUALIZAR ERRORES
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		
		$data['text_form'] = !isset($this->request->get['ejemplo_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['ejemplo_id'])) {
			$data['action'] = $this->url->link('produccion/ejemplo/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('produccion/ejemplo/edit', 'user_token=' . $this->session->data['user_token'] . '&ejemplo_id=' . $this->request->get['ejemplo_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('produccion/ejemplo', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['ejemplo_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$info = $this->model_produccion_ejemplo->getEjemplo($this->request->get['ejemplo_id']);
		}

		if (isset($this->request->get['ejemplo_id'])) {
			$data['ejemplo_id'] = $this->request->get['ejemplo_id'];
		} else {
			$data['ejemplo_id'] = 0;
		}

		if (isset($this->request->post['descrip'])) {
			$data['descrip'] = $this->request->post['descrip'];
		} elseif (!empty($info)) {
			$data['descrip'] = $info['descrip'];
		} else {
			$data['descrip'] = '';
		}		
		
		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($info)) {
			$data['code'] = $info['code'];
		} else {
			$data['code'] = '';
		}		

		if (isset($this->request->post['number'])) {
			$data['number'] = $this->request->post['number'];
		} elseif (!empty($info)) {
			$data['number'] = $info['number'];
		} else {
			$data['number'] = '';
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
		$this->response->setOutput($this->load->view('produccion/ejemplo_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'produccion/ejemplo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['descrip']) < 1) || (utf8_strlen(trim($this->request->post['descrip'])) > 200)) {
			$this->error['descrip'] = $this->language->get('error_descrip');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'produccion/ejemplo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'produccion/ejemplo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	
		
	public function download_xlsx() {
	
		if (isset($this->request->get['filter_ejemplo_id'])) {
			$filter_ejemplo_id = $this->request->get['filter_ejemplo_id'];
		} else {
			$filter_ejemplo_id = '';
		}
		if (isset($this->request->get['filter_descrip'])) {
			$filter_descrip = $this->request->get['filter_descrip'];
		} else {
			$filter_descrip = '';
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
		$data['ejemplos'] = array();
		$filter_data = array(
			'filter_ejemplo_id'        => $filter_ejemplo_id,
			'filter_descrip'           => $filter_descrip,
			'filter_nota'           => $filter_nota,
			'filter_status'            => $filter_status,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);
		$this->load->model('produccion/ejemplo');
		$results = $this->model_produccion_ejemplo->getEjemplos($filter_data);
	
	
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
					->setEjemplo("reportes");
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
					->setCellValue('A'.$row,  $result['ejemplo_id'])
					->setCellValue('B'.$row,  $result['descrip'])
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
		$this->load->model('produccion/ejemplo');
		$edit=$new=$linea=0;
		foreach ($data as $in_ar){
			if (!empty($in_ar[1]) and $linea>0){
				$dato=array(
					"descrip" => $in_ar[1],
					"nota" => $in_ar[2],
					"code" => $in_ar[3],
					"status" => $in_ar[4],
					"date_added" => date("Y-m-d",strtotime($in_ar[3]))
				);
				if ((int)$in_ar[0]>0){
					//EDITAR
					$this->model_produccion_ejemplo->editEjemplo($in_ar[0],$dato);
					$edit++;
				}else{
					//NUEVO
					$this->model_produccion_ejemplo->addEjemplo($dato);			
					$new++;
				}
			}
			$linea++;
		}
		return array("archivo" => $archivo, "edit" => $edit,"new" => $new);
	}	
		
}