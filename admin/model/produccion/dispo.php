<?php
class ModelProduccionDispo extends Model {
	
	public function bajaDispo() {
		$sql="DROP TABLE p" . DB_PREFIX . "dispo ";
		$this->db->query($sql);
	}
	public function creaDispo() {
		$sql="CREATE TABLE IF NOT EXISTS p" . DB_PREFIX . "dispo (
  			dispo_id int(11) NOT NULL  AUTO_INCREMENT,

			descrip varchar(200) DEFAULT NULL,
			number(20) DEFAULT NULL			
						
			status int(11) NOT NULL DEFAULT 1,
			date_added timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			date_modified datetime,
			date_delete datetime,
			user_id_added 		int(11) NOT NULL,
			user_id_modified 	int(11) default 0,
			user_id_delete 		int(11) default 0,
			autogestion_id 		int(11) default 0,
			PRIMARY KEY (dispo_id ) ) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$this->db->query($sql);
	}
	public function traeDispo() {
		$this->bajaDispo();
		$this->creaDispo();
	}	
	
	public function addDispo($data) {
		
		$sql="INSERT INTO p" . DB_PREFIX . "dispo 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
		number = '" . $this->db->escape($data['number'])."',
			status = '" . $this->db->escape($data['status'])."',
			date_added	= now() ,
			user_id_added = '" . $this->user->getId() . "' ";
		$this->db->query($sql);
		$dispo_id = $this->db->getLastId();
		return $dispo_id;
	}
	
	public function editDispo($dispo_id, $data) {
		$sql="UPDATE p" . DB_PREFIX . "dispo 
		SET descrip = '" . $this->db->escape($data['descrip'])."',
			number = '" . $this->db->escape($data['number'])."',
			status = '" . $this->db->escape($data['status'])."',
			user_id_modified = '" . $this->user->getId() . "',
			date_modified 	= now() 
		WHERE dispo_id = '" . (int)$dispo_id . "'";
		$this->db->query($sql);
	}
	
	public function deleteDispo($id) {
		$sql="UPDATE p" . DB_PREFIX . "dispo 
		SET status = 0,
		user_id_delete = '" . $this->user->getId() . "',
		date_delete	= now() 
		WHERE dispo_id = '" . (int)$id . "'";
		$this->db->query($sql);
	}
	
	
	public function copyDispo($dispo_id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "dispo WHERE dispo_id = '" . (int)$dispo_id . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$data = $query->row;
			$data['status'] = '1';
			$this->addDispo($data);
		}
	}

	public function getDispo($id) {
		$sql="SELECT DISTINCT * FROM p" . DB_PREFIX . "dispo 
		WHERE dispo_id = '" . (int)$id . "'; ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getDispos($data = array()) {
		$sql = "SELECT * FROM p" . DB_PREFIX . "dispo ";
		$implode = array();
		if (!empty($data['filter_dispo_id'])) {
			$implode[] = " dispo_id='" . $this->db->escape($data['filter_dispo_id']) . "' ";
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
			'dispo_id',
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
	public function getTotalDispos($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM p" . DB_PREFIX . "dispo ";
		$implode = array();
		if (!empty($data['filter_dispo_id'])) {
			$implode[] = " dispo_id='" . $this->db->escape($data['filter_dispo_id']) . "' ";
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