<?php
namespace App\Controller\Admin;

use App\Controller\Controller;

class AppController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->layout('admin');

        // 管理画面は要認証
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Blogs',
                    'fields' => [
                        'username' => 'mail_address',
                        'password' => 'password'
                    ],
                ]
            ],
            'loginAction' => [
                'controller' => 'Blogs',
                'action' => 'login'
            ],
            'unauthorizedRedirect' => $this->referer() // 未認証時、元のページを返します。
        ]);

        // PagesController が動作し続けるように
        // display アクションを許可
        $this->Auth->allow(['display']);

        // TODO: 認証済みのブログを取得する
        $this->loadModel('Blogs');
        $id = $this->Auth->user('id');
        $this->blog = null;
        if ($id) {
            $this->blog = $this->Blogs->get($id);
        }
        $this->set('blog', $this->blog);
        $this->set('_serialize', ['blog']);
    }
}
