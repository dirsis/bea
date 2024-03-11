<?php
class ModelProduccionProveedor extends Model {
	
	public function bajaProveedor() {
		$sql="DROP TABLE p" . DB_PREFIX . "Proveedor ";
		$this->db->query($sql);
	}
	public function creaProveedor() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "Proveedor (
  			Proveedor_id int(11) NOT NULL  AUTO_INCREMENT,

			razonsocial varchar(200) default '',
			domicilio varchar(100) default 'Cordoba',
			email varchar (140) default '',			
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (Proveedor_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeProveedor() {
		$this->bajaProveedor();
		$this->creaProveedor();
	}	
	
	public function addProveedor($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "Proveedor 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
		number = '" . $this->db->escape($data['number'])."',
			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$Proveedor_id = $this->db->getLastId();
		return $Proveedor_id;
	}
	
	public function editProveedor($Proveedor_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "Proveedor 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
			number = '" . $this->db->escape($data['number'])."',
			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE Proveedor_id = '" . (int)$Proveedor_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteProveedor($id) {
		$sql="UPDATE p" . DB_PREFIX . "Proveedor 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE Proveedor_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyProveedor($Proveedor_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "Proveedor WHERE Proveedor_id = '" . (int)$Proveedor_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addProveedor($data);
		}
	}

	public function getProveedor($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "Proveedor 
		WHERE Proveedor_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getProveedors($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "Proveedor ";
		$implode = array();
		if (!empty($data['filter_Proveedor_id'])) {
			$implode[] = " Proveedor_id='" . $this->db->escape($data['filter_Proveedor_id']) . "' ";
		}		
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
		}		
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'Proveedor_id',
			'descrip',
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
	public function getTotalProveedors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "Proveedor ";
		$implode = array();
		if (!empty($data['filter_Proveedor_id'])) {
			$implode[] = " Proveedor_id='" . $this->db->escape($data['filter_Proveedor_id']) . "' ";
		}		
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
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