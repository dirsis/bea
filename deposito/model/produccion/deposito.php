<?php
class ModelProduccionDeposito extends Model {
	
	public function bajaDeposito() {
		$sql="DROP TABLE p" . DB_PREFIX . "deposito ";
		$this->db->query($sql);
	}
	public function creaDeposito() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "deposito (
  			deposito_id int(11) NOT NULL  AUTO_INCREMENT,

			
			descrip varchar(200) DEFAULT '',
			
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (deposito_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeDeposito() {
		$this->bajaDeposito();
		$this->creaDeposito();
	}	
	
	public function addDeposito($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "deposito 
		SET 
			descrip = '" . $this->db->escape($data['descrip'])."',
			cantidad = '" . $this->db->escape($data['cantidad'])."',
			fecha = '" . date("Y-m-d",strtotime($this->db->escape($data['fecha'])))."',

			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$deposito_id = $this->db->getLastId();
		return $deposito_id;
	}
	
	public function editDeposito($deposito_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "deposito 
		SET 
			descrip = '" . $this->db->escape($data['descrip'])."',
			cantidad = '" . $this->db->escape($data['cantidad'])."',
			fecha = '" . date("Y-m-d",strtotime($this->db->escape($data['fecha'])))."',

			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE deposito_id = '" . (int)$deposito_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteDeposito($id) {
		$sql="UPDATE p" . DB_PREFIX . "deposito 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE deposito_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyDeposito($deposito_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "deposito WHERE deposito_id = '" . (int)$deposito_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addDeposito($data);
		}
	}

	public function getDeposito($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "deposito 
		WHERE deposito_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getDepositos($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "deposito ";
		$implode = array();
		if (!empty($data['filter_deposito_id'])) {
			$implode[] = " deposito_id='" . $this->db->escape($data['filter_deposito_id']) . "' ";
		}		
//BEA
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
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
			'deposito_id',

			'descrip',
			'cantidad',
			'fecha',

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
	public function getTotalDepositos($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "deposito ";
		$implode = array();
		if (!empty($data['filter_deposito_id'])) {
			$implode[] = " deposito_id='" . $this->db->escape($data['filter_deposito_id']) . "' ";
		}		
//BEA
		if (!empty($data['filter_descrip'])) {
			$implode[] = " descrip LIKE '%" . $this->db->escape($data['filter_descrip']) . "%' ";
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