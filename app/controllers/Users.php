<?php 

class Users extends CoreController{
    
    public function index(){
        //$json = (int)(json_decode($json)[0]);
        if(!empty($_COOKIE["user"]["id"]) && !empty($_COOKIE["user"]["hash"])){
            $db = new UsersModel();
            //проверяем куки
            $user = $db->checkCookie();
            //проверка чего там нам вернулось, либо user либо false
            if($user){
                $this->params_on_view["user"] = $user;
                $this->getView('user');
            }else
                $this->redirectOn();
        }else
            $this->redirectOn();
    }
    //метод регистрации пользователей
    public function registration(){
        //если массив пустой редиректим на регистрацию
        if(empty($_POST))
            $this->redirectOn('home/registration');
        //проверка полей формы 
        if($this->checkName($_POST["name"]) && $this->checkEmail($_POST["email"]) && $this->checkPassword($_POST["password"], $_POST["password_repeat"])){
            $db = new UsersModel();
            //добавляем пользователя и берем его id
            $db->addUser($_POST["email"],crypt($_POST["password"],"IL#klfdusokj"),$_POST["name"]);
            //переводим на страницу пользователя
            $this->redirectOn('users/index/');  
        }
        else
            $this->redirectOn('home/registration');
    }
    
    public function login(){
        if(empty($_POST))
            $this->redirectOn('');
        if($this->checkEmail($_POST["email"]) && $this->checkPassword($_POST["password"],$_POST["password"])){
        $db = new UsersModel();
        //проверяем пользователя
        if($db->login($_POST["email"],crypt($_POST["password"],"IL#klfdusokj")))
            $this->redirectOn('users/index/');
        else
            $this->redirectOn('');
        }else
            $this->redirectOn('');
    }
    
    public function logout(){
        $db = new UsersModel();
        //удаляем куки
        $db->logout();
        //переводим на главнуюс траницу
        $this->redirectOn(''); 
    }
    
    //проверка имени
    private function checkName($name){
            return true;
    }
    
    //проверка email
    private function checkEmail($email){
        //проверка на пустоту и по фильтру
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }
    
    //проверка пароля
    private function checkPassword($pass,$pass_repeat){
        //проверка на пустоту и сравнение их 
        if(!empty($pass)  && !empty($pass_repeat) && $pass == $pass_repeat)
            return true;
        else
            return false;
    }
    
}