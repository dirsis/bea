<?php
class ModelProduccionTipo extends Model {
	
	public function bajaTipo() {
		$sql="DROP TABLE p" . DB_PREFIX . "tipo ";
		$this->db->query($sql);
	}
	public function creaTipo() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "tipo (
  			tipo_id int(11) NOT NULL  AUTO_INCREMENT,

			descrip varchar(200) DEFAULT NULL,
			nota varchar(255) DEFAULT NULL,
			code int(11) NOT NULL DEFAULT 1,
			color varchar(20) DEFAULT NULL			
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (tipo_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeTipo() {
		$this->bajaTipo();
		$this->creaTipo();
	}	
	
	public function addTipo($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "tipo 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
			nota = '" . $this->db->escape($data['nota'])."',
			code = '" . $this->db->escape($data['code'])."',
			color = '" . $this->db->escape($data['color'])."',
			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$tipo_id = $this->db->getLastId();
		return $tipo_id;
	}
	
	public function editTipo($tipo_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "tipo 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
			nota = '" . $this->db->escape($data['nota'])."',
			code = '" . $this->db->escape($data['code'])."',
			color = '" . $this->db->escape($data['color'])."',
			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE tipo_id = '" . (int)$tipo_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteTipo($id) {
		$sql="UPDATE p" . DB_PREFIX . "tipo 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE tipo_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyTipo($tipo_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "tipo WHERE tipo_id = '" . (int)$tipo_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addTipo($data);
		}
	}

	public function getTipo($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "tipo 
		WHERE tipo_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTipos($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "tipo ";
		$implode = array();
		if (!empty($data['filter_tipo_id'])) {
			$implode[] = " tipo_id='" . $this->db->escape($data['filter_tipo_id']) . "' ";
		}		
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
		}		
		if (!empty($data['filter_nota'])) {
			$implode[] = " nota LIKE '%" . $this->db->escape($data['filter_nota']) . "%' ";
		}		
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'tipo_id',
			'descrip',
			'nota',
			'date_added',
			'code'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY descrip";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
 
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getTotalTipos($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "tipo ";
		$implode = array();
		if (!empty($data['filter_tipo_id'])) {
			$implode[] = " tipo_id='" . $this->db->escape($data['filter_tipo_id']) . "' ";
		}		
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
		}	
		if (!empty($data['filter_nota'])) {
			$implode[] = " nota LIKE '%" . $this->db->escape($data['filter_nota']) . "%' ";
		}				
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}  
	
}