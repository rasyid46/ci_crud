<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('data','dt');
	}  
	
	public function yuhu()
	{
		$this->load->view('chart');
		 
	}
	
	public function datas()
	{
		
		$data = $this->dt->get_data();
		
		$category = array();
		$category['name'] = 'Bulan';
		
		$series1 = array();
		$series1['name'] = 'dinar';
		
		$series2 = array();
		$series2['name'] = 'dirham';
		
		$series3 = array();
		$series3['name'] = 'rupiah';
		
	//	$series3 = array();
		//$series3['name'] = 'Highcharts';
		
		foreach ($data as $row)
		{
		    $category['data'][] = $row->bulan;
			$series1['data'][] = $row->dinar;
			$series2['data'][] = $row->dirham;
			$series3['data'][] = $row->rupiah;
		}
		
		$result = array();
		array_push($result,$category);
		array_push($result,$series1);
		array_push($result,$series2);
		array_push($result,$series3);
		
		print json_encode($result, JSON_NUMERIC_CHECK);
		
//	$this->load->view('chart');
  //redirect("chart/yuhu");
   
	}
	
	function tes(){
	// $data['namaLow']=  $this->mpegawai->getNamaLowongan($idLow);
	//$data['data'] = $this->dt->get_data();
	$this->load->view('chart2');
	$this->output->enable_profiler(TRUE);
	}
	
	function testpdf(){
	  $this->load->library('fpdf');
	
	  $this->fpdf->FPDF('P','cm','A4');
	  $this->fpdf->AddPage();
	  //$this->fpdf->SetFont('Arial','',10);
	  $this->fpdf->setFont('Arial','B',9);
	  $teks = "Ini hasil Laporan PDF menggunakan Library FPDF di CodeIgniter";
	  $this->fpdf->Cell(3, 0.5, $teks, 1, '0', 'L', true);
		
	//	$this->fpdf->setFont('Arial','B',7);
		
		$this->fpdf->Text(8,1.9,'Jl.Zambrud I No.35 Sumur Batu - Jakarta Pusat');
		 
		 
		$this->fpdf->Line(15.6,2.1,5,2.1);  
			$this->fpdf->Text(8,1.9,'Jl.Zambrud I No.35 Sumur Batu - Jakarta Pusat');
		//$this->fpdf->Ln();
	  
	  $this->fpdf->Output(); 
	  	

		$this->load->library('pdf');
		$pdf = new PDF();
		$pdf->SetMargins(1,1);
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0.5,'Hellol Ci',0,1,'C');
		$pdf->Output();
	 
	 /*
		$this->load->library('fpdf');
		$this->fpdf->FPDF('P','cm','A4');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->Text(7.5,1,"DAFTAR PENJUALAN BULAN ");
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->Text(8.3,1.5,'KOMUNITAS MUSISI INDONESIA');
		$this->fpdf->setFont('Arial','B',7);
		$this->fpdf->Text(8,1.9,'Jl.Zambrud I No.35 Sumur Batu - Jakarta Pusat');
		 
		$this->fpdf->Line(15.6,2.1,5,2.1);             
		$this->fpdf->ln(1.6);
		$this->fpdf->ln(0.3);
		$this->fpdf->Output(); 
	*/
	}
	
}

/* End of file chart.php */
/* Location: ./application/controllers/chart.php */