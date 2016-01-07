<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE);

class Admin extends CI_Controller {

    private $data;

    public function admin() {
	parent::__construct();
	$this->load->model("muser", "muser");
    }

    function index() {
	$this->data['list_user'] = $this->muser->list_user();
	$this->load->view("admin/v_view", $this->data);
    }

    function create() {
	$this->load->view("admin/v_create");
    }

    function save_user() {
	$userName = $this->input->post('username');
	$nama = $this->input->post('nama');
	$password = $this->input->post('password');
	$dataInsert = array('username' => $userName, 'nama' => $nama, 'password' => $password);
	if ($this->muser->save($dataInsert)) {
	    $this->session->set_flashdata('success_msg', 'Data Berhasil disimpan');
	} else {
	    $this->session->set_flashdata('error_msg', 'Data gagal disimpan');
	}
	redirect("admin/admin");
    }

    function edit_user($id) {
	$this->data['dataedit'] = $this->muser->loaddata($id);
	$this->load->view("admin/v_edit", $this->data);
    }

    function update_user() {
	$id = $this->input->post('id');
	$userName = $this->input->post('username');
	$nama = $this->input->post('nama');
	$password = $this->input->post('password');
	$prm = array('username' => $userName, 'nama' => $nama, 'password' => $password,'id'=>$id);
//	var_dump($dataupdate); die();
	if ($this->muser->update($prm)) {
	    $this->session->set_flashdata('success_msg', 'Data Berhasil disimpan');
	} else {
	    $this->session->set_flashdata('error_msg', 'Data gagal disimpan');
	}
	redirect("admin/admin");
    }

    function delete_user($id) {
	if ($this->muser->delete_user($id)) {
	    $this->session->set_flashdata('success_msg', 'Data Berhasil dihapus');
	} else {
	    $this->session->set_flashdata('error_msg', 'Data gagal dihapus');
	    echo 'gagal';
	}
	redirect("admin/");
    }

//$this->output->enable_profiler(TRUE
}
