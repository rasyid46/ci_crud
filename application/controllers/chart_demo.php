<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE)

class Chart_demo extends CI_Controller {
	
	public function chart_demo(){
		parent::__construct();
	}
	function index()
	{
		echo "<img src='index.php/chart_demo/generate_chart_manual'><br />"	;
		echo "<img src='index.php/chart_demo/generate_chart_database'>"	;
 
	}
 
	function generate_chart_manual()
	{
                $data[0]["key"]="key 1";
                $data[0]["value"]="17";
                $data[1]["key"]="key 2";
                $data[1]["value"]="26";
                $data[2]["key"]="key 4";
                $data[2]["value"]="37";
                $data[3]["key"]="key 4";
                $data[3]["value"]="7";
 
		echo create_bar_chart("Alasan Pekerjaan",$data,450,250);	
	}
       function generate_chart_database()
	{
		$this->load->plugin('chart');
		$this->load->database();
		$this->db->select (" id_produk as key,  stok  as value");
		 	
		$query=$this->db->get('product');	
		echo create_bar_chart("Alasan Pekerjaan",$query->result_array(),450,250);	
	}
 
}