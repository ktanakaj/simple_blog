<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Contents Controller
 *
 * @property \App\Model\Table\ContentsTable $Contents
 */
class ContentsController extends AppController
{
    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $content = $this->Contents->newEntity();
        if ($this->request->is('post')) {
            $content = $this->Contents->patchEntity($content, $this->request->data);
            $content->blog_id = $this->blog->id;
            if ($this->Contents->save($content)) {
                $this->Flash->success('保存しました。');

                return $this->redirect(['controller' => 'Blogs', 'action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗しました。入力内容を再確認してください。');
            }
        }
        $blogs = $this->Contents->Blogs->find('list', ['limit' => 200]);
        $this->set(compact('content', 'blogs'));
        $this->set('_serialize', ['content']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Content id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $content = $this->Contents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->Contents->patchEntity($content, $this->request->data);
            $content->blog_id = $this->blog->id;
            if ($this->Contents->save($content)) {
                $this->Flash->success('保存しました。');

                return $this->redirect(['controller' => 'Blogs', 'action' => 'index']);
            } else {
                $this->Flash->error('保存に失敗しました。入力内容を再確認してください。');
            }
        }
        $blogs = $this->Contents->Blogs->find('list', ['limit' => 200]);
        $this->set(compact('content', 'blogs'));
        $this->set('_serialize', ['content']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Content id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $content = $this->Contents->get($id);
        if ($this->Contents->delete($content)) {
            $this->Flash->success('削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。もう一度実行してください。');
        }

        return $this->redirect(['controller' => 'Blogs', 'action' => 'index']);
    }
}
