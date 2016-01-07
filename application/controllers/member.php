<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Member extends CI_Controller {
    private $data;
    
    function member(){
        parent::__construct();
        
        if($this->session->userdata("is_login") != "2")
		{
			redirect("home/index");
		}
        
          //JSON URL which should be requested
                $json_url = 'http://localhost/webbank/index.php/api/kurs';
              //  $json_url = 'http://192.168.1.99/webbank/index.php/api/kurs';
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
                
						$dinar= $row['kurs_dinar'];
						$kdinar12= floor($dinar/2);
					
						
						$dirham= $row['kurs_dirham'];
						$kdirhmas12 = floor($dirham/2);
                        $kdirhmas16 = floor($dirham/6);
                        $kdirhmas26 = $kdirhmas16*2;
                        $kdirhmas46 = $kdirhmas16*4;
                        $kdirhmas56 = $kdirhmas16*5;
                        $kdirhmas36 = $kdirhmas16*3;            
                
				$iduser=$this->session->userdata("user_id");
				$assets_url="http://localhost/ta/assets/";
				$title = "Member -Area";
        
				$id_user=$this->session->userdata("id_user");
				$noreks=$this->session->userdata("no_rekening");
        
				$ad = "http://localhost/webbank/index.php/api/saldo_select";

				$chd = curl_init();  
				curl_setopt($chd, CURLOPT_URL, $ad);
				curl_setopt($chd, CURLOPT_POSTFIELDS,"norek=".$noreks);
				curl_setopt($chd, CURLOPT_HEADER, 0);
				curl_setopt($chd, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($chd, CURLOPT_TIMEOUT, 30);
				curl_setopt($chd, CURLOPT_POST, 1);
				curl_exec($chd);

				$respons = curl_exec($chd);
		
				curl_close($chd);
				                         
				$pec = explode("-",$respons);
				
				$sdinar= $pec[0];
				$sdirham= $pec[1];
        
       $tes= $this->output->enable_profiler(true);
        $this->data  =  array (
                            "assets_url"=>$assets_url,
                            "title" =>$title,
                            "dinar" =>$dinar,
							"kdinar12" => $kdinar12,
							
							"dirham" =>$dirham,
							"kdirhmas12" => $kdirhmas12,
                            "kdirhmas16" => $kdirhmas16,
                            "kdirhmas26" => $kdirhmas26,
                            "kdirhmas36" => $kdirhmas36,
                            "kdirhmas46" => $kdirhmas46,
                            "kdirhmas56" => $kdirhmas56,
							
							
                            "id_user" =>$id_user,
                          //  "tes" =>$tes,
                            "noreks" =>$noreks,
                            "sdinar" =>$sdinar,
                            "sdirham" =>$sdirham
                       
                            
        );
        
        $this->load->model("mmember","mbr");
        $this->load->model("mpemesanan","pp");
        $this->load->model("mdetail","mdt");
        $this->load->model("mpembayaran","bayar");
        $this->load->model("mtujuan","mt");
    }
    
    function index(){
         $id_user=$this->session->userdata("id_user");
     $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
     $this->load->view("member/v_memberr",$this->data);   
   
   }
   //Edit
   
   function edit_profile($id_user){
       $this->data['title']="Member - Edit Profile";
       $this->data['$id_user']=$id_user;
       $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
       $this->load->view('member/v_edit_member',  $this->data);
     }

   function ubah_password($id_user){
	   $this->data['title']="Member - ubah Pasword";
       $this->data['$id_user']=$id_user;
       $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
       $this->load->view('member/v_edit_password',  $this->data);
   }
       
       


   //
   //update
   function update_member(){
       $id_user = $this->input->post('id_user');
       $email =  $this->input->post('email');
       $nama =  $this->input->post('nama');
       $alamat =  $this->input->post('alamat');
       $no_rekening =  $this->input->post('no_rekening');
       $tlp =  $this->input->post('tlp');
       
       $this->form_validation->set_rules('nama','nama','required|alpha');
       $this->form_validation->set_rules('alamat','alamat','required');
       $this->form_validation->set_rules('tlp','telepon','required|integer');
       
       if($this->form_validation->run() == FALSE)
       {
            $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
             $this->data['$id_user']=$id_user;
           $this->data['Title']="Member -Update Profile";
           $this->load->view('member/v_edit_member',  $this->data);
       }
 else {
       $prm ['id_user'] =$id_user;   
       $prm ['nama'] =$nama;   
       $prm ['alamat'] =$alamat;   
       $prm ['tlp'] =$tlp; 
       
       if ($this->mbr->update_member($prm))
            {
                 $this->session->set_flashdata("success_msg","Data berhasil diupdate !!");
            }else {
               $this->session->set_flashdata("error_msg","Data gagal diupdate !!");   
            }
            
            
            redirect("member/index");
       
       }
    
       
       
   }
   
   function update_password(){
     $id_user = $this->input->post('id_user');
     $pl = $this->input->post('pl');
     $password = $this->input->post('password');
     $repassword = $this->input->post('repassword');
     $email = $this->input->post('email');
	 
	 
	 
	 $this->form_validation->set_rules('password','Password','required|alpha_numeric|max_length[20]|matches[repassword]');
	 $this->form_validation->set_rules('repassword','RE- Password','required|alpha_numeric|max_length[20]');
	 $this->form_validation->set_rules('pl','Password Lama','required|alpha_numeric|max_length[20]');
	 
	  if($this->form_validation->run() == FALSE)
       {
           $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
           $this->data['$id_user']=$id_user;
           $this->data['Title']="Member - Update Password";
           $this->load->view('member/v_edit_password',  $this->data);
       }
		else {
	 
	  $prm ['id_user'] =$id_user;  
	  $prm ['password'] =$pl;  
	  $prm ['email'] =$email;  
	 
	  
						  
						   if ($this->mbr->cek_user($prm))
								{
									
									$prm[pbaru]= $password;
																	
									$this->mbr->update_password($prm);
									$this->session->set_flashdata("success_msg","Password berhasil diubah");
									redirect("member/ubah_password/$id_user");
							}else {
								   $this->session->set_flashdata("error_msg","Password lama tidak cocok");   
								   redirect("member/ubah_password/$id_user");
								}
			}
   }  															
													
			
			
			
			
           
             
       
       
	   
	
	
//load aja parameter
   
 

   
    function detail_pemesanan($id_pengiriman){
          
          $id_user=$this->session->userdata("id_user");
          $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
       
        //$this->data['d_detail']=  $this->mdt->get_pemesanan_byid($id_pemesanan);
          $this->data['data_pengiriman']=  $this->mt->get_pengiriman_by_trx($id_pengiriman);
          
		  //$this->data['data_pengiriman']= $this->mt->get_pengiriman_by_trx($id_pengiriman);
		  
          $this->data['title']="Member - Detail Pemesanan";
          $this->load->view('member/v_detail_pemesanan_member', $this->data);
         
    }
	
	public function tespdf(){
	 
	
	}
    


    //
   
   function logout(){
       $this->session->unset_userdata("is_login");
       $this->session->unset_userdata("email");
       $this->session->unset_userdata("id_user");
       $this->session->unset_userdata("no_rekening");
       $this->cart->destroy();
	   $this->belanjad->destroy();
        
//       $this->session->unser_userdata("is_login");
       redirect("home/index");
   
   }
   
      function pemesanan($id_user){
        $id_user=$this->session->userdata("id_user");
		$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
        $this->data['title']= "Member - Pemesanan";
     // $this->data['d_pemesanan'] = $this->pp->get_pemesanan_by_idmember($id_user);
        $this->data['data_pembayaran'] = $this->bayar->get_pembayaran_by_idmember($id_user);
        $this->load->view('member/v_pemesanan_member',$this->data);
		}
   
   function g_pemesanan_m($id_user){
		
        $id_user=$this->session->userdata("id_user");
		$this->data['$id_user']=$id_user;
		$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
        $this->data['thn']=$thn=date("Y");
        $this->data['thn2']=$thn=date("Y");
		 
        $this->data['title']= "Member - Pemesanan";
     // $this->data['d_pemesanan'] = $this->pp->get_pemesanan_by_idmember($id_user);
        $this->data['grafik'] = $this->bayar->g_penjualan_member($id_user);
        $this->load->view('member/v_list_grafik',$this->data);
	}
	
	function g_pemesanan_m2(){
    
		$id_user=$this->session->userdata("id_user");
		$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
        $this->data['title']= "Member - Pemesanan";
		
		 $dari = $this->input->post('dari');
         $sampai = $this->input->post('sampai');

				$d = explode("-",$dari);
				
				$dt = $d[0];
				$dr = $d[1];
				
				$s = explode("-",$sampai);
				$st = $s[0];
				$ds = $s[1];
				
				$prm['id_user']= $id_user;
				$prm['d'] = $dr;
				$prm['dt'] = $dt;
				$prm['s'] = $ds;
				$prm['st'] = $st;
		
		 $this->data['thn']=$dt;
 		$this->data['thn2']=$st;
    	
		$this->data['grafik'] = $this->bayar->g_penjualan_member2($prm);
         $this->load->view('member/v_list_grafik',$this->data);
	}
   
   function lap_pembelian(){
            $id_user=$this->session->userdata("id_user");
            $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
            $dari = $this->input->post('dari');
            $sampai = $this->input->post('sampai');
            /*
          
            echo $dari;
             */
            
            $prm['dari']= $dari;
            $prm['sampai']= $sampai;
            $prm['id_user']= $id_user;
            
           $this->data['query']=$this->bayar->lap_pemebelian_m($prm); 
            
          //  $this->session->set_flashdata("success_msg","data berhasil ditampilkan !!");
            
            $this->data['dari']=$dari;
            $this->data['sampai']=$sampai;
            $this->data['id_user']=$id_user;
            $this->load->view('member/v_list_laporan',  $this->data);
            
   }
      
}