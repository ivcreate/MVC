<?php
abstract class CoreController{
    public $params_on_view = [];
    abstract public function index();
    public function getView($view){
        include_once("./app/views/header.view.php");
        include_once("./app/views/$view.view.php");
        include_once("./app/views/footer.view.php");
    }
    
    public function redirectOn($uri = ''){
        header("Location: http://".$_SERVER['SERVER_NAME']."/".$uri,false);
    }
}