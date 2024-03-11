<?php
class ModelProduccionproveedor extends Model {
	
	public function bajaproveedor() {
		$sql="DROP TABLE p" . DB_PREFIX . "proveedor ";
		$this->db->query($sql);
	}
	public function creaproveedor() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "proveedor (
  			proveedor_id int(11) NOT NULL  AUTO_INCREMENT,

			  razonsocial varchar(200) default '',
			  domicilio varchar(100) default 'Cordoba'	
			  email varchar(140) default '',	
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (proveedor_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeproveedor() {
		$this->bajaproveedor();
		$this->creaproveedor();
	}	
	
	public function addproveedor($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "proveedor 
		SET razonsocial = '" . $this->db->escape($data['razonsocial'])."',
		domicilio = '" . $this->db->escape($data['domicilio'])."',
			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$proveedor_id = $this->db->getLastId();
		return $proveedor_id;
	}
	
	public function editproveedor($proveedor_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "proveedor 
		SET razonsocial = '" . $this->db->escape($data['razonsocial'])."',
			domicilio = '" . $this->db->escape($data['domicilio'])."',
			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE proveedor_id = '" . (int)$proveedor_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteproveedor($id) {
		$sql="UPDATE p" . DB_PREFIX . "proveedor 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE proveedor_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyproveedor($proveedor_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "proveedor WHERE proveedor_id = '" . (int)$proveedor_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addproveedor($data);
		}
	}

	public function getproveedor($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "proveedor 
		WHERE proveedor_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getproveedors($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "proveedor ";
		$implode = array();
		if (!empty($data['filter_proveedor_id'])) {
			$implode[] = " proveedor_id='" . $this->db->escape($data['filter_proveedor_id']) . "' ";
		}		
		if (!empty($data['filter_razonsocial'])) {
			$implode[] = " razonsocial LIKE '%" . $this->db->escape($data['filter_razonsocial']) . "%' ";
		}		
		if (!empty($data['filter_status'])) {
			$implode[] = " status='" . $this->db->escape($data['filter_status']) . "' ";
		}		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'proveedor_id',
			'razonsocial',
			'date_added',
			'code'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY razonsocial";
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
	public function getTotalproveedors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "proveedor ";
		$implode = array();
		if (!empty($data['filter_proveedor_id'])) {
			$implode[] = " proveedor_id='" . $this->db->escape($data['filter_proveedor_id']) . "' ";
		}		
		if (!empty($data['filter_razonsocial'])) {
			$implode[] = " razonsocial LIKE '%" . $this->db->escape($data['filter_razonsocial']) . "%' ";
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