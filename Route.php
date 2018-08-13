<?php
//класс маршрутов сайта
class Route{
    //переменные класса Route, для хранения маршрута
    protected $controller;
    protected $action;
    protected $params = [];
    static $_instance;
    //реализация синглтона. Но она мне не понадобилась
    public static function getInstance() {
		if(!(self::$_instance instanceof self)) 
			self::$_instance = new self();
		return self::$_instance;
	}
    //обработчик урла
    public function __construct(){
        //если нет uri то это главная страница
        if(!isset($_SERVER['REDIRECT_URL'])){
            //назначаем контроллер и метод из него
            $this->controller = 'Home';
            $this->action = 'index';
        }else{
            //обработываем ури
            $this->uriReader();
            //если файл контроллера не найден то выдаем ошибку
            if(!file_exists("app/controllers/$this->controller.php"))
                echo "bad getoway class not found";
        }
    }
    //метод разбора uri вида /controller/action/param1/param2....
    private function uriReader(){
        //создаем массив из частей ссылки
        $uri = explode('/',$_SERVER['REDIRECT_URL']);
        //первой присваиваем контроллер
        $this->controller = ucfirst($uri[1]);
        //если вторая часть есть, та записываем ее, если нет то вызываем метод index
        if(!empty($uri[2]))
            $this->action = $uri[2];
        else
            $this->action = 'index';
        //записываем параметры
        if(!empty($uri[3]))
            for($i = 3; $i < count($uri); $i++)
                $this->params[] = $uri[$i];
                
                
    }
    //Создаем Reflection и вызываем с помощью него метод контроллера и передаем параметры в него
    public function Routing(){
        //проверка на существование класса контроллера
        if(class_exists($this->getController())){
            // создание объекта ReflectionClass
          $rc = new ReflectionClass($this->getController());
          //проверка существования метода в этом контроллере
          if($rc->hasMethod($this->getAction())) {
            //создание экземпляра класса контроллера
			$controller = $rc->newInstance();
            //выбор метода
			$method = $rc->getMethod($this->getAction());
            //вызов метода и передача параметров
			$method->invoke($controller, $this->getParams());  
            }else{
                //ВСЕ ПЛОХО(
                echo "Bad getway. Method not found: ".$this->getAction().". In controller: $this->controller";
            }
        }
    }
    //возвращает контроллер
    protected function getController(){
        return $this->controller;
    }
    //возвращает метод
    protected function getAction(){
        return $this->action;
    }
    //возвращает параметры в json формате
    protected function getParams(){
        //если не пусто, то переводим в json
        if(!empty($this->params))
            return json_encode($this->params);
    }      
        
}
