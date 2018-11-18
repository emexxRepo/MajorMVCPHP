<?php
/**
 * Created by PhpStorm.
 * Developed By Majorman
 * User: mt
 * Date: 12.11.2018
 * Time: 21:20
 */

class  Controller extends Application
{
    protected $_controller, $_action;
    public $view;

    public function __construct(string $controller, string $_action)
    {
        parent::__construct();
        $this->_controller = $controller;
        $this->_action = $_action;
        $this->view = new View();
    }

}