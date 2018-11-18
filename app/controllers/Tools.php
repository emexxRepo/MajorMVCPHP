<?php

class Tools extends Controller
{
    public function __construct(string $controller, string $_action)
    {
        parent::__construct($controller, $_action);
    }

    public function indexAction()
    {
        $this->view->render('tools/index');
    }

    public function firstAction()
    {
        $this->view->render('tools/first');
    }

    public function secondAction()
    {
        $this->view->render('tools/second');
    }
}