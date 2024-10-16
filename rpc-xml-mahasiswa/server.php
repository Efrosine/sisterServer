<?php
error_reporting(1); // value 1 error ditampilkan, value 0 error tidak ditampilkan
header('Content-Type: text/xml; charset=UTF-8');

include "database.php";
// buat objek baru dari class Database
$aa = new database();

// function untuk menghapus selain huruf dan angka
function filter($data)
{
	$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
	return $data;
	unset($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$input = file_get_contents("php://input");
	$data = xmlrpc_decode($input);

	$aksi = $data[0]['aksi'];
	$nim = $data[0]['nim'];
	$nama = $data[0]['nama'];
	$no_hp = $data[0]['no_hp'];
	$alamat = $data[0]['alamat'];

	if ($aksi == 'tambah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'alamat' => $alamat
		);
		$aa->tambah_data($data2);
	} elseif ($aksi == 'ubah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'alamat' => $alamat
		);
		$aa->ubah_data($data2);
	} elseif ($aksi == 'hapus') {
		$aa->hapus_data($nim);
	}

	// hapus variable dari memory
	unset($input, $data, $data2, $nim, $nama, $no_hp, $alamat, $aksi);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {	// menampilkan satu data 
	if (($_GET['aksi'] == 'tampil') and (isset($_GET['nim']))) {
		$nim = filter($_GET['nim']);
		$data = $aa->tampil_data($nim);
		$xml = xmlrpc_encode($data);
		echo $xml;
	} else  // menampilkan semua data 
	{
		$data = $aa->tampil_semua_data();
		$xml = xmlrpc_encode($data);
		echo $xml;
	}
	unset($xml, $query, $nim, $data);
}
