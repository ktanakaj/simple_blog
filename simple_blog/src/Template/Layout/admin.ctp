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
  <title><?= ($blog ? h($blog->title) : 'Simple Blog') . '管理画面' ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <?= $this->Html->css('admin.css') ?>
  <?= $this->Html->css('dashboard.css') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
<body>

<?= $this->Flash->render() ?>
<header class="header">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <?= $this->Html->link(($blog ? $blog->title : 'Simple Blog') . '管理画面', ['prefix' => false, 'controller' => 'Blogs', 'action' => 'index'], ['class' => 'navbar-brand']) ?>
      </div>
    </div>
  </nav>
  <h1></h1>
</header>

<div class="container-fluid">
  <?= $this->fetch('content') ?>
</div>

<footer>
  <hr>
  <nav>
    <ul>
      <li><?= $this->Html->link('メニューへ', ['prefix' => 'admin', 'controller' => 'Blogs', 'action' => 'index']) ?></li>
      <li><?= $this->Html->link('ブログへ', ['prefix' => false, 'controller' => 'Blogs', 'action' => 'index']) ?></li>
    </ul>
  </nav>
</footer>

</body>
</html>
