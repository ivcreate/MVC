<?php
class Home extends CoreController{
    //домашняя стрница
    public function index(){
        $this->getView('login');
    }
    //востонавление    
    public function recovery(){
        $this->getView('recovery');
    }
    //регистрация
    public function registration(){
        $this->getView('registration');
    }
}
