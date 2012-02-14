<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Users;

class UsersController extends \lithium\action\Controller {

    public function index() {
        $users = Users::all();
        return compact('users');
    }

    public function add() 
    {
        $user = Users::create($this->request->data);

        if (($this->request->data) && $user->save()) {
            return $this->redirect('Users::index');
        }
        return compact('user');
    }
}

?>