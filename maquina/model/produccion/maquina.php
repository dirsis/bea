<?php
class ModelProduccionmaquina extends Model {
	
	public function bajamaquina() {
		$sql="DROP TABLE p" . DB_PREFIX . "maquina ";
		$this->db->query($sql);
	}
	public function creamaquina() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "maquina (
  			maquina_id int(11) NOT NULL  AUTO_INCREMENT,

			denomina varchar(200) DEFAULT NULL,
			modelo varchar(20) DEFAULT NULL	
			indice  int(11) NOT NULL DEFAULT 1,		
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (maquina_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traemaquina() {
		$this->bajamaquina();
		$this->creamaquina();
	}	
	
	public function addmaquina($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "maquina 
		SET denomina = '" . $this->db->escape($data['denomina'])."',
		modelo = '" . $this->db->escape($data['modelo'])."',
			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$maquina_id = $this->db->getLastId();
		return $maquina_id;
	}
	
	public function editmaquina($maquina_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "maquina 
		SET denomina = '" . $this->db->escape($data['denomina'])."',
			modelo = '" . $this->db->escape($data['modelo'])."',
			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE maquina_id = '" . (int)$maquina_id . "'";
		$this->db->query($sql);
	}
	
	public function deletemaquina($id) {
		$sql="UPDATE p" . DB_PREFIX . "maquina 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE maquina_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copymaquina($maquina_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "maquina WHERE maquina_id = '" . (int)$maquina_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addmaquina($data);
		}
	}

	public function getmaquina($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "maquina 
		WHERE maquina_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getmaquinas($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "maquina ";
		$implode = array();
		if (!empty($data['filter_maquina_id'])) {
			$implode[] = " maquina_id='" . $this->db->escape($data['filter_maquina_id']) . "' ";
		}		
		if (!empty($data['filter_denomina'])) {
			$implode[] = " denomina LIKE '%" . $this->db->escape($data['filter_denomina']) . "%' ";
		}		
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'maquina_id',
			'denomina',
			'date_added',
			'code'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY denomina";
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
	public function getTotalmaquinas($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "maquina ";
		$implode = array();
		if (!empty($data['filter_maquina_id'])) {
			$implode[] = " maquina_id='" . $this->db->escape($data['filter_maquina_id']) . "' ";
		}		
		if (!empty($data['filter_denomina'])) {
			$implode[] = " denomina LIKE '%" . $this->db->escape($data['filter_denomina']) . "%' ";
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