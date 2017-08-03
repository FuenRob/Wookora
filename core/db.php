<?php

/* 
 * File db: connections bbdd
 */

class Db
{
    /**
     * Database credentials
     * @var string
     */
    protected $host;
    protected $socket;
    protected $_username;
    protected $_password;
    protected $database;
    protected $port;
    protected $charset;
    protected $connection;
    
    public function __construct()
    {
        $this->host = DB_HOST;
        $this->_username = DB_USER;
        $this->_password = DB_PASSWORD;
        $this->database = DB_DATABASE;
    }
    
    public function connect() {
            $this->connection = mysqli_connect($this->host, $this->_username, $this->_password, $this->database);
            
            if (!$this->connection) {
                print "Problemas para conectar con el servidor de base de datos: ".$host;
                exit();
            }
    }
    
    public function disconnect(){
        if (!$this->connection)
            return false;
        $this->connection->close();
        $this->connection = null;
    }
    
    public function select($colums = '*',$table, $conditions = ''){
        if($conditions == ''){
            $sql = 'SELECT '. $colums . ' FROM ' . $table . ';';
        }else{
            $sql = 'SELECT '. $colums . ' FROM ' . $table . ' WHERE '. $conditions .';';
        }
        
        $result = $this->connection->query($sql);
        return $result;
    }
    
    public function insert($colums,$datas,$table){
        $sql = 'INSERT INTO '. $table .'('. $colums .') VALUES ('. $datas .');';
        
        $result = 0;
        if($this->connection->query($sql) === TRUE){
            $result = db::lastId();
        }else{
            print "Problem with insert SQL";
            exit();
        }
        
        return $result;
    }
    
    public function update($colum,$data,$table,$conditions){
        $sql = 'UPDATE `'. $table .'` SET `'. $colum .'`="'. $data .'" WHERE '.$conditions;
        
        $result = $this->connection->query($sql);
        return $result;
    }
    
    public function lastId(){
        $id = mysqli_insert_id( $this->connection );
        return $id;
        
    }
    
    public function executeQuery($sql = ''){
        if(!empty($sql)){
            $result = $this->connection->query($sql);
            return $result;
        }
        return false;

    }
    
}
