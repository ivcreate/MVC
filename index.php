<?php
//добалняем поиск по нашим дерикториям mvc
set_include_path(get_include_path()
					.PATH_SEPARATOR.'app/controllers'
					.PATH_SEPARATOR.'app/models'
					.PATH_SEPARATOR.'app/views');
//подключаем функцию подргузки классов
function __autoload($class){
	require_once($class.'.php');
}
//создаем объект маршрутов
$route = new Route();
//передаем руль)
$route->Routing();