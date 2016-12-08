<?php
namespace App\Controller\Admin;

use App\Controller\Controller;

class AppController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->layout('admin');

        // TODO: 認証済みのブログを取得する
        $this->loadModel('Blogs');
        $query = $this->Blogs->find('all', [
            'order' => ['Blogs.id' => 'DESC']
        ]);
        $this->blog = $query->first();
        $this->set('blog', $this->blog);
        $this->set('_serialize', ['blog']);

        // TODO: ブログがまだ作成されていない場合は作成画面のみ許可
    }
}
