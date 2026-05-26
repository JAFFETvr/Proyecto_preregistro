<?php
class DBconnection {
    private $host = "host=localhost ";
    private $port = "port=5432 ";
    private $db = "dbname=titledata_table ";
    private $user = "user=jaffetvicente "; 
    private $psw = "password=Charly1982";
    private $conn;

    public function openDB() {
        $connection_string = $this->host . $this->port . $this->db . $this->user . $this->psw;
        $this->conn = @pg_connect($connection_string);
        
        if (!$this->conn) {
            $error = error_get_last();
            die("Error de conexión a la BD: " . $error['message']);
        }
    }
    
    public function closeDB() {
        if ($this->conn) {
            pg_close($this->conn);
        }
    }
    
    public function query($sql) {
        return @pg_query($this->conn, $sql);
    }

    public function getConn() {
        return $this->conn;
    }
}
?>