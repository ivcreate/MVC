<?php

class UsersModel extends Db{
    public function __construct(){
        parent::__construct();
    }
        
    public function getAllUsers(){
        $users = $this->db->prepare("SELECT * FROM `users`");
        $users->execute();
        echo "<pre>";
        foreach($users->fetchAll() as $user)
        echo crypt("Hellp","s456");
            print_r($user);
    }
    
    public function addUser($email,$password,$name = ''){
        $hash = $this->getHash();
        //подготовка запроса на добавление
        $user_ins = $this->db->prepare("INSERT INTO `users`(`user_email`, `user_password`, `user_name`, `user_hash`)
                                                     VALUES(".$this->db->quote($email).",".
                                                     $this->db->quote($password).",".
                                                     $this->db->quote($name).",".
                                                     $this->db->quote($hash).")");
        //добавление
        $user_ins->execute();
        //берем id последней вставки
        $id = $this->db->lastInsertId();
        //устанавливаем куки для пользователя
        $this->setCookie($id,$hash);
    }
    
    private function setCookie($id,$hash){
        //устанавливаем время жизни на 1 час
        setcookie("user[id]", $id, time()+3600,"/");
        setcookie("user[hash]", $hash, time()+3600,"/");
    }
    
    private function deleteCookie(){
        //убиваем куки
        setcookie("user[id]", "", time()-3600*60,"/");
        setcookie("user[hash]", "", time()-3600*60,"/");
    }
    
    public function checkCookie(){
        //переводим id из строки в число
        $id = $_COOKIE["user"]["id"]*1;
        $hash =  $_COOKIE["user"]["hash"];
        //проверяем на целлость id и нет ли пробелов в хэше
        if(is_int($id) && !strpos($hash," "))
            //берем пользователя
            if($user = $this->getUserById($id))
                //сравниваем хэши
                if($user["user_hash"] == $hash)
                    //если все ок, позвращаем юзверя
                    return $user;
        
        //если дошли до сюда, значит, что-то пошло не по плану. Убиваем все!
        $this->deleteCookie();
        return false;
    }
    
    public function getUserById($id){
        //Думаю тут и так все понятно
        return $this->db->query("SELECT * FROM users WHERE id=".$this->db->quote($id))->fetch();
    }
    //метод генерации хэша
    private function getHash(){
        //из чего будет составлена строка для хэширования
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        //строка и соль для хэша
        $string = '';
        $salt = '';
        //заполняем рандомными буквами и цифрами из $chars
        for ($i = 0; $i < rand(1, 40); $i++) {
          $salt .= substr($chars, rand(1, $numChars) - 1, 1);
          $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        //возвращаем хэш
        return crypt($string,$salt);
    }
    
    public function login($email,$password){
        $user = $this->db->query("SELECT * FROM users WHERE user_email=".$this->db->quote($email)." 
                                                        AND user_password=".$this->db->quote($password))->fetch();
        if($user){
            //Генерируем новый хэш и обновляем запись (не обязательно)
            $new_hash = $this->getHash();
            $this->db->query("UPDATE users SET user_hash=".$this->db->quote($new_hash)." WHERE id=".$this->db->quote($user["id"]));
            //устанавливаем куки с новым хэшом
            $this->setCookie($user["id"],$new_hash);
            return true;
        }else
            return false;
    }
    
    public function logout(){
        $this->deleteCookie();
    }
}