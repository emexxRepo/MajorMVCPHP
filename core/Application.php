<?php
/**
 * Created by PhpStorm.
 * Developed By Majorman
 * User: mt
 * Date: 12.11.2018
 * Time: 21:05
 */

class Application
{
    public function __construct()
    {
        $this->setReporting();
        $this->unsetRegisterGlobals();
    }

    private function setReporting()
    {
        if (DEBUG) {
            error_reporting(E_ALL | E_STRICT);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            ini_set('log_errors', 0);
            ini_set('error_log', ROOT . DS . 'tmp' . 'logs' . DS . 'errors.log');
        }
    }

    private function unsetRegisterGlobals()
    {
        if (ini_get('register_globals')) {
            $globalsAry = ['_SESSION', '_COOKIE', '_POST', '_GET', '_REQUEST', '_SERVER', '_ENV', '_FILES'];
            foreach ($globalsAry as $global) {

                foreach ($GLOBALS[$global] as $key => $value) {
                    if($GLOBALS[$global] === $value){
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }
}