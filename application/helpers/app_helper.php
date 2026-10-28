<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('getconfig'))
{
	function getconfig($value)
	{
		if($value == "" ) {return false;}
		$ci =& get_instance();
		$ci->db->select('config.config_name, config.config_content');
		$ci->db->where('config.config_name', $value);
		$ci->db->limit(1,0);
		$q = $ci->db->get('config');
		if($q->num_rows() > 0)
		{
			$r = $q->row_array();
			return $r['config_content'];
		}
		else
		{
			return false;
		}
	}
}


if ( ! function_exists('nuxencrypt'))
{
	function nuxencrypt($string, $action) 
	{ 
		$secret_key = 'jJr6dky9kRt2jkqLFsSNmBw8twBx7JkX';
		$secret_iv = 'CeYfQkyy6Qzf44jHPcn8CSyhsUae7rO2';	 
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		if( $action == 'hide' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'show' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
		return $output;
	}
}

//generate unique id
if (!function_exists('generate_unique_id')) {
	function generate_unique_id()
	{
		$id = uniqid("", TRUE);
		$id = str_replace(".", "-", $id);
		return $id . "-" . rand(10000000, 99999999);
	}
}

//generate username
if (!function_exists('str_username')) {
	function str_username($str)
	{
		return url_title(convert_accented_characters($str), "_", true);
	}
}

//generate slug
if (!function_exists('str_slug')) {
	function str_slug($str)
	{
		return url_title(convert_accented_characters($str), "-", true);
	}
}

if ( ! function_exists('indo_date'))
{
	function indo_date($tgl)
	{
		if(strtotime($tgl) != NULL){	
		$ubah = gmdate($tgl, time()+60*60*8);
		$pecah = explode("-",$ubah);
		$tanggal = $pecah[2];
		$bulan = bulan($pecah[1]);
		$tahun = $pecah[0];
		return $tanggal.' '.$bulan.' '.$tahun;
		}
	}
}

if ( ! function_exists('bulan'))
{
	function bulan($bln)
	{
		switch ($bln)
		{
			case 1:
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	}
}


if ( ! function_exists('nama_hari'))
{
	function nama_hari($day) 
	{
		switch ($day)
		{
			case 'Sunday':
				return "Ahad";
				break;
			case 'Monday':
				return "Senin";
				break;
			case 'Tuesday':
				return "Selasa";
				break;
			case 'Wednesday':
				return "Rabu";
				break;
			case 'Thursday':
				return "Kamis";
				break;
			case 'Friday':
				return "Jum'at";
				break;
			case 'Saturday':
				return "Sabtu";
				break;
		}
	}
}
if ( ! function_exists('nuxcrypto'))
{
	function nuxcrypto($string, $action) 
	{ 
		$secret_key = 'Q8RbJ4uaQ7';
		$secret_iv = '3zGZYavqS4';	 
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		if( $action == 'encrypt' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
		return $output;
	}
}
if (! function_exists('client_info'))
{
	function client_info($id, $field)
	{
		$ci = get_instance();
		$ci->db->select('*');
		$ci->db->from('clients');
		$ci->db->where('id', $id);
		$q = $ci->db->get();
		$r = $q->row_array();
		return $r[$field];
	}
}

if (! function_exists('client_public_info'))
{
	function client_public_info($id, $field)
	{
		$ci = get_instance();
		$ci->db->select('*');
		$ci->db->from('clients_public');
		$ci->db->where('id', $id);
		$q = $ci->db->get();
		$r = $q->row_array();
		return $r[$field];
	}
}

if (! function_exists('therapy_info'))
{
	function therapy_info($therapy_id, $field)
	{
		$ci = get_instance();
		$ci->db->select('*');
		$ci->db->from('therapy');
		$ci->db->where('therapy_id', $therapy_id);
		$ci->db->limit(1,0);
		$q = $ci->db->get();
		if($q->num_rows() > 0)
		{
			$r = $q->row_array();
			return $r[$field];
		}
	}
}
if (! function_exists('group_info'))
{
	function group_info($id, $field)
	{
		$ci = get_instance();
		$ci->db->select('*');
		$ci->db->from('group_public');
		$ci->db->where('group_id', $id);
		$ci->db->limit(1,0);
		$q = $ci->db->get();
		$r = $q->row_array();
		return $r[$field];
	}
}
if (! function_exists('hitung_umur'))
{
	function hitung_umur($tanggal_lahir){
		$birthDate = new DateTime($tanggal_lahir);
		$today = new DateTime("today");
		if ($birthDate > $today) { 
			exit("0 tahun 0 bulan 0 hari");
		}
		$y = $today->diff($birthDate)->y;
		$m = $today->diff($birthDate)->m;
		$d = $today->diff($birthDate)->d;
		return $y." tahun ".$m." bulan ".$d." hari";
	}
}
if (! function_exists('umur_saat_dass'))
{
	function umur_saat_dass($tanggal_lahir, $tanggal_mengisi){
		$birthDate = new DateTime($tanggal_lahir);
		$today = new DateTime($tanggal_mengisi);
		if ($birthDate > $today) { 
			exit("0 tahun 0 bulan 0 hari");
		}
		$y = $today->diff($birthDate)->y;
		$m = $today->diff($birthDate)->m;
		$d = $today->diff($birthDate)->d;
		// return $y." tahun ".$m." bulan ".$d." hari";
		return $y;
	}
}
if (! function_exists('dass_result'))
{
	function dass_result($kategori_id, $nilai)
	{
		/* Kategori ID
			1 -> Tekanan (Stress) 	
			2 -> Kebimbangan (Anxiety) 	
			3 -> Kemurungan (Depression) 	
		*/
		switch ($kategori_id)
		{
			case 1:
				if($nilai <=7 )
				{
					$keputusan = 'Normal';
				}
				else if($nilai <= 9)
				{
					$keputusan = 'Ringan';
				}
				else if($nilai <= 13)
				{
					$keputusan = 'Sederhana';
				}
				else if($nilai <= 17)
				{
					$keputusan = 'Berat';
				}
				else if($nilai >= 18)
				{
					$keputusan = 'Sangat Berat';
				}
			break;
			case 2:
				if($nilai <=4)
				{
					$keputusan = 'Normal';
				}
				else if($nilai <= 6)
				{
					$keputusan = 'Ringan';
				}
				else if($nilai <= 8)
				{
					$keputusan = 'Sederhana';
				}
				else if($nilai <= 10)
				{
					$keputusan = 'Berat';
				}
				else if($nilai >= 11)
				{
					$keputusan = 'Sangat Berat';
				}
			break;
			case 3:
				if($nilai <= 5)
				{
					$keputusan = 'Normal';
				}
				else if($nilai <= 7)
				{
					$keputusan = 'Ringan';
				}
				else if($nilai <= 8)
				{
					$keputusan = 'Sederhana';
				}
				else if($nilai <= 14)
				{
					$keputusan = 'Berat';
				}
				else if($nilai >= 15)
				{
					$keputusan = 'Sangat Berat';
				}
			break;
			default:
				$keputusan = '';
		}
		return $keputusan;
	}
}
if ( ! function_exists('jenis_kelamin'))
{
	function jenis_kelamin($gender) 
	{
		switch ($gender)
		{
			case 'Male':
				return "Laki-laki";
				break;
			case 'Female':
				return "Perempuan";
				break;
		}
	}
}

if ( ! function_exists('tindak_lajut_rpk'))
{
	function tindak_lajut_rpk($val) 
	{
		switch ($val)
		{
			case 1:
				return "Sesi dilanjutkan";
			break;
			case 2:
				return "Sesi Selesai";
			break;
			case 3:
				return "Diteruskan ke Ahli Lain";
			break;
			default:
				return false;
			break;
		}
	}
}
if ( ! function_exists('keputusan_dass'))
{
	function keputusan_dass($test_id,$kategori_id)
	{
		/* Kategori ID
			1 -> Tekanan (Stress) 	
			2 -> Kebimbangan (Anxiety) 	
			3 -> Kemurungan (Depression) 	
		*/
		$ci =& get_instance();
		$ci->db->select('soal_kategori.soal_kategori_id,soal_kategori.kategori_nama');
        $ci->db->from('soal_kategori');
		$ci->db->where('soal_kategori.soal_kategori_id', $kategori_id);
        $ci->db->order_by('soal_kategori.soal_kategori_id', 'asc');
        $kategori_soal = $ci->db->get();

		$jumlah = 0;
		$result = '';
		if($kategori_soal->num_rows() > 0)
		{
			foreach ($kategori_soal->result() as $kategori)
			{
				if(helper_dass_by_group($test_id, $kategori_id)->num_rows() > 0)
				{
					foreach (helper_dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
					{
						$jumlah = $jumlah + $nilai->jawaban;
					}
					$jumlah;
					$result = dass_result($kategori_id, $jumlah);
				}
			}
		}
		return $result;
		
	}
}

if ( ! function_exists('poin_dass'))
{
	function poin_dass($test_id,$kategori_id)
	{
		/* Kategori ID
			1 -> Tekanan (Stress) 	
			2 -> Kebimbangan (Anxiety) 	
			3 -> Kemurungan (Depression) 	
		*/
		$ci =& get_instance();
		$ci->db->select('soal_kategori.soal_kategori_id,soal_kategori.kategori_nama');
        $ci->db->from('soal_kategori');
		$ci->db->where('soal_kategori.soal_kategori_id', $kategori_id);
        $ci->db->order_by('soal_kategori.soal_kategori_id', 'asc');
        $kategori_soal = $ci->db->get();

		$jumlah = 0;
		$result = '';
		if($kategori_soal->num_rows() > 0)
		{
			foreach ($kategori_soal->result() as $kategori)
			{
				if(helper_dass_by_group($test_id, $kategori_id)->num_rows() > 0)
				{
					foreach (helper_dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
					{
						$jumlah = $jumlah + $nilai->jawaban;
					}
					$jumlah;
					$result = dass_result($kategori_id, $jumlah);
				}
			}
		}
		return $jumlah;
	}
}
if ( ! function_exists('helper_dass_by_group'))
{
	function helper_dass_by_group($test_id, $kategori_id)
	{
		$ci =& get_instance();
		$ci->db->select( 'hasil_test.hasil_id,
				hasil_test.test_id,
				hasil_test.client_id,
				hasil_test.soal_id,
				hasil_test.jawaban,
				soal.pertanyaan,
				soal.kategori_id,
				soal_kategori.kategori_nama
			');
		$ci->db->from('hasil_test');
		$ci->db->join('soal', 'soal.soal_id = hasil_test.soal_id');
		$ci->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
		$ci->db->where('hasil_test.test_id', abs((int) $test_id));
		$ci->db->where('soal.kategori_id', abs((int) $kategori_id));
		$ci->db->order_by('soal.soal_id', 'asc');
		$q = $ci->db->get();
		return $q;
	}
}

if ( ! function_exists('soal_by_kategori'))
{
	function soal_by_kategori($kategori_id)
	{
		$ci =& get_instance();
		$ci->db->select('soal.soal_id,soal.pertanyaan,soal.kategori_id,soal_kategori.kategori_nama');
		$ci->db->from('soal');
		$ci->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
		$ci->db->where('soal.kategori_id', abs((int) $kategori_id));
		$ci->db->order_by('soal.soal_id', 'asc');
		$q = $ci->db->get();
		return $q;
	}
}

if ( ! function_exists('columnFromIndex'))
{
	function columnFromIndex($number){
		if($number === 0)
			return "A";
		$name='';
		while($number>0){
			$name=chr(65+$number%26).$name;
			$number=intval($number/26)-1;
			if($number === 0){
				$name="A".$name;
				break;
			}
		}
		return $name;
	}
}

if (! function_exists('counselor_info'))
{
	function counselor_info($id, $field)
	{
		if($id != '' && $field != '')
		{
			$ci = get_instance();
			$ci->db->select('*');
			$ci->db->from('counselor');
			$ci->db->where('counselor_id', $id);
			$ci->db->limit(1,0);
			$q = $ci->db->get();
			if($q->num_rows() > 0)
			{
				$r = $q->row_array();
				return $r[$field];
			}
		}
	}
}
