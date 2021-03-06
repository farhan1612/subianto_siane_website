<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelproject extends CI_Model {

	public function loadAllProjectCategory()
	{
		$query = $this->db->query("
				SELECT pc.*
				FROM project_category pc
				ORDER BY pc.id ASC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function insertProject($title, $description, $category, $projectstory, $date, $client, $status, $location, $projecturi)
	{
		$field = array(
				'title' => $title,
				'description' => $description,
				'project_story' => $projectstory,
				'id_category' => $category,
				'project_detail_date' => $date,
				'project_detail_client' => $client,
				'project_detail_status' => $status,
				'project_detail_location' => $location,
				'project_uri' => $projecturi
			);
		$this->db->insert('project', $field);
	}

	public function uploadProjectPhoto($idproject, $file)
	{
		$field = array(
				'photo' => $file,
				'id_project' => $idproject
			);
		$this->db->insert('project_album', $field);
	}

	public function loadAllProject()
	{
		$query = $this->db->query("
				SELECT p.*, pc.category_name, 
				    (SELECT pa.photo
				        FROM project_album pa 
				         WHERE p.id = pa.id_project AND pa.status_cover_project = 1 limit 0,1) as photo
				FROM project p, project_category pc
				WHERE p.id_category = pc.id
				ORDER BY p.id DESC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function loadAllProjectAlbum($uri3)
	{
		$query = $this->db->query("
				SELECT *
				FROM project_album
				WHERE id_project = '$uri3' ORDER BY id ASC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function loadOneProject($projecturi)
	{
		$query = $this->db->query("
				SELECT p.title, p.description, p.project_story,
                p.project_detail_date, p.project_detail_client, p.project_detail_status, p.project_detail_location,
                (SELECT pa.photo
                    FROM project_album pa
                    WHERE p.id = pa.id_project
                    AND pa.status_sidebar_project = 1) as sidebarphoto,
                (SELECT pa.status_sidebar_project
                    FROM project_album pa
                    WHERE p.id = pa.id_project
                    AND pa.status_sidebar_project = 1) as statussidebar
                FROM project p
                WHERE p.project_uri = '$projecturi' 
			");
		if($query->num_rows()>0){
			$data = $query->row();
			return $data;
		}
	}

	public function loadSidebarProject($projecturi)
	{
		$query = $this->db->query("
				SELECT pa.photo, pa.status_sidebar_project
				FROM project_album pa, project p
				WHERE p.project_uri = '$projecturi'
				AND pa.id_project = p.id
				LIMIT 0,1
			");
		if($query -> num_rows() > 0){
			$data = $query->row();
			return $data;
		}
	}

	public function loadAllPhotosDetailProject($projecturi)
	{
		$query = $this->db->query("
				SELECT pa.photo
				FROM project_album pa, project p
				WHERE p.project_uri = '$projecturi'
				AND pa.id_project = p.id
				ORDER BY pa.id ASC
			");
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		}

	}

	public function insertProjectAlbum($idproject, $id, $cover, $sidebar)
	{
		$this->db->trans_begin();
		$field = array(
				'status_cover_project' => '0',
				'status_sidebar_project' => '0'
			);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);

		$field = array(
				'status_cover_project' => '1'
			);
		$this->db->where('id', $cover);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);

		$field = array(
				'status_sidebar_project' => '1'
			);
		$this->db->where('id', $sidebar);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);

		// complete database transaction
        $this->db->trans_complete();

        // return false if something went wrong
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            return TRUE;
        }
	}

	public function loadAllProjectAbout()
	{
		$query = $this->db->query("
				SELECT *
				FROM project_album
				ORDER BY id ASC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function insertProjectAbout($about)
	{
		$this->db->trans_begin();
		$field = array(
				'status_about' => '0',
			);
		$this->db->update('project_album', $field);

		$field = array(
				'status_about' => '1'
			);
		$this->db->where('id', $about);
		$this->db->update('project_album', $field);

		// complete database transaction
        $this->db->trans_complete();

        // return false if something went wrong
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            return TRUE;
        }
	}

	public function loadAllProjectSidebar()
	{
		$query = $this->db->query("
				SELECT *
				FROM project_album
				ORDER BY id ASC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function insertProjectSidebar($sidebar)
	{
		foreach($sidebar as $sb)
		{
			$field = array(
				'status_sidebar_random' => '1'
			);

		$this->db->where('id', $sb);
		$this->db->update('project_album', $field);
		}
	}

	public function loadAllProjectHome()
	{
		$query = $this->db->query("
				SELECT project_album.*,  project.title as title
				from project_album, project 
				where project.id = project_album.id_project 
				group by project_album.id_project
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function loadAllPhotoHome($id)
	{
		$query = $this->db->query("
				SELECT project_album.*,  project.title as title, project.id as idpro
				from project_album, project 
				where project.id = project_album.id_project AND project_album.id_project = '$id'
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function insertProjectHome($home, $id, $idproject)
	{
		$this->db->trans_begin();
		$field = array(
				'status_feature_home' => '0',
			);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);

		$field = array(
				'status_feature_home' => '1'
			);
		$this->db->where('id', $home);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);

		// complete database transaction
        $this->db->trans_complete();

        // return false if something went wrong
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }else{
            return TRUE;
        }
	}

	public function loadPhotoAbout(){
		$query = $this->db->query("
			SELECT pa.photo, pa.status_about
			FROM project_album pa
			WHERE pa.status_about = '1'
			");
		if($query->num_rows() > 0) {
			$data = $query->row();
			return $data;
		}
	}

	public function loadRandomPhoto(){
		$this->db->order_by('id', 'RANDOM');
    	$this->db->limit(1);
    	$this->db->where('status_sidebar_random', '1');
    	$query = $this->db->get('project_album');
    	if($query->num_rows() > 0){
    		$data = $query->row();
    		return $data;
    	}
	}

	public function loadFeaturedHome(){
		$query = $this->db->query("
			SELECT pa.photo, p.project_uri, p.title
			FROM project_album pa, project p
			WHERE pa.id_project = p.id
			AND pa.status_feature_home='1'
			");
		if($query->num_rows()> 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function resetProjectHome($idproject)
	{
		$field = array(
				'status_feature_home' => '0',
			);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);
	}

	public function resetCoverProject($idproject)
	{
		$field = array(
				'status_cover_project' => '0'
			);
		$this->db->where('id_project', $idproject);
		$this->db->update('project_album', $field);
	}

	public function setCoverProject($idphoto)
	{
		$field = array(
				'status_cover_project' => '1'
			);
		$this->db->where('id', $idphoto);
		$this->db->update('project_album', $field);
	}

	public function loadAllProjectUpdate($id)
	{
		$query = $this->db->query("
				SELECT p.*, pc.category_name, pc.id as idcat,
				    (SELECT pa.photo
				        FROM project_album pa 
				         WHERE p.id = pa.id_project AND pa.status_cover_project = 1 limit 0,1) as photo
				FROM project p, project_category pc
				WHERE p.id_category = pc.id AND p.id = '$id'
				ORDER BY p.id DESC
			");
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function updateProject($ids, $title, $description, $category, $projectstory, $date, $client, $status, $location, $projecturi)
	{
		$field = array(
				'title' => $title,
				'description' => $description,
				'project_story' => $projectstory,
				'id_category' => $category,
				'project_detail_date' => $date,
				'project_detail_client' => $client,
				'project_detail_status' => $status,
				'project_detail_location' => $location,
				'project_uri' => $projecturi
			);
		$this->db->where('id', $ids);
		$this->db->update('project', $field);
	}

	public function loadProject($field, $condition)
	{
		$this->db->where($field, $condition);
		$query = $this->db->get('project');
		if($query->num_rows()>0){
			$data = $query->row();
			return $data;
		}
	}

	public function loadPhotoDetail($id){
		$query = $this->db->query("
				SELECT pa.photo
				FROM project_album pa, project p
				WHERE pa.id_project = '$id'
				AND pa.id_project = p.id
				ORDER BY pa.id ASC
			");
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
	}

	public function deleteProject($id){
		return $this->db->delete('project', array('id'=>$id));
	}

	public function deleteProjectDetail($id){
		return $this->db->delete('project_album', array('id_project' => $id));
	}

	public function loadPhoto($id){
		$query = $this->db->query("
				SELECT *
				FROM project_album 
				WHERE id_project = '$id'
				ORDER BY id ASC
			");
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
	}

	public function loadOnlyOneProject($id)
	{
		$query = $this->db->query("
				SELECT p.*, pc.category_name
				FROM project p, project_category pc
				WHERE p.id = '$id'
				AND p.id_category = pc.id
			");
		if($query->num_rows()>0){
			$data = $query->row();
			return $data;
		}
	}

	public function loadDeletePhoto($photo)
	{
		foreach($photo as $dp)
		{
			$query = $this->db->query("
				SELECT *
				FROM project_album 
				WHERE id = '$dp'
				ORDER BY id ASC
			");
			if($query->num_rows() > 0){
				foreach($query->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
		}
	}	

	public function loadProjectAlbum($id)
	{
		$query = $this->db->query("
				SELECT *
				FROM project_album
				WHERE id = '$id'
			");
		if($query->num_rows() > 0){
			$data = $query->row();
			return $data;
		}
	}
	public function deleteOnePhoto($id){
		return $this->db->delete('project_album', array('id'=>$id));
	}

	public function loadCoverProject($idproject)
	{
		$query = $this->db->query("
				SELECT pa.id, pa.photo, pa.id_project, pa.status_cover_project
				FROM project_album pa
				WHERE pa.id_project = '$idproject'
				AND pa.status_cover_project = '1'
			");
		return $query;
	}

	public function deletePhoto($photo)
	{
		foreach($photo as $dp)
		{
			$this->db->where('id', $dp);
			$this->db->delete('project_album');
		}
	}

	public function get_project_by_title($title){
        $query = $this->db->get_where('project', array('title' => $title));
        return $query->row_array();
    }

    public function loadAddProject()
	{
		$query = $this->db->query("
				SELECT p.*, pc.category_name, 
				    (SELECT pa.photo
				        FROM project_album pa 
				         WHERE p.id = pa.id_project AND pa.status_cover_project = 1 limit 0,1) as photo
				FROM project p, project_category pc
				WHERE p.id_category = pc.id
				ORDER BY p.id DESC
			");
		return $query->row();
	}

	public function checkProjectbyFilter($category)
	{
		$query = $this->db->query("
				SELECT p.id, pc.category_name 
				FROM project p, project_category pc 
				WHERE p.id_category = pc.id 
				AND pc.category_name='$category';
			");
		return $query;
	}

	public function checkProjectDetail($projecturi)
	{
		$query = $this->db->query("
				SELECT p.id, p.title
                FROM project p
                WHERE p.project_uri = '$projecturi'
			");
		return $query;
	}

}

/* End of file modelproject.php */
/* Location: ./application/models/modelproject.php */