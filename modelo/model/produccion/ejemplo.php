<?php
class ModelProduccionEjemplo extends Model {
	
	public function bajaEjemplo() {
		$sql="DROP TABLE p" . DB_PREFIX . "ejemplo ";
		$this->db->query($sql);
	}
	public function creaEjemplo() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "ejemplo (
  			ejemplo_id int(11) NOT NULL  AUTO_INCREMENT,

			
			detalle varchar(200) DEFAULT '',
			cantidad int(11) NOT NULL DEFAULT 1,
			fecha  datetime,

						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (ejemplo_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeEjemplo() {
		$this->bajaEjemplo();
		$this->creaEjemplo();
	}	
	
	public function addEjemplo($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "ejemplo 
		SET 
			detalle = '" . $this->db->escape($data['detalle'])."',
			cantidad = '" . $this->db->escape($data['cantidad'])."',
			fecha = '" . date("Y-m-d",strtotime($this->db->escape($data['fecha'])))."',

			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$ejemplo_id = $this->db->getLastId();
		return $ejemplo_id;
	}
	
	public function editEjemplo($ejemplo_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "ejemplo 
		SET 
			detalle = '" . $this->db->escape($data['detalle'])."',
			cantidad = '" . $this->db->escape($data['cantidad'])."',
			fecha = '" . date("Y-m-d",strtotime($this->db->escape($data['fecha'])))."',

			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE ejemplo_id = '" . (int)$ejemplo_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteEjemplo($id) {
		$sql="UPDATE p" . DB_PREFIX . "ejemplo 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE ejemplo_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyEjemplo($ejemplo_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "ejemplo WHERE ejemplo_id = '" . (int)$ejemplo_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addEjemplo($data);
		}
	}

	public function getEjemplo($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "ejemplo 
		WHERE ejemplo_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getEjemplos($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "ejemplo ";
		$implode = array();
		if (!empty($data['filter_ejemplo_id'])) {
			$implode[] = " ejemplo_id='" . $this->db->escape($data['filter_ejemplo_id']) . "' ";
		}		
//BEA
		if (!empty($data['filter_detalle'])) {
			$implode[] = " detalle LIKE '%" . $this->db->escape($data['filter_detalle']) . "%' ";
		}		
		if (!empty($data['filter_cantidad'])) {
			$implode[] = " cantidad='" . $this->db->escape($data['filter_cantidad']) . "' ";
		}		
		if (!empty($data['filter_fecha'])) {
			$implode[] = " fecha='" . $this->db->escape($data['filter_fecha']) . "' ";
		}		
//FIN BEA
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'ejemplo_id',

			'detalle',
			'cantidad',
			'fecha',

			'date_added',
			'code'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY detalle";
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
	public function getTotalEjemplos($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "ejemplo ";
		$implode = array();
		if (!empty($data['filter_ejemplo_id'])) {
			$implode[] = " ejemplo_id='" . $this->db->escape($data['filter_ejemplo_id']) . "' ";
		}		
//BEA
		if (!empty($data['filter_detalle'])) {
			$implode[] = " detalle LIKE '%" . $this->db->escape($data['filter_detalle']) . "%' ";
		}		
		if (!empty($data['filter_cantidad'])) {
			$implode[] = " cantidad='" . $this->db->escape($data['filter_cantidad']) . "' ";
		}		
		if (!empty($data['filter_fecha'])) {
			$implode[] = " fecha='" . $this->db->escape($data['filter_fecha']) . "' ";
		}		
//FIN BEA
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