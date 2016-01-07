<?php 

		function tanggal_hari_ini($tgl){
		
		$hari=date(w,time());
		$tanggal=date(d,time());
		$bulan=date(m,time());
		$tahun=date(Y,time());
		switch ($hari) {
		case 0: $hari="Minggu"; 
		break;
		case 1: $hari="Senin";
		break;
		case 2: $hari="Selasa";
		break;
		case 3: $hari="Rabu";
		break;
		case 4: $hari="Kamis";
		break;
		case 5: $hari="Jum'at";
		break;
		case 6: $hari="Sabtu";
		break;
		}
		switch ($bulan) {
		case 01: $bulan="Januari";
		break;
		case 02: $bulan="Februari";
		break;
		case 03: $bulan="Maret";
		break;
		case 04: $bulan="April";
		break;
		case 05: $bulan="Mei";
		break;
		case 06: $bulan="Juni";
		break;
		case 07: $bulan="Juli";
		break;
		case 08: $bulan="Agustus";
		break;
		case 09: $bulan="September";
		break;
		case 10: $bulan="Oktober";
		break;
		case 11: $bulan="November";
		break;
		case 12: $bulan="Desember";
		break;
		}
		echo "$hari, $tanggal $bulan $tahun";
		}
		tanggal_hari_ini();
?>