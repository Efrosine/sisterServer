<?php
error_reporting(E_ALL);
class Database
{
    private $host = "192.168.56.2";
    private $dbname = "toko";
    private $user = "root";
    private $password = "rootpassword";
    private $port = "3306";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            print('gagal');
            echo "Koneksi gagal $e";
        }
    }

    public function login($data){
        $query = $this->conn->prepare("select id_pengguna,nama from pengguna where id_pengguna=? and pin=MD5(?)");
        $query->execute(array($data['id_pengguna'],$data['pin']));

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data;
        $query->closeCursor();
        unset($data);
    }


    public function tampil_semua_data()
    {
        $query = $this->conn->prepare("select id_barang,nama_barang from barang order by id_barang");
        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        return $data;

        $query->closeCursor();
        unset($data);
    }

    public function tampil_data($id_barang)
    {
        $query = $this->conn->prepare("select id_barang,nama_barang from barang where id_barang=?");
        $query->execute(array($id_barang));

        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data;

        $query->closeCursor();
        unset($id_barang, $data);
    }



    public function tambah_data($data)
    {
        $query = $this->conn->prepare("insert ignore into barang (id_barang,nama_barang) values (?,?)");
        $query->execute(array($data['id_barang'], $data['nama_barang']));
        $query->closeCursor();
        unset($data);
    }
    public function ubah_data($data)
    {
        $query = $this->conn->prepare("update barang set nama_barang=? where id_barang=?");
        $query->execute(array($data['nama_barang'], $data['id_barang']));
        $query->closeCursor();
        unset($data);
    }
    public function hapus_data($id_barang)
    {
        $query = $this->conn->prepare("delete from barang where id_barang=?");
        $query->execute(array($id_barang));
        $query->closeCursor();
        unset($id_barang);
    }
}
