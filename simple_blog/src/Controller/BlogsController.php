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
    public function initialize()
    {
        parent::initialize();

        // ブログはDBだが現状MAX1件の想定なので1件取得する
        $query = $this->Blogs->find('all', [
            'order' => ['Blogs.id' => 'DESC']
        ]);
        $this->blog = $query->first();

        // ブログがまだ作成されていない場合は管理画面に飛ばす
        if (!$this->blog) {
            return $this->redirect(['prefix' => 'admin', 'controller' => 'Blogs', 'action' => 'add']);
        }

        $this->set('blog', $this->blog);
        $this->set('_serialize', ['blog']);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        // TODO: コンテンツをページングで取得
        $contents = $this->blog->contents;
        $this->set(compact('contents'));
        $this->set('_serialize', ['$contents']);
    }

    /**
     * View method
     *
     * @param string|null $id Content id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function contents($id = null)
    {
        // ブログのコンテンツを取得
        $this->loadModel('Contents');
        $content = $this->Contents->get($id, [
            'contain' => ['Tags']
        ]);

        $this->set('content', $content);
        $this->set('_serialize', ['content']);
    }
}
