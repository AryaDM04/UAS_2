<?php
include("koneksi.php");

class User
{
    //ini juga enkapsulasi
    protected $password;
    // kalau ini kita enkapsulasi passwordnya biar yang diluar dari kelas user tidak bisa semenah menah untuk mengakses password ini 
    public $username;
    public $fullname;
    public function __construct($username, $password, $fullname)
    {
        $this->username = $username;
        $this->password = $password;
        $this->fullname = $fullname;
    }
}

class UserManager extends User
{
    // enkapsulasi
    protected $conn;
    // disini yang kita enkap itu $conn yang dimana berarti koneksinya ke database itu kita proteksi/lindungi jadi yang berada diluar kelas user managerataupun turunannya itu tidak bisa dy akses semenah-menah jadi khusus saja yang berada dalam kelas user manager dan anakan/inehritnya yang bisa akses
    public function __construct($conn, $password, $username, $fullname)
    {
        parent::__construct($password, $username, $fullname);
        $this->conn = $conn;
    }

    public function createUser()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $queri = mysqli_query($this->conn, "INSERT INTO tb_user (fullname, username, password) VALUES ('$this->fullname','$this->username','$hashedPassword')");
        return $queri;
    }
}

class Login extends User
{
    protected $conn;
    public function __construct($conn, $username, $password)
    {
        parent::__construct($username, $password, '');
        $this->conn = $conn;
    }

    public function loginUser()
    {
        $query = mysqli_query($this->conn, "SELECT * FROM tb_user WHERE username = '$this->username'");
        $userData = mysqli_fetch_assoc($query);
        if ($userData) {
            if (password_verify($this->password, $userData["password"])) {
                session_start();
                $_SESSION["username"] = $this->username;
                header("location:tiket/index.php");
            } else {
                header("location:login.php?pesan=password salah");
            }
        } else {
            header("location:login.php?pesan=username salah");
        }
    }
}
