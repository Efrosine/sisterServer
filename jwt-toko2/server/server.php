<?php
error_reporting(1);

include_once 'core.php';
include_once 'lib/php-jwt/src/BeforeValidException.php';
include_once 'lib/php-jwt/src/ExpiredException.php';
include_once 'lib/php-jwt/src/SignatureInvalidException.php';
include_once 'lib/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

include_once 'Database.php';
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 3600');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (null !== $_SERVER('HTTP_ACCESS_CONTROL_REQUEST_HEADERS'))
        header("Access-Control_Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}
$postdata = file_get_contents("php://input");
$data = json_decode($postdata);



function filter($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
    unset($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($data->id_pengguna) and isset($data->pin)) {
    $data2['id_pengguna'] = $data->id_pengguna;
    $data2['pin'] = $data->pin;

    $data3 = $abc->login($data2);
    if ($data3) {
        $token = array(
            'iat' => $issued_at,
            'exp' => $expiration_time,
            'iss' => $issuer,
            'data' => array(
                'id_pengguna' => $data3['id_pengguna'],
                'nama' => $data3['nama']
            )
        );

        http_response_code(200);
        

        $jwt = JWT::encode($token, $key);
        echo json_encode(
            array(
                'message' => 'Login Success',
                'id_pengguna' => $data3['id_pengguna'],
                'nama' => $data3['nama'],
                'jwt' => $jwt
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(
            array(
                'message' => 'Login Gagal'
            )
        );
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jwt = $data->jwt;
    $aksi = $data->aksi;
    $id_barang = $data->id_barang;
    $nama_barang = $data->nama_barang;
    error_log($id_barang);


    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        if ($aksi == 'tambah') {
            $data2 = array(
                'aksi' => $aksi,
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang
            );
            $abc->tambah_data($data2);
        } elseif ($aksi == 'ubah') {
            $data2 = array(
                'aksi' => $aksi,
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang
            );
            $abc->ubah_data($data2);
        } elseif ($aksi == 'hapus') {
            $data2 = array(
                'aksi' => $aksi,
                'id_barang' => $id_barang
            );
            $abc->hapus_data($id_barang);
        }

        http_response_code(200);
        echo json_encode($data2);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('message' => 'Access Denied'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $jwt = $_GET['jwt'];
    try {

        if (isset($_GET['aksi'])) {
            if(($_GET['aksi'] == 'tampil') and (isset($_GET['id_barang'])))
            $id_barang = filter($_GET['id_barang']);
            $data = $abc->tampil_data($id_barang);
        } else {
            $data = $abc->tampil_semua_data();
        }
        http_response_code(200);
        echo json_encode($data);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array('message' => 'Access denied 2'));
    }
} else {
    http_response_code(401);
    echo json_encode(array('message' => 'Access denied 3'));
}

unset($abc, $postdata, $data, $data2,$data3, $token, $key, $issued_at, $expiration_time, $issuer, $jwt, $id_barang, $nama_barang, $aksi, $e);
