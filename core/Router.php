<?php
/**
 * Created by PhpStorm.
 * Developed By Majorman
 * User: mt
 * Date: 12.11.2018
 * Time: 19:31
 */

class Router
{
    public static function route(array $url)
    {
        //CONTROLLERS
        $controller  = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
        $controllerName = $controller;
        array_shift($url);
            //array_shift() işlevi deste dizisinin ilk elemanını çekip
        // döndürürken diziden o elemanı eksiltip diziyi yeniden indisler.
        // Dizgesel anahtarlara dokunulmazken sayısal anahtarlar
        // sıfırdan başlayarak yeniden oluşturulur.


        //ACTİONS
        $action = (isset($url[0]) && $url[0] !='') ? $url[0] . 'Action' : 'indexAction';
        $actionName = $controller;
        array_shift($url);


        //QUERY PARAMS
        $queryParams = $url;
        $dispatch = new $controller($controllerName,$action);

        if(method_exists($controller,$action)){
            call_user_func_array([$dispatch,$action],$queryParams);
        } else{
            die('The Method does not exist in the controller \n"' . $controllerName .'\"');
        }

    }
}