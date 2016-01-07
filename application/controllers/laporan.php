<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {
	private $data;
	public function laporan()
	{
		parent::__construct();
		
		//
		
			//	$tes= $this->output->enable_profiler(TRUE);
				$assets_url = "http://localhost/skydrive/proyek_akhir19/assets/";
				$title = "Admin - DND ccShop";
		
		
                $json_url = 'http://localhost/webbank/index.php/api/kurs';
                $ch = curl_init( $json_url );
                $options = array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                    // CURLOPT_POSTFIELDS => $json_string
                );
                curl_setopt_array( $ch, $options ); //Setting curl options
                $result =  curl_exec($ch); //Getting jSON result string
                $decode = json_decode($result, true);
                foreach($decode['kurs'] as $row){
                        
                        $row['kurs_dirham'];
                        $row['kurs_dinar'];
                }
                                                     
             
                $dinar= $row['kurs_dinar'];
                $dirham= $row['kurs_dirham'];
		  
			$this->data = array(
			"assets_url" => $assets_url,
			"title" => $title,
			"dinar" => $dinar,
			"dirham" => $dirham
			);
		
				$this->load->model("mmember","mbr");
				$this->load->model("mproduct","mpd");
				$this->load->model("mkategori","ktg");
				$this->load->model("mpemesanan","pp");
				$this->load->model("mdetail","mdt");
                $this->load->model("mongkir","ongkir");
                $this->load->model("mpembayaran","bayar");
                $this->load->model("mtujuan","mt");
		
		//
		$this->load->model('data','dt');
	}  
	
	public function yuhu()
	{
		$this->load->view('admin/v_laporan_penjualan',$this->data);
		//$this->output->enable_profiler(TRUE);
	}
	
	public function jual()
	{
		
		$data = $this->dt->get_data();
		
		$category = array();
		$category['name'] = 'Bulan';
		
		$series1 = array();
		$series1['name'] = 'dinar';
		
		$series2 = array();
		$series2['name'] = 'dirham';
		
	//	$series3 = array();
		//$series3['name'] = 'Highcharts';
		
		foreach ($data as $row)
		{
		    $category['data'][] = $row->bulan;
			$series1['data'][] = $row->dinar;
			$series2['data'][] = $row->dirham;
			//$series3['data'][] = $row->highcharts;
		}
		
		$result = array();
		array_push($result,$category);
		array_push($result,$series1);
		array_push($result,$series2);
		//array_push($result,$series3);
		
		print json_encode($result, JSON_NUMERIC_CHECK);
	}
	
}

/* End of file chart.php */
/* Location: ./application/controllers/chart.php */