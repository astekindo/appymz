<?php

class Menu_models extends CI_Model {
    
	public $menunm = '';
	
    public function tmenu_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "WITH RECURSIVE rqName (
						 nama_menu,ID_menu, id_parent, level, controller, sequence,bview,binsert,bupdate,bdelete,deskripsi,aktif, arrHierarchy)
					 AS ( SELECT 
						 nama_menu, ID_menu, id_parent,1,controller,sequence,bview,binsert,bupdate,bdelete,deskripsi,aktif,
						 ARRAY[coalesce(id_parent,0)]
					 FROM
						 mst.tm_menu
					 WHERE
						 id_parent = 0
					 UNION ALL
					   SELECT          
						 tn.nama_menu,
						 tn.id_menu,
						 tn.id_parent,
						 tp.LEVEL + 1,tn.controller,tn.sequence,tn.bview,tn.binsert,tn.bupdate,tn.bdelete,tn.deskripsi,tn.aktif,
						 arrHierarchy || tn.id_menu
					 FROM
						 rqName tp, mst.tm_menu tn
					 WHERE
						 tp.id_menu = tn.id_parent
					 )
					 SELECT id_menu, id_parent,controller, sequence,
							CASE WHEN bview='1' THEN 'checked' ELSE 'false' END bview, 
							CASE WHEN binsert='1' THEN 'checked' ELSE 'false' END binsert, 
							CASE WHEN bupdate='1' THEN 'checked' ELSE 'false' END bupdate, 
							CASE WHEN bdelete='1' THEN 'checked' ELSE 'false' END bdelete, 
							deskripsi,aktif,
					 concat(CASE 
						 WHEN level = 1 THEN ''
						 WHEN level = 2 THEN '...' 
						 WHEN level = 3 THEN '......' 
						 WHEN level = 4 THEN '.........' 
						END,nama_menu) as nama_menu
					   FROM rqName
					   where aktif = true
					  ORDER BY arrHierarchy";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;

                $this->menunm.='<tr class="gradeX">';
                $this->menunm.='<td align="center">' . $no . '</td><td align="center">' . $row->id_menu . '</td><td>' . $row->id_parent . '</td><td>' . $row->nama_menu . '</td>
									<td>' . $row->controller . '</td><td>' . $row->deskripsi . '</td><td>' . $row->sequence . '</td>
									<td><div class="grid9 on_off"><input type="checkbox" id="bview" name="bview" ' . $row->bview . ' disabled/></div></td>
									<td><div class="grid9 on_off"><input type="checkbox" id="binsert" name="binsert" ' . $row->binsert . ' disabled/></div></td>
									<td><div class="grid9 on_off"><input type="checkbox" id="bupdate" name="bupdate" ' . $row->bupdate . ' disabled/></div></td>
									<td><div class="grid9 on_off"><input type="checkbox" id="bdelete" name="bdelete" ' . $row->bdelete . ' disabled/></div></td>
									<td align="center"><a href="menu/form/'. $row->id_menu .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_menu.',\''.base_url().'menu/delete\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->menunm.='</tr>';
            }
        } else {
            $this->menunm = '';
        }
        return $this->menunm;
	}

    public function __construct() {
        $this->load->database();
        parent::__construct();
        $this->load->model('fungsi');
    }

    public $menus = '';

/*    public function getData($id) {
        $sql = "SELECT *
				FROM mst.tm_menu
				WHERE id=" . $id;
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $result['vId'] = $row->id;
            $result['vTitle'] = $row->title;
            $result['vParent'] = $row->parent;
            $result['vIndex'] = $row->sequence;
            $result['vController'] = $row->controller;
            $result['vDescription'] = $row->description;
        }

        return $result;
    }
*/
	function getData($id_menu)
	{
		$sql1 = "SELECT sequence, nama_menu, deskripsi, id_parent, controller, logtime, 
						loguser,
						CASE WHEN bview='1' THEN 'checked' ELSE 'false' END bview, 
						CASE WHEN binsert='1' THEN 'checked' ELSE 'false' END binsert, 
						CASE WHEN bupdate='1' THEN 'checked' ELSE 'false' END bupdate, 
						CASE WHEN bdelete='1' THEN 'checked' ELSE 'false' END bdelete, 
						aktif, id_menu
				FROM mst.tm_menu
				where aktif=true and id_menu=" . $id_menu;
		$query = $this->db->query($sql1);
		return $query->result_array();
	}

    public function menu_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT *
			FROM mst.tm_menu
			WHERE id_parent=1
			ORDER BY sequence";

            $query = $this->db->query($sql1);
            foreach ($query->result() as $row) {
				$this->menus.='<li>';
                $this->menus.='<a id="drop' . $row->sequence . '" href="#" >' . $row->nama_menu . '</a>';
				
				
                $sql2 = "SELECT *
				FROM mst.tm_menu
				WHERE id_parent='" . $row->id_menu . "' 
				ORDER BY sequence";

                $q = $this->db->query($sql2);

				if ($q -> num_rows() > 0){
				$this->menus.='<ul role="menu" aria-labelledby="drop' . $row->sequence . '">';

                foreach ($q->result() as $r) {
				
					$cntr = base_url() . $r->controller;
					
					$this->menus.='<li><a tabindex="-1" href="' . $cntr . '">' . $r->nama_menu . '</a>';
                    
					$sql3 = "SELECT *
					FROM mst.tm_menu
					WHERE id_parent='" . $r->id_menu . "' 
					ORDER BY sequence";
					
					$y = $this->db->query($sql3);
					if($y->num_rows() > 0){
						$this->menus.='<ul role="menu" aria-labelledby="drop' . $r->sequence . '">';
						foreach ($y->result() as $r1) {
							$cntr1 = base_url() . $r1->controller;
							$this->menus.='<li><a tabindex="-1" href="' . $cntr1 . '">' . $r1->nama_menu . '</a></li>';
						}
					$this->menus.='</li>';
					$this->menus.='</ul>';
					}else
					{$this->menus.='</li>';}
				}
                $this->menus.='</li></ul>';
				}else
				{$this->menus.='</li>';}
            }
		} else {
            $this->menus = '';
        }
        return $this->menus;
    }

    public function get_parents() {
        $sql = "WITH RECURSIVE rqName (
					 nama_menu,ID_menu, id_parent, level, aktif, arrHierarchy)
				 AS ( SELECT 
					 nama_menu, ID_menu, id_parent,1,aktif,
					 ARRAY[coalesce(id_parent,0)]
				 FROM
					 mst.tm_menu
				 WHERE
					 id_parent = 0
				 UNION ALL
				   SELECT
					 tn.nama_menu,
					 tn.id_menu,
					 tn.id_parent,
					 tp.LEVEL + 1,tn.aktif,
					 arrHierarchy || tn.id_menu
				 FROM
					 rqName tp, mst.tm_menu tn
				 WHERE
					 tp.id_menu = tn.id_parent
				 )
				 SELECT id_menu,
				 concat(CASE 
					 WHEN level = 1 THEN ''
					 WHEN level = 2 THEN '...' 
					 WHEN level = 3 THEN '......' 
					 WHEN level = 4 THEN '.........' 
					END,nama_menu) as nama_menu
				   FROM rqName
				   where aktif = true
				  ORDER BY arrHierarchy";
        $q = $this->db->query($sql);
		if($q->num_rows() > 0)
		{
			return $q->result();
		}
		else
		{
			return FALSE;
		}
    }
/*
    public function addData($data) {
        $idmax = $this->fungsi->maxID("mst.tm_menu");

        $sqlu = "INSERT INTO mst.tm_menu (id, sequence, title, description, parent, controller)
			VALUES(" . $idmax . ", '" . $data['dIndex'] . "', '" . $data['dTitle'] . "', '" . $data['dDescription'] . "'
					, '" . $data['dParent'] . "', '" . $data['dController'] . "')";
        #exit($sqlu);
        $query = $this->db->query($sqlu);
    }

    public function editData($data) {
        $sqlu = "UPDATE mst.tm_menu 
				SET sequence = " . $data['dIndex'] . ", 
					title = '" . $data['dTitle'] . "', 
					description = '" . $data['dDescription'] . "', 
					parent = " . $data['dParent'] . ", 
					controller = '" . $data['dController'] . "'
				WHERE id = " . $data['dId'];
        $query = $this->db->query($sqlu);
    }

    public function deleteData($id) {
        $sqld = "DELETE FROM mst.tm_menu 
				WHERE id = " . $id;
        $query = $this->db->query($sqld);
    }
*/
	function add_record($data) 
	{
		$this->db->insert('mst.tm_menu', $data);
		return;
	}
	
	function update_record($data,$id_menu) 
	{
		$this->db->where('id_menu',$id_menu);
		$this->db->update('mst.tm_menu', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_menu', $this->uri->segment(3));
		$this->db->delete('mst.tm_menu');
	}

	
}

?>
