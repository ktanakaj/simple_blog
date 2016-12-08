<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 *
 * @param Blog $blog ブログ情報。
 */
?><!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($blog->title) ?></title>
  <?= $this->Html->css('default.css') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
<body>

<div class="layout">
  <?= $this->Flash->render() ?>
  <header>
    <h1><?= h($blog->title) ?></h1>
  </header>

  <div class="container">
    <?= $this->fetch('content') ?>
  </div>

  <footer>
    <hr>
    <nav>
      <ul>
        <li><?= $this->Html->link('ブログトップへ', ['controller' => 'Blogs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link('ブログの管理', ['prefix' => 'admin', 'controller' => 'Blogs', 'action' => 'index']) ?></li>
      </ul>
    </nav>
  </footer>
</div>

</body>
</html>
