<?php

class Muser extends CI_Model {

    function generate_idproduk() {
	$sql = "select * from user";
	$query = $this->db->query($sql);
	$jml_user = $query->num_rows();
	$jml_user = str_pad($jml_user + 1, 3, '0', STR_PAD_LEFT);
	$idproduk = "PR" . $jml_user;
	return $idproduk;
    }

    function save($prm) {
	$data = array(
	    'user_name' => $prm['username'],
	    'nama' => $prm['nama'],
	    'password' => $prm['password']);
	if ($this->db->insert('user', $data)) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }
    function list_user() {
	$this->db->order_by("`id` DESC ");
	return $this->db->get("user");
//	$query = $this->db->query("select * from user");
//	return $query;
    }
    
    function  delete_user($id){
	if($this->db->delete('user', array('id' => $id))){
	    return true;
	}else{
	    return false;
	} 
    }
    
    function loaddata($id){
	$query = $this->db->get_where('user', array('id' => $id));
	return $query;
    }
    function update($prm) {
	 
	$data = array(
	    'user_name' => $prm['username'],
	    'nama' => $prm['nama'],
	    'password' => $prm['password']);
	    $this->db->where('id', $prm['id']);
	  $this->db->update('user', $data); 
    }
}

?>