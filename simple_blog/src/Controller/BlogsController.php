<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Blogs Controller
 *
 * @property \App\Model\Table\BlogsTable $Blogs
 */
class BlogsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        // ブログはDBだが現状MAX1件の想定なので1件取得する
        $query = $this->Blogs->find('all', [
            'order' => ['Blogs.id' => 'DESC']
        ]);
        $blog = $query->first();
        $this->set('blog', $blog);
        $this->set('_serialize', ['blog']);

        if (!$blog) {
            return;
        }

        // TODO: ブログが作成済みの場合はコンテンツをページングで取得
        $contents = $blog->contents;

        $this->set(compact('$contents'));
        $this->set('_serialize', ['$contents']);
    }
}
