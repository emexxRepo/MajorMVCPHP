<?php
/**
 * Created by PhpStorm.
 * Developed By Majorman
 * User: mt
 * Date: 12.11.2018
 * Time: 21:54
 */

class Home extends Controller
{

    public function __construct(string $controller, string $_action)
    {
        parent::__construct($controller, $_action);
    }

    public function indexAction()
    {



        $db = DB::getInstance();
        $fields = [
            'fname' => 'john',
            'lname' => 'doe',
            'email' => 'johndoe@example.com',
            'phone1' => '05434442211',
            'phone2' => '05434442211',
            'phone3' => '05434442211',
            'address' => 'City Samel',
            'address2' => 'City Samel2',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'state' => 'Demir',
            'zip_code' => '55400',
            'user_id' => 1

        ];

        /*
         * $update = $db->update('contacts', $fields, 1);
        $insert = $db->insert('contacts', $fields);
        $delete = $db->delete('contacts', 14);*/

        $contacts = $db->find("contacts", [
            'conditions' => "lname = ?",
            'bind' => ['topuz'],
            'order' => "lname,fname",
            'limit' => 2
        ]);

        $contacts1 = $db->findFirst("contacts", [
            'conditions' => "lname = ?",
            'bind' => ['topuz'],
        ]);
        dd($contacts1);
        $this->view->render('home/index');
    }


}