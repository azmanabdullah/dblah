<?php


// by Azman abdlh

class DB_dblah {
  public $values = [];
  public $_table;
  public $_select = "*";
  public $_field = 'id';
  public $_result;



  public function __construct($connection = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbname' => ''
  ])
  {

    try
    {
      $config = $connection["driver"].':host='.$connection["host"].';dbname='.$connection["dbname"];      
      $this->con = new PDO($config,$connection["user"],$connection["pass"]);
    }catch(PDOException $e)
    {
      echo $e->getMessage();
      die();
    }
  }

  public function getLastData()
  {
    $query = "SELECT * FROM $this->_table";
    $result = $this->con->query($query);
    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
      $rows = $row; 
    }
    
    return $rows;

  }


  public function insert($data){
    $this->data = $data;
    $index = 0;
    foreach($this->data as $dataValue):
      $valuePrepare .= '?,';
      if(is_integer($dataValue)):
        $this->values[$index] = $dataValue;
      else:
        $this->values[$index] = "$dataValue";
      endif;
      $index++;
    endforeach;

    $kolom = implode(',',array_keys($this->data));
    $valuePrepare = rtrim($valuePrepare,',');

    $this->_query = "INSERT INTO $this->_table ($kolom) VALUES ($valuePrepare)";
    $result = $this->con->prepare($this->_query);

    
    if($result->execute($this->values)){
      
      
      return true;
    }else{

      return false;
    }

  }
  public function to($table)
  {
    $this->_table = $table;
    return $this;
  }

  public function update($field,$key)
  {

    foreach($field as $keyField => $valField)
    {
      $query = "UPDATE $this->_table SET  $keyField = '$valField' WHERE $this->_field = ?";
      $result = $this->con->prepare($query);
      if($result->execute(array($key)))return true;
      else return false;

    }


  }
  public function all()
  {

    $rows = [];
    $query = "SELECT * FROM $this->_table";
    $result = $this->con->query($query);
    while($row = $result->fetch(PDO::FETCH_OBJ))
    {
      $rows[] = $row;
    }
    return $rows;

  }
  public function get($key,$option,$val)
  {

    if(is_int($val))
    {
      $val = $val;
    }else{
      $val = "'".$val."'";
    }

    $query = "SELECT $this->_select FROM $this->_table WHERE $key $option $val";
    $rows = [];
    $result = $this->con->query($query);
    while($row = $result->fetch(PDO::FETCH_OBJ))
    {
      $rows[] = $row;
    }
     return $rows[0];

  }

  public function query($query)
  {
    $this->_result = $this->con->query($query);
    return $this;
  }


  public function result()
  {
    $rows = [];
    while($row = $this->_result->fetch(PDO::FETCH_OBJ))
    {
      $rows[] = $row;
    }

    return $rows;
  }


  public function count()
  {
    $query = "SELECT * FROM $this->_table";
    $result = $this->con->query($query)->rowCount();
    return $result;

  }
  public function select($field = ["*"])
  {
    $this->_select = implode(',',$field);
    return $this;
  }
  public function field($field)
  {
    $this->_field = $field;
    return $this;
  }


  public function join($table,$key1,$key2)
  {
    $query = "SELECT * FROM $this->_table INNER JOIN $table ON $key1 = $key2";
    $this->_result = $this->con->query($query);
    return $this;
  }
   public function leftJoin($table,$key1,$key2)
  {
    $query = "SELECT * FROM $this->_table LEFT JOIN $table ON $key1 = $key2";
    $this->_result = $this->con->query($query);
    return $this;
  }
   public function rightJoin($table,$key1,$key2)
  {
    $query = "SELECT * FROM $this->_table RIGHT JOIN $table ON $key1 = $key2";
    $this->_result = $this->con->query($query);
    return $this;
  }


}

