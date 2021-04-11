<?php
    class Database {
        private $database;
        public function __construct($dsn = "mysql:host=localhost;dbname=Bank_System", $user = "root", $password = "")
        {
            try{
                $this->database = new PDO($dsn, $user, $password);
            }
            catch(PDOException $ex){
                echo $ex->getMessage();
            }
        }
        public function insert($insert){
            $this->database->exec($insert);
        }
        public function search($search, $column){
            $data = array();
            foreach ($this->database->query($search) as $row) {
                for($i = 0; $i < count($column); $i++):
                   // $data[] = $row[$column[$i]];
                    array_push($data, $row[$column[$i]]);
                endfor;
            }
            return $data;
        }
        public function search2($search){
            $stmt = $this->database->prepare($search);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $data2 = [];
            foreach ($data as $columnName => $value) {
                $data2[] = $value;
            }
            return $data2;
        }
        public function update($update){
            $this->database->exec($update);
        }
        public function remove($remove){
            $this->database->exec($remove);
        }
    }

    $db = new Database();
