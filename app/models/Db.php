<?php
//родительский класс базы данных
class Db{
    protected $db;
    private $name_bd = "poddomen";
    private $login_bd = "xxxx";
    private $password_bd = "xxxxx";
    private $host_bd = "localhost";
    
    public function __construct(){
       $this->db = new PDO('mysql:host='.$this->host_bd.';dbname='.$this->name_bd, $this->login_bd, $this->password_bd);
       //необязательный запрос на создание таблицы (здесь только для примера). Также можно добавить поле ip, для большей защиты
       $this->db->query("CREATE TABLE `poddomen`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `user_email` VARCHAR(255) NOT NULL , `user_password` VARCHAR(255) NOT NULL , `user_name` VARCHAR(255) NULL DEFAULT NULL, `user_hash` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }
}
