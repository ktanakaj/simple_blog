<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

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
        // 許可するアクション一覧に logout と add を設定
        $this->Auth->allow(['logout', 'add']);
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
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $blog = $this->Blogs->newEntity();
        if ($this->request->is('post')) {
            $blog = $this->Blogs->patchEntity($blog, $this->request->data);
            if ($this->Blogs->save($blog)) {
                $this->Flash->success('保存しました。');

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗しました。入力内容を再確認してください。');
            }
        }
        $this->set(compact('blog'));
        $this->set('_serialize', ['blog']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Blog id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $blog = $this->Blogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $blog = $this->Blogs->patchEntity($blog, $this->request->data);
            if ($this->Blogs->save($blog)) {
                $this->Flash->success('保存しました。');

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗しました。入力内容を再確認してください。');
            }
        }
        $this->set(compact('blog'));
        $this->set('_serialize', ['blog']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $blog = $this->Auth->identify();
            if ($blog) {
                $this->Auth->setUser($blog);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('ユーザー名またはパスワードが不正です。');
        }
    }

    public function logout()
    {
        $this->Flash->success('ログアウトしました。');
        return $this->redirect($this->Auth->logout());
    }
}
