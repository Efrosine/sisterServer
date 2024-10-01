<?php
error_reporting(1);
include "Database.php";
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (null !== $_SERVER('HTTP_ACCESS_CONTROL_REQUEST_HEADERS'))
        header("Access-Control_Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(8);
}
$postdata = file_get_contents("php://input");

function filter($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
    unset($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode($postdata);
    $id_barang = $data->id_barang;
    $nama_barang = $data->nama_barang;
    $aksi = $data->aksi;

    if ($aksi == 'tambah') {
        $data2 = array('id_barang' => $id_barang, 'nama_barang' => $nama_barang);
        $abc->tambah_data($data2);
    } elseif ($aksi == 'ubah') {
        $data2 = array('id_barang' => $id_barang, 'nama_barang' => $nama_barang);
        $abc->ubah_data($data2);
    } elseif ($aksi == 'hapus') {
        $abc->hapus_data($id_barang);
    }
    unset($postdata, $data, $data2, $id_barang, $nama_barang, $aksi, $abc);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ((isset($_GET['aksi']) && $_GET['aksi'] == 'tampil')) {
        if ((isset($_GET['id_barang']))) {
            $id_barang = filter($_GET['id_barang']);
            $data= $abc->tampil_data($id_barang);
            echo json_encode($data);
        }
    }else{
        $data = $abc -> tampil_semua_data();
        echo json_encode($data);
    }
    unset($postdata,$data,$id_barang,$abc);
}
