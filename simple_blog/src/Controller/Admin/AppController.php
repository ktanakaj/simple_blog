<?php
namespace App\Controller\Admin;

use App\Controller\Controller;

class AppController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->layout('admin');
    }
}
