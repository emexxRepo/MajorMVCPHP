<?php
/**
 * Created by PhpStorm.
 * Developed By Majorman
 * User: mt
 * Date: 12.11.2018
 * Time: 21:28
 */

class View
{
    protected $_head, $_body, $_siteTitle = DEFAULT_TITLE, $_outputBuffer, $_layout = DEFAULT_LAYOUT;

    public function __construct()
    {

    }

    public function render(string $viewName)
    {
        $viewAry = explode('/', $viewName);
        $viewString = implode(DS, $viewAry);

        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php')) {
            include(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php');
            include(ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->_layout . '.php');
        } else {
            die('The View \"' . $viewName . '\" does not exist');
        }
    }

    public function content(string $type = 'head')
    {
        if ($type == 'head') {
            return $this->_head;
        } else if ($type == 'body') {
            return $this->_body;
        }
    }

    public function start(string $type = 'head')
    {
        $this->_outputBuffer = $type;
        ob_start();
    }

    public function end()
    {
        if ($this->_outputBuffer == 'head') {
            $this->_head = ob_get_clean();
        } elseif ($this->_outputBuffer == 'body') {
            $this->_body = ob_get_clean();
        } else {
            die('You must run the first method');
        }
    }

    public function setTitle(string $title = 'MAJOR MVC FRAMEWORK')
    {
        $this->_siteTitle = $title;
    }

    public function setLayout(string $path)
    {
        $this->_layout = $path;
    }
}