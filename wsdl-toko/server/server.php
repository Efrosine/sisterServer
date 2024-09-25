<?php
error_reporting(1);
require_once('nusoap.php');
require_once('Database.php');

$server = new nusoap_server();


$server->configureWSDL('WSDL Toko', 'urn:ServerWSDL');
$server->register(
    'tampil_semua_data',
    array(),
    array('output' => 'xsd:Array'),
    'urn:ServerWSDL',
    'urn:ServerWSDL#tampil_semua_data',
    'rpc',
    'encoded',
    'tampil semua data'
);
$server->register(
    'tampil_data',
    array('input' => 'xsd:String'),
    array('output' => 'xsd:Array'),
    'urn:ServerWSDL',
    'urn:ServerWSDL#tampil_data',
    'rpc',
    'encoded',
    'tampil data'
);
$server->register(
    'tambah_data',
    array('input' => 'xsd:Array'),
    array(),
    'urn:ServerWSDL',
    'urn:ServerWSDL#tambah_data',
    'rpc',
    'encoded',
    'tambah data'
);
$server->register(
    'ubah_data',
    array('input' => 'xsd:Array'),
    array(),
    'urn:ServerWSDL',
    'urn:ServerWSDL#ubah_data',
    'rpc',
    'encoded',
    'ubah data'
);
$server->register(
    'hapus_data',
    array('input' => 'xsd:String'),
    array(),
    'urn:ServerWSDL',
    'urn:ServerWSDL#hapus_data',
    'rpc',
    'encoded',
    'hapus data'
);

function filter($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
    unset($data);
}

function tampil_semua_data()
{
    $abc = new Database();
    $data = $abc->tampil_semua_data();
    return $data;
    unset($abc, $data);
}

function tampil_data($id_barang)
{
    $id_barang = filter($id_barang);
    $abc = new Database();
    $data = $abc->tampil_data($id_barang);
    return $data;
    unset($id_barang, $abc, $data);
}

function tambah_data($data)
{
    $abc = new Database();
    $data = $abc->tambah_data($data);
    unset($abc, $data);
}

function ubah_data($data)
{
    $abc = new Database();
    $data = $abc->ubah_data($data);
    unset($abc, $data);
}

function hapus_data($id_barang)
{
    $id_barang = filter($id_barang);
    $abc = new Database();
    $data = $abc->hapus_data($id_barang);
    unset($id_barang, $abc, $data);
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

unset($server);
