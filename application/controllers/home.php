<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	private $data;
	
	function home()
	{  //untuk men set variabel deafult
		parent::__construct();
		$id_user=$this->session->userdata("id_user");
		$noreks = $this->session->userdata("no_rekening");
		//$nama=$this->session->userdata("nama");
		//$assets_url="http://192.168.1.99/skydrive/proyek_akhir21/assets/";
				$assets_url="http://localhost/ta/assets/";
				$title		="Ayunishop Dinar -Dirham";
                
             ///   $tes= $this->output->enable_profiler(TRUE);
               
                //JSON URL which should be requested
                $json_url = 'http://localhost/webbank/index.php/api/kurs';
               // $json_url = 'http://192.168.1.4/webbank/index.php/api/kurs';
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
						$kdinar12= floor($dinar/2);
					
						
						$dirham= $row['kurs_dirham'];
						$kdirhmas12 = floor($dirham/2);
                        $kdirhmas16 = floor($dirham/6);
                        $kdirhmas26 = $kdirhmas16*2;
                        $kdirhmas46 = $kdirhmas16*4;
                        $kdirhmas56 = $kdirhmas16*5;
                        $kdirhmas36 = $kdirhmas16*3;
						 
                        
				
				
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
                
                
                
                
		$this->data = array(  //untuk menyederhanakan fungsi
                                                        "title" => $title,
                                                         "id_user" =>$id_user,   
                                                    //     "nama" =>$nama,   
							"assets_url" => $assets_url,
							"dinar" => $dinar,
							"kdinar12" => $kdinar12,
							
							"dirham" => $dirham,
                            "kdirhmas12" => $kdirhmas12,
                            "kdirhmas16" => $kdirhmas16,
                            "kdirhmas26" => $kdirhmas26,
                            "kdirhmas36" => $kdirhmas36,
                            "kdirhmas46" => $kdirhmas46,
                            "kdirhmas56" => $kdirhmas56,
							
							//"tes"=>$tes,
                            "noreks"=>$noreks,
                            "sdinar"=>$sdinar,
                            "sdirham"=>$sdirham
							
							);
		$this->load->library('cart');
		$this->load->library('belanja');
		$this->load->library('belanjad');
		$this->load->model("mmember","mbr");
		$this->load->model("madmin","madm",'',TRUE);
		$this->load->model("mproduct","mpd");
		$this->load->model("mdetail","mdt");
        $this->load->model("mpemesanan","pp");
        $this->load->model("mtujuan","mt");
        $this->load->model("mongkir","ongkir");
        $this->load->model("mpembayaran","bayar");
        
        
	}
	 
	function index($off =0)
	{
		
		//$this->data['d_member'] = $this->mbr->list_member(3, $off);
		//echo "isi ".$this->data['assets_url'];
                
		 $config['base_url']=base_url()."home/index";
		 $config['total_rows']=$this->mpd->num_product();
		 $config['uri_segment']=3;
		 $config['per_page']=4;
		 
		 $this->pagination->initialize($config);
		 
		
		$this->data['title'] = "Halaman Utama";
		$this->data['d_product'] = $this->mpd->list_product(4,$off);
		$this->data['off'] =$off;
		
                 if($this->session->userdata("is_login") != "2")
		{
			$this->load->view('v_home',$this->data);
		}else{
                    $id_user=$this->session->userdata("id_user");
                     $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
                    $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
                  
                    $this->load->view('v_home2',$this->data);
                }
	}
	
	function register()
	{
		 if ($this->session->userdata("is_login") !=2){
						$this->data['title']="Registrasi Member";
						$this->data['list_ongkir'] = $this->ongkir->list_ongkir();
						$this->load->view('v_register',$this->data);
				  
                }else{
						redirect('home');
					
					}

		
	
	}
	
	
	
        function proses_registrasi()
	{
		$this->data['title'] = "Registrasi";
		
		$nama = $this->input->post('nama');
		$alamat= $this->input->post('umember');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$notlp = $this->input->post('notlp');
		$id_kota = $this->input->post('id_kota');
		$norek = $this->input->post('norek');
		$noktp = $this->input->post('noktp');
		$ttl = $this->input->post('ttl');
		
	
		
		$this->form_validation->set_rules('nama','Name','required|alpha');
		$this->form_validation->set_rules('norek','No rekening','required|numeric|is_unique[user_account.no_rekening]|is_unique[admin.no_rek]');
		$this->form_validation->set_rules('umember','Alamat','required||alpha_numeric');
		$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[user_account.email]');
		$this->form_validation->set_rules('password','Password','required|alpha_numeric|max_length[20]|matches[repassword]');
		$this->form_validation->set_rules('notlp','no telepon ','required|numeric|min_length[7]');
	//	$this->form_validation->set_rules('noktp', 'Nomor KTP', 'required|numeric|min[12]');
		$this->form_validation->set_rules('noktp', 'Nomor KTP', 'required|numeric');
		$this->form_validation->set_rules('ttl', 'Tanggal Lahir', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
        $this->data['list_ongkir'] = $this->ongkir->list_ongkir();    
		$this->load->view('v_register',$this->data);
		}else{
		
		
		//$nilai3 = $_POST['data3'];

		// pengiriman ke situsku.com via CURL
		$url = "http://localhost/webbank/index.php/api/cek_norek";

		$curlHandle = curl_init(); //$curlHandle
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS,"norek=".$norek."&noktp=".$noktp."&ttl=".$ttl);
//		curl_setopt($curlHandle, CURLOPT_POSTFIELDS,"norek=".$norek."&noktp=".$noktp."&ttl=".$ttl);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_exec($curlHandle);

		 $curl_response = curl_exec($curlHandle);
		
		curl_close($curlHandle);
		
		 

		$arr = json_decode($curl_response,true);
		$no_rek = $arr['account'][0]['no_rek'];
		//$passwords = $arr['user'][0]['password'];

		if ($no_rek !=''){
			//echo"login sukses";
			///*
			$prm['nama'] = $nama;
			$prm['alamat'] = $alamat;
			$prm['email'] = $email;
			 
			$prm['password'] = $password;
			$prm['norek'] = $norek;
			$prm['notlp'] = $notlp;
			$prm['id_kota'] = $id_kota;
			$prm['ttl'] = $ttl;
			$prm['noktp'] = $noktp;
			
			if($this->mbr->save_member($prm))
			{
				//$this->data['info_msg'] = "Data berhasil disimpan !!!";
				$this->session->set_flashdata("success_msg","Data berhasil disimpan !!!");	
			}else{
				//$this->data['info_msg'] = "Data gagal disimpan !!!";
				$this->session->set_flashdata("error_msg","Data gagal disimpan !!!");
			}
			
			$this->session->set_flashdata("success_msg","Data berhasil disimpan !!!");	
			redirect("home/register");
		//*/
		}else{
		$this->session->set_flashdata("error_msg","Cek Kembali No Rekening , No KTP dan Tanggal Lahir pada acount bank anda");
		$this->data['list_ongkir'] = $this->ongkir->list_ongkir();    
		$this->load->view('v_register',$this->data);

		
		}
		
		}	
	}
	function login()
	{
		if ($this->session->userdata("is_login") !=2){
							$this->data['title']="Login Member";
							$this->load->view('v_login',$this->data);
							}else{
							redirect("home");
							}
							
		
	}
	
	function crpesan(){
		if ($this->session->userdata("is_login") !=2){
		$this->data['title']="Cara Pesan";
		$this->data['tp']='v_top_menu';
		$this->data['sidebar']='v_sidebar';
		$this->load->view('v_cara_pesan',$this->data);
		}else{
			$id_user=$this->session->userdata("id_user");
			$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
			$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
			$this->data['title']="Cara Pesan";
			$this->data['tp']='v_top_menu_1';
			$this->data['sidebar']='v_sidebar2';
			$this->load->view('v_cara_pesan',$this->data);				
			  }
	
	}
	
	function t(){
	$this->load->view('tes2',$this->data);	
	}
	function tes(){
										$loop = TRUE;
										$dinar1 = 0;
										$dinar12 = 0;
										$dirham1 = 0;
										$dirham12 = 0;
										$dirham16 = 0;
										$nominal = $this->data['dinar'];
										$rp_awal = $nominal;
						
										$dinars=$this->data['dinar'];
									
										$kdinar12= floor($dinars/2);
									
										$dirhams=$this->data['dirham'];
										$kdirhmas12 = floor($dirhams/2);
										$kdirhmas16 = floor($dirhams/6);
										$kdirhmas26 = $kdirhmas16*2;
										$kdirhmas46 = $kdirhmas16*4;
										$kdirhmas56 = $kdirhmas16*5;
										$kdirhmas36 = $kdirhmas16*3;
																																							 
										while($loop == TRUE)
										{
											if(($nominal >= $dinars) and ($nominal % $dinars !=0))
											{
											$dinar1 = floor($nominal/$dinars);
											$nominal = $nominal % $dinars;
											//echo $nominal."</br>";
											}
								
											elseif(($nominal >= $kdinar12) and ($nominal % $kdinar12 != 0))
											{
												$dinar12 = floor($nominal/$kdinar12);
												$nominal = $nominal % $kdinar12;
										//echo $nominal."</br>";
											}
											
											elseif(($nominal >= $dirhams) and ($nominal % $dirhams != 0))
											{
												$dirham1 = floor($nominal/$dirhams);
												$nominal = $nominal % $dirhams;
										//echo $nominal."</br>";
											}
									
											elseif(($nominal >= $kdirhmas12) and ($nominal % $kdirhmas12 != 0))
											{
												$dirham12 = floor($nominal/$kdirhmas12);
												$nominal = $nominal % $kdirhmas12;
										//echo $nominal."</br>";
											}
									
											elseif(($nominal >= $kdirhmas16) and ($nominal % $kdirhmas16 != 0))
											{
												$dirham16 = floor($nominal/$kdirhmas16);
												$nominal = $nominal % $kdirhmas16;
										//echo $nominal."</br>";
											}
												elseif($nominal < 13000)
											{
												$loop = FALSE;
											}
										}
											 
											$this->data['dirhamp']=$dirhamp = $dirham1 + (($dirham12 * 0.5) + ($dirham16 * 0.167));
											
	
		if ($this->session->userdata("is_login") !=2){
		$this->data['title']="Cara Pesan";
		$this->data['tp']='v_top_menu';
		$this->data['sidebar']='v_sidebar';
		$this->load->view('v_tes',$this->data);
		}else{
			$id_user=$this->session->userdata("id_user");
			$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
			$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
			$this->data['title']="Cara Pesan";
			$this->data['tp']='v_top_menu_1';
			$this->data['sidebar']='v_sidebar2';
			$this->load->view('v_tes',$this->data);				
			  }
	
	}
	
	
	function hubkami(){
		if ($this->session->userdata("is_login") !=2){
		$this->data['title']="Hubungi Kami";
		$this->data['tp']='v_top_menu';
		$this->data['sidebar']='v_sidebar';
		$this->load->view('v_hub_kami',$this->data);
		}else{
		$id_user=$this->session->userdata("id_user");
		$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
		$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
		$this->data['title']="Hubungi Kami";
		$this->data['tp']='v_top_menu_1';
		$this->data['sidebar']='v_sidebar2';
		$this->load->view('v_hub_kami',$this->data);	
		}
	
	}
	
	function proses_login()
	{
		//$this->output->enable_profiler(TRUE);
		
		$this->data['title'] = "User Login";
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$this->form_validation->set_rules('email','Email','required|valid_email');
		$this->form_validation->set_rules('password','Password','required|alpha_numeric|max_length[20]');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('v_login',$this->data);
		}else{
		
			$prm['email'] = $email;
			$prm['password'] = $password;
			
			if($this->mbr->cek_user($prm))
			{
				//echo "User ditemukan";
				$sql="select * from user_account where email='$email'";
                $hasilsql=$this->db->query($sql);					
                                
                                                $this->session->set_userdata("is_login","2");
                                                foreach($hasilsql->result() as $row){              
                                                $this->session->set_userdata("email",$row->email);
                                         
                                                $this->session->set_userdata("id_user",$row->id_user);
                                              
                                                $this->session->set_userdata("no_rekening",$row->no_rekening);
                                }
			     $iduser=$this->session->userdata("user_id");
                                                       
						redirect("home/index/",  $this->data);
					
			
			}else{
				//echo "Alien !!!";
							$this->session->set_flashdata("error_msg","User dan password yang dimasukan tidak cocok");
							redirect("home/login");
			}
		}
	}
	
	
        function cek_user(){
            $email = $this->input->post('email');
            $prm['email'] = $email;
            
          //  $this->mbr->cek_member($prm);
           // $data['data_member']=$this->mbr->cek_member($prm);
           
            //$this->load->view('member/v_memberr',$this->data);
          
	 
		
	//$this->load->view('member/v_memberr',$this->data);
        
            
        }
        function proses_login_admin()
	{
		//$this->output->enable_profiler(TRUE);
		
		$this->data['title'] = "Admin Login";
		
		$user_admin = $this->input->post('user_admin');
		$password = $this->input->post('password');
		
		$this->form_validation->set_rules('user_admin','User Admin','required|alpha_dash|max_lenght[20]');
		$this->form_validation->set_rules('password','Password','required|alpha_dash|max_length[20]');
		
		if($this->form_validation->run() == FALSE)
		{
			redirect("home");
		}else{
		
			$padm['user_admin'] = $user_admin;
			$padm['password'] = $password;
			
			if($this->madm->cek_user($padm))
			{
				//echo "User ditemukan";
				 
					$this->session->set_userdata("is_login","1");
					
					$sql="select * from admin where user_admin='$user_admin'";
					$hasilsql=$this->db->query($sql);					
                                
                                               
                    foreach($hasilsql->result() as $row){              
                    $this->session->set_userdata("no_rekening_admin",$row->no_rek);
                    } 
                                              
					redirect("admin/index");
					 
			 
					 
				
			}else{ 
				//echo "Alien !!!";
							$this->session->set_flashdata("error_msg","User dan password yang dimasukan tidak cocok");
							redirect("home/index#myModal");
			}
		}
	}
	
	function list_product($off = 0)
	{
		$config['base_url'] = base_url();
		$config['total_rows'] = $this->mpd->num_product();
		$config['uri_segment'] = 3;
		$config['per_page'] = 3;
		
		$this->pagination->initialize($config);
		
		$this->data['title'] = "List Product";
		$this->data['d_product'] = $this->mpd->list_product(3, $off);
		$this->data['off'] = $off;
		
		$this->load->view('v_home',$this->data);
	}
	
	 
        function belanjaku(){
            
             if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
						$id_user=$this->session->userdata("id_user");
						$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
						$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
						$this->load->view('v_belanjakomplit',  $this->data);
				 
                }
        }
        function add_pemesanan($off =0){
            
            
        $jumlah = $this->input->post('jumlah');
        $id_pemesanan = $this->input->post('id_pemesanan');
        $id_user = $this->input->post('id_user');
        $dirham = $this->input->post('dirham');
        $dinar = $this->input->post('dinar');
        $id_produk = $this->input->post('id_produk');
        $berat = $this->input->post('berat');
        $nama_produk = $this->input->post('nama_produk');
        $stok = $this->input->post('stok');
             
        $this->form_validation->set_rules('jumlah','jumlah  ','required|integer|greater_than[0]');
		
		if($this->form_validation->run() == FALSE)
		{
                $config['base_url']=base_url()."home/add_pemesanan";
				$config['total_rows']=$this->mpd->num_product();
				$config['uri_segment']=3;
				$config['per_page']=4;
		 
				$this->pagination->initialize($config);
		 
		
				$this->data['title'] = "Halaman Utama";
				$this->data['d_product'] = $this->mpd->list_product(4,$off);
				$this->data['off'] =$off;
		
                 $id_user=$this->session->userdata("id_user");
                 $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
                 $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
				 $this->session->set_flashdata("error_msg","Inputan jumlah harus angka atau minimal pemesanan 1");	
                redirect("home");
				
        }		
				else
		{
				
					$prm['id_produk'] = $id_produk;
					$prm['jumlah']  = $jumlah;
					
					if($this->mpd->cek_stok($prm))
					{
						$this->session->set_flashdata("error_msg","Cek stok jumlah yang dimasukan lebih dari stok");
					     redirect("home");
					}
					else{
						
					
        
							$data = array(
						   'id'      => $id_produk,
						   'qty'     => $jumlah,
						   'price'   => $dirham,
						   'dinar'    => $dinar,
						   'berat'    => $berat,
						   'name'    => $nama_produk
						   
						   
						);

						$this->belanjad->insert($data);
						 $this->cart->destroy();
						 $this->session->set_flashdata("success_msg","Barang telah masuk keranjang belanja");
						 redirect(home);
					   
						}
        }
    }
       
		
    function checkout(){
      if ($this->session->userdata("is_login") !=2){
        redirect('home');
     }else{
              $id_user=$this->session->userdata("id_user");
			  $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
			  $id_pengiriman = $this->input->post('id_pengiriman');
              $id_pemesanan =  $this->input->POST('id_pemesanan');
              $id_transaksi =  $this->input->POST('id_transaksi');
              $alamat =  $this->input->post('alamat');
              $notlp=  $this->input->POST('notlp');
              $tb=  $this->input->POST('tb');
              $tdinar=  $this->input->POST('tdinar');
              $tdirham=  $this->input->POST('tdirham');
              $no_rek=  $this->input->POST('no_rek');
              $sdinar=  $this->data['sdinar'];
              $sdirham=  $this->data['sdirham'];
				
				
		
			  $kaldinar = $sdinar - $tdinar;
			  $kaldirham = $sdirham - $tdirham;
		
			  //echo $kaldinar."<br>".$kaldirham;
			   
			   //
			   
										$loop = TRUE;
										$dinar1 = 0;
										$dinar12 = 0;
										$dirham1 = 0;
										$dirham12 = 0;
										$dirham16 = 0;
										$nominal = $this->data['dinar'];
										$rp_awal = $nominal;
						
										$dinars=$this->data['dinar'];
									
										$kdinar12= floor($dinars/2);
									
										$dirhams=$this->data['dirham'];
										$kdirhmas12 = floor($dirhams/2);
										$kdirhmas16 = floor($dirhams/6);
										$kdirhmas26 = $kdirhmas16*2;
										$kdirhmas46 = $kdirhmas16*4;
										$kdirhmas56 = $kdirhmas16*5;
										$kdirhmas36 = $kdirhmas16*3;
																																							 
										while($loop == TRUE)
										{
											if(($nominal >= $dinars) and ($nominal % $dinars !=0))
											{
											$dinar1 = floor($nominal/$dinars);
											$nominal = $nominal % $dinars;
											//echo $nominal."</br>";
											}
								
										elseif(($nominal >= $kdinar12) and ($nominal % $kdinar12 != 0))
										{
											$dinar12 = floor($nominal/$kdinar12);
											$nominal = $nominal % $kdinar12;
									//echo $nominal."</br>";
										}
										
										elseif(($nominal >= $dirhams) and ($nominal % $dirhams != 0))
										{
											$dirham1 = floor($nominal/$dirhams);
											$nominal = $nominal % $dirhams;
									//echo $nominal."</br>";
										}
								
										elseif(($nominal >= $kdirhmas12) and ($nominal % $kdirhmas12 != 0))
										{
											$dirham12 = floor($nominal/$kdirhmas12);
											$nominal = $nominal % $kdirhmas12;
									//echo $nominal."</br>";
										}
								
										elseif(($nominal >= $kdirhmas16) and ($nominal % $kdirhmas16 != 0))
										{
											$dirham16 = floor($nominal/$kdirhmas16);
											$nominal = $nominal % $kdirhmas16;
									//echo $nominal."</br>";
										}
											elseif($nominal < 13000)
										{
											$loop = FALSE;
										}
										}
										$dinar = $dinar1 + ($dinar12 * 0.5);
										$dirhamp = $dirham1 + (($dirham12 * 0.5) + ($dirham16 * 0.167));
										
			   
			   //
			    if ($kaldinar < 0 && $kaldirham < 0){
				$this->session->set_flashdata("error_msg","saldo tidak cukup");	
				redirect("home/belanjaku");
				}else if ($kaldinar < 0){
				//echo "saldo dinar tidak cukup";
				$this->session->set_flashdata("error_msg","saldo dinar tidak cukup");	
				redirect("home/belanjaku");
				}else if ($kaldirham < 0){
				
				$hp = $tdirham - $sdirham; 								//berpa kurangnya		
				$potongdinar = ceil($hp/$dirhamp);						//dari dinar mau dipecah berpa $hp
				$kd = $kaldinar - $potongdinar;                        //itung ulang dinarnya jika dikuarangi potong dinar
																	  // jika hasilnya lebih dari no oke aja
				
					if ($kd > 0 ){
						
						//echo "bisa dipecah  1 dinar berhaga <br>".$dirhamp;
						$udirham = ($dirhamp * $potongdinar) + $sdirham;
						$udinar  = $sdinar - $potongdinar ;
						//
						$pkdirham = $udirham - $tdirham;
						
						if($pkdirham > 0){
								
					//echo "no_rek=".$no_rek."saldo_dinar u=".$udinar."saldo_dirham u=".$udirham."psaldo_dinar b=".$tdinar."psaldo_dirham b=".$tdirham);
					/*
					echo "no_rek=".$no_rek."<br>";
					echo "Update dinar=".$udinar."<br>";
					echo "update dirham=".$udirham."<br>";
					echo "belanja dinar=".$tdinar."<br>";
					echo "belanja dirham=".$tdirham."<br>";
					*/	 

							///*
							 $url = "http://localhost/webbank/index.php/api/konfirmasiol";
							// $url = "http://192.168.1.4/webbank/index.php/api/cek_saldo";
							$curlHandle = curl_init(); //$curlHandle
							curl_setopt($curlHandle, CURLOPT_URL, $url);
						//  curl_setopt($curlHandle, CURLOPT_POSTFIELDS,"no_rek=".$no_rek."&saldo_dinar=".$udinar."&saldo_dirham=".$udirham); curl_setopt($ch2, CURLOPT_POSTFIELDS,"no_rek=".$no_rek."&saldo_dinar=".$tdinar."&saldo_dirham=".$tdirham);
							curl_setopt($curlHandle, CURLOPT_POSTFIELDS,"no_rek=".$no_rek."&saldo_dinar=".$udinar."&saldo_dirham=".$udirham."&psaldo_dinar=".$tdinar."&psaldo_dirham=".$tdirham);
							curl_setopt($curlHandle, CURLOPT_HEADER, 0);
							curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER,1);
							curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
							curl_setopt($curlHandle, CURLOPT_POST, 1);
							curl_exec($curlHandle);
							
					
							$prm['id_pemesanan'] = $id_pemesanan;
							$prm['id_pengiriman'] = $id_pengiriman;
							$prm['id_transaksi'] = $id_transaksi;
							$prm['alamat'] = $alamat;
							$prm['notlp'] = $notlp;
							$prm['tb'] = $tb;
							$prm['id_user'] = $id_user;
							$prm['jumlah_dinar'] = $tdinar;
							$prm['jumlah_dirham'] = $tdirham;
							
							
								
			  
							$this->pp->save($prm);
							$this->mdt->save($prm);
						 
							$this->mt->save_tujuan($prm);
							$this->bayar->save_transaksi($prm);
              
							$this->data['title'] = "Konfirmasi";
							$this->data['tdinar'] = $tdinar;
							$this->data['tdirham'] = $tdirham;
							$this->data['trx'] = $id_transaksi;
							$this->data['status'] = 'Berhasil Saldo dinar berhasil dipecah ke dirham';
              
							$this->cart->destroy();
							$this->belanjad->destroy();
							
							$this->load->view('v_status',  $this->data);
										//		
											
						//	*/														
																		  
																						
										 
										 
										 
										 
								
							//		echo "salfo dirham cukup mau dipotong dari dinar anda";
						}else{
								//echo "salfo dirham tidak cukup meski dipecah";
								$this->session->set_flashdata("error_msg","saldo dirham tidak cukup");	
								redirect("home/belanjaku");
							}					
						//
					}else{
				
						$this->session->set_flashdata("error_msg","saldo dirham tidak cukup");	
						redirect("home/belanjaku");
					}
				
				}else{
				
				//echo "saldo cukup";
							$link = "http://localhost/webbank/index.php/api/konfirmasi_online";		
								
							$ch2 = curl_init(); //$ch2
							curl_setopt($ch2, CURLOPT_URL, $link);
							curl_setopt($ch2, CURLOPT_POSTFIELDS,"no_rek=".$no_rek."&saldo_dinar=".$tdinar."&saldo_dirham=".$tdirham);
							curl_setopt($ch2, CURLOPT_HEADER, 0);
							curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
							curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
							curl_setopt($ch2, CURLOPT_POST, 1);
							curl_exec($ch2);
				 
						//save data
							$prm['id_pemesanan'] = $id_pemesanan;
							$prm['id_pengiriman'] = $id_pengiriman;
							$prm['id_transaksi'] = $id_transaksi;
							$prm['alamat'] = $alamat;
							$prm['notlp'] = $notlp;
							$prm['tb'] = $tb;
							$prm['id_user'] = $id_user;
							$prm['jumlah_dinar'] = $tdinar;
							$prm['jumlah_dirham'] = $tdirham;
							
							
								
			  
							$this->pp->save($prm);
							$this->mdt->save($prm);
						 
							$this->mt->save_tujuan($prm);
							$this->bayar->save_transaksi($prm);
              
							$this->data['title'] = "Konfirmasi";
							$this->data['tdinar'] = $tdinar;
							$this->data['tdirham'] = $tdirham;
							$this->data['trx'] = $id_transaksi;
							$this->data['status'] = 'Saldo berhasil dikurangi';
              
							$this->cart->destroy();
							$this->belanjad->destroy();
							//$this->session->set_flashdata("success_msg","Terima kasih telah berbelanja no transaksi anda $id_transaksi");	
							$this->load->view('v_status',  $this->data);
							//redirect('member/pemesanan/'.$id_user);
				}
			 
			 
			}          
        }
		
		function co(){
		
				   $tdinar=  $this->input->POST('tdinar');
					$tdirham=  $this->input->POST('tdirham');
					$no_rek=  $this->input->POST('no_rek');
					$link = "http://localhost/webbank/index.php/api/konfirmasi_online";		
					// $link = "http://192.168.1.4/webbank/index.php/api/update_saldo";		
					$ch2 = curl_init(); //$ch2
					curl_setopt($ch2, CURLOPT_URL, $link);
					curl_setopt($ch2, CURLOPT_POSTFIELDS,"no_rek=".$no_rek."&saldo_dinar=".$tdinar."&saldo_dirham=".$tdirham);
					curl_setopt($ch2, CURLOPT_HEADER, 0);
					curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch2, CURLOPT_TIMEOUT, 30);
					curl_setopt($ch2, CURLOPT_POST, 1);
					echo curl_exec($ch2);
			  
		}
		
		function cek_norek(){
		
				
				$norek = $this->session->userdata("no_rekening");
				$ad = "http://localhost/webbank/index.php/api/saldo_select";

				$chd = curl_init();  
				curl_setopt($chd, CURLOPT_URL, $ad);
				curl_setopt($chd, CURLOPT_POSTFIELDS,"norek=".$norek);
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
				echo"<br>";
				echo "No Rekening   :	".$norek."<br>";
				echo "Dinar		:	".$sdinar;
				echo"<br>";
				echo "Dirham	: ".$sdirham;
		}
		
		
        
        function checkdinar (){
            $id_pemesanan = $this->input->post('id_pemesanan');
            $id_user=$this->session->userdata("id_user");
            $prm['id_pemesanan']=$id_pemesanan;
            $prm['id_user']=$id_user;
            $this->pp->save2($prm);
            $this->mdt->save2($prm);
            $this->belanja->destroy();
            
            echo $id_pemesanan;
            redirect('home');
        }
        
        function kdirham(){
            
                if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
                    $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
                    $this->data['newiddinar'] = $this->pp->generate_iddinar();
                    $this->load->view('v_kdirham',  $this->data);
                }
        }
              
        function kbelanja(){
            
                if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
                    
						$id_user=$this->session->userdata("id_user");
						$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
						$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
						$this->load->view('v_kbelanja',  $this->data);
                }
        }
        
        
         function update_cart()
		 {
		 $id = $this->input->post('id');
		 $qty = $this->input->post('qty');
		 $rowid = $this->input->post('rowid');
		 $name = $this->input->post('name');
		 
		 $prm['id_produk'] = $id;
		 $prm['qty'] = $qty;
		 /*
		 echo $id."<br>";
		 echo $qty."<br>";
		 echo $rowid."<br>";
		 */
		
		$this->form_validation->set_rules('qty','jumlah  ','required|integer|greater_than[0]');
		
		if($this->form_validation->run() == FALSE)
		{
		$this->session->set_flashdata("error_msg","Inputan jumlah harus angka atau minimal pemesanan 1");	
		redirect('home/belanjaku');
		}
		else
		
		{
				 $prm['id_produk'] = $id;
				 $prm['jumlah'] = $qty;
			
				
				if($this->mpd->cek_stok($prm))
				{
				$this->session->set_flashdata("error_msg","jumlah yang dimasukan melebihi stok");	
				redirect('home/belanjaku');
				}else{
				 $data = array(
					   'rowid' => $rowid,
					   'qty'   => $qty
					);
				$this->session->set_flashdata("success_msg","Pemesanan berhasil di  produk :". $name ." berhasil di update");
				 $this->belanjad->update($data); 
				 redirect('home/belanjaku');
			
					}
		}
		 
        }
        
        function removebelanja($rowid){
            $this->belanjad->update(array(
                'rowid' => $rowid,
                'qty' => 0
            )         
            );
            $this->cart->destroy();
            redirect('home/belanjaku');
        }
		
		function removebelanja2($rowid){
            $this->belanjad->update(array(
                'rowid' => $rowid,
                'qty' => 0
            )         
            );
            $this->cart->destroy();
            redirect('home/kbelanja');
        }
		
		
		
        function removeD($rowid){
            $this->cart->update(array(
                'rowid'=> $rowid,
                'qty'=>0
            ));   
            redirect('home/kdirham');
        }
        
        function kdinar(){
            if ($this->session->userdata("is_login") !="2") {
                redirect('home');
            } else {
                
                 
            
               //     $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
                    $this->data['newiddinar'] = $this->pp->generate_iddinar();
                    $this->load->view('v_kdinar',  $this->data);
            }
        }
        
        function removeDin($rowid){
            $this->belanja->update(array(
                'rowid'=>$rowid,
                'qty'=>0
            ));
            
            redirect('home/kdinar');
        }
        
        function kategori($idkategori){// $this->data['d_product'] 
            $this->data['d_kategori'] =  $this->mpd->get_id_kategori($idkategori);
            if ($this->session->userdata("is_login") !=2){
              
                if ($idkategori == NULL){
                  redirect('home');   
                }else{
                
             $this->load->view('v_kategori', $this->data);
               }           
            }else {
                 $id_user=$this->session->userdata("id_user");
                     $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
                 $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();                
             $this->load->view('v_kategori_2',  $this->data);   
            }          
        }
		
		function cari(){
		  $nama= $this->input->post('nama');
		  $this->data['nama']=$nama;
		 $this->data['hasil'] = $this->mpd->cari($nama);
		
		 if ($this->session->userdata("is_login") !=2){
                            
             $this->load->view('v_hasil_pencarian',$this->data);
                     
            }else {
                 $id_user=$this->session->userdata("id_user");
                     $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
                 $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();                
              $this->load->view('v_hasil_pencarian_2',$this->data);
            }          
		// 
		
		}
        
        function pengiriman(){
             if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
                      $id_user=$this->session->userdata("id_user");
					  $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
					  $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
					  $this->data['idtujuan'] = $this->mt->generate_idtujuan();
					  $this->data['list_ongkir'] = $this->ongkir->get_ongkir();
					  $this->load->view('v_pengiriman',  $this->data);
             
                }
        }
        
        function pengiriman_hitung(){
            if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
                     
                           
              $idpengiriman = $this->input->post('pengiriman');
              $idpesan = $this->input->post('idpesan');
              $nama = $this->input->post('nama');
              $alamat = $this->input->post('alamat');
              $notlp = $this->input->post('notlp');
              $tb = $this->input->post('tb');
              $idkotakota = $this->input->post('kota');
              
                         
            $pecahan = explode("-", $idkotakota);
           
             $id_kota= $pecahan[0]; 
             $kota = $pecahan[1];
            
              $qty = ceil($tb);
              $this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
              $this->data['idtransaksi'] = $this->bayar->generate_idtransaksi();
              
              $this->form_validation->set_rules('nama','Nama ','required|alpha');
              $this->form_validation->set_rules('alamat','Alamat ','required|alpha_dash');
              $this->form_validation->set_rules('notlp','No telepon ','required|numeric|max_length[15]|min_length[7]');
              
              if ($this->form_validation->run() == FALSE){
              $id_user=$this->session->userdata("id_user");
              $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
                    
              
              $this->data['idtujuan'] = $this->mt->generate_idtujuan();
              $this->data['list_ongkir'] = $this->ongkir->get_ongkir();
          
                     $this->load->view('v_pengiriman',  $this->data);
              }else{
                 
                 $data = array(
               'id'      => $id_kota,
               'qty'     => $qty,
               'price'   => $kota,
               'name'    => $nama,
               'options' => array('alamat' => $alamat, 'notlp' => $notlp, 'berat_barang'=>$tb)
                     
                     
            );

            $this->cart->insert($data); 
  
            $id_user=$this->session->userdata("id_user");
            $this->data['idtujuan'] = $this->mt->generate_idtujuan();
            $this->data['d_member']=  $this->mbr->get_member_byid($id_user);
            $this->data['idtujuan'] = $this->mt->generate_idtujuan();
          //$this->load->view('v_pengiriman_hitung',  $this->data);
            redirect('home/rangkuman_pengiriman');  
            
              }
             
                }
        }
        
        function rangkuman_pengiriman(){
            if ($this->session->userdata("is_login") !=2){
                    redirect('home');
                }else{
                    
                    
                    if(!$this->belanjad->contents()){
					redirect('home');
					}else{
                    
                    
							$id_user=$this->session->userdata("id_user");
							$this->data['d_member']=  $this->mbr->get_member_byid($id_user);
							$this->data['idtujuan'] = $this->mt->generate_idtujuan();
							$this->data['idtujuan'] = $this->mt->generate_idtujuan();
							$this->data['newidpemesanan'] = $this->pp->generate_idpemesanan();
							$this->data['idtransaksi'] = $this->bayar->generate_idtransaksi();
							//
							$loop = TRUE;
										$dinar1 = 0;
										$dinar12 = 0;
										$dirham1 = 0;
										$dirham12 = 0;
										$dirham16 = 0;
										$nominal = $this->data['dinar'];
										$rp_awal = $nominal;
						
										$dinars=$this->data['dinar'];
									
										$kdinar12= floor($dinars/2);
									
										$dirhams=$this->data['dirham'];
										$kdirhmas12 = floor($dirhams/2);
										$kdirhmas16 = floor($dirhams/6);
										$kdirhmas26 = $kdirhmas16*2;
										$kdirhmas46 = $kdirhmas16*4;
										$kdirhmas56 = $kdirhmas16*5;
										$kdirhmas36 = $kdirhmas16*3;
																																							 
										while($loop == TRUE)
										{
											if(($nominal >= $dinars) and ($nominal % $dinars !=0))
											{
											$dinar1 = floor($nominal/$dinars);
											$nominal = $nominal % $dinars;
											//echo $nominal."</br>";
											}
								
											elseif(($nominal >= $kdinar12) and ($nominal % $kdinar12 != 0))
											{
												$dinar12 = floor($nominal/$kdinar12);
												$nominal = $nominal % $kdinar12;
										//echo $nominal."</br>";
											}
											
											elseif(($nominal >= $dirhams) and ($nominal % $dirhams != 0))
											{
												$dirham1 = floor($nominal/$dirhams);
												$nominal = $nominal % $dirhams;
										//echo $nominal."</br>";
											}
									
											elseif(($nominal >= $kdirhmas12) and ($nominal % $kdirhmas12 != 0))
											{
												$dirham12 = floor($nominal/$kdirhmas12);
												$nominal = $nominal % $kdirhmas12;
										//echo $nominal."</br>";
											}
									
											elseif(($nominal >= $kdirhmas16) and ($nominal % $kdirhmas16 != 0))
											{
												$dirham16 = floor($nominal/$kdirhmas16);
												$nominal = $nominal % $kdirhmas16;
										//echo $nominal."</br>";
											}
												elseif($nominal < 13000)
											{
												$loop = FALSE;
											}
										}
											 
											$this->data['dirhamp']=$dirhamp = $dirham1 + (($dirham12 * 0.5) + ($dirham16 * 0.167));
										
							//
							$this->load->view('v_pengiriman_hitung',  $this->data);
					   
                }
                }
        }
        
        
           
        function konverter_rupiah($nominal)
		{
			$loop = TRUE;
			$dinar1 = 0;
			$dinar12 = 0;
                        
                        
			$dirham1 = 0;
			
			$dirham56 = 0;
			$dirham46 = 0;
                        $dirham12 = 0;
			$dirham26 = 0;
			$dirham16 = 0;
			
                        $rp_awal = $nominal;
			$dinars=$this->data['dinar'];
                        
                        $kdinar12= floor($dinars/2);
                        
			$dirhams=$this->data['dirham'];
                        $kdirhmas12 = floor($dirhams/2);
                        $kdirhmas16 = floor($dirhams/6);
                        $kdirhmas26 = $kdirhmas16*2;
                        $kdirhmas46 = $kdirhmas16*4;
                        $kdirhmas56 = $kdirhmas16*5;
                        $kdirhmas36 = $kdirhmas16*3;
                        
                        
                        
                        echo 'Rupiah   '.$nominal.'<br><br>';
                        echo '1 Dinar  '.$dinars.'<br>';
                        echo '1/2 Dinar  '.$kdinar12.'<br><br>';
                        
                       echo '1 Dirham  '.$dirhams.'<br>';
                       
                       echo '1/6 Dirham  '.$kdirhmas16.'<br>';
                       echo '2/6 Dirham  '.$kdirhmas26.'<br>';
                       echo '1/2 Dirham  '.$kdirhmas12.'<br>';
                     //  echo '3/6 Dirham  '.$kdirhmas36.'<br>';
                       echo '4/6 Dirham  '.$kdirhmas46.'<br>';
                       echo '5/6 Dirham  '.$kdirhmas56.'<br><br><br>';
                        
                        
			while($loop == TRUE)
			{
				if(($nominal >= $dinars) and ($nominal % $dinars !=0))
				{
					$dinar1 = floor($nominal/$dinars);
					$nominal = $nominal % $dinars;
					//echo $nominal."</br>";
				}
				elseif(($nominal >= $kdinar12) and ($nominal % $kdinar12 != 0))
				{
					$dinar12 = floor($nominal/$kdinar12);
					$nominal = $nominal % $kdinar12;
					//echo $nominal."</br>";
				}
				elseif(($nominal >= $dirhams) and ($nominal % $dirhams != 0))
				{
					$dirham1 = floor($nominal/$dirhams);
					$nominal = $nominal % $dirhams;
					//echo $nominal."</br>";
				}
				elseif(($nominal >= $kdirhmas12) and ($nominal % $kdirhmas12 != 0))
				{
					$dirham12 = floor($nominal/$kdirhmas12);
					$nominal = $nominal % $kdirhmas12;
					//echo $nominal."</br>";
				}
				elseif(($nominal >= $kdirhmas16) and ($nominal % $kdirhmas16 != 0))
				{
					$dirham16 = floor($nominal/$kdirhmas16);
					$nominal = $nominal % $kdirhmas16;
					//echo $nominal."</br>";
				}
				elseif($nominal < 13000)
				{
					$loop = FALSE;
				}
			} 
			
			 $dinar1."-".$dinar12."-".$dirham1."-".$dirham12."-".$dirham16."-".$nominal;
		//	echo $dinar1."-".$dinar12."-".$dirham1."-".$dirham12."-".$dirham16."-".$nominal.'<br>'.'<br>';
		//	echo '___jumlah 1 dinar = '.$dinar1."___Jumlah 1/2 dinar = ".$dinar12."__Jumlah 1 Dirham = ".$dirham1."__Jumlah 1/2 dirham = ".$dirham12."__Jumlah 1/6 dirham =  ".$dirham16."__Sisa nominal   ".$nominal.'<br><br>';
			
                        echo"
                        <table border=1>
                        <tr>
                            <td>1 Dinar</td>
                            <td>1/2 Dinar</td>
                            <td>1 Dirham</td>
                            <td>1/2 Dirham</td>
                            <td>1/6 Dirham</td>
                            <td>Sisa Nominal</td>
                           
                        </tr>      
                        ";
                        echo '<tr>'
                            .'<td>'.$dinar1.'</td>'
                            .'<td>'.$dinar12.'</td>'.
                            '<td>'.$dirham1.'</td>'.
                            '<td>'.$dirham12.'</td>'.
                            '<td>'.$dirham16.'</td>'.
                            '<td>'.$nominal.'</td>'
                            
                        .'</tr></table><br>';
                        
                        
                        
			$dinar = $dinar1 + ($dinar12 * 0.5);
			//echo "Dinar = ".$dinar."</br>";
			//return $dinar;
			
                        $dirham = $dirham1 + (($dirham12 * 0.5) + ($dirham16 * 0.167));
                       
                        
                        echo "Dinar = ".$dinar.'<br>'.'<br>';
                        echo "Dirham = ".$dirham.'<br>'.'<br>';
			
                        
                        return $dinar."-".$dirham."-".$rp_awal."-".$nominal;
			
		}
		
		
         
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */