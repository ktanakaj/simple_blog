<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 */
?><!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->fetch('title') ?></title>
  <?= $this->Html->css('admin.css') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
<body>

<header>
  <h1><?= $this->fetch('title') ?></h1>
</header>

<div class="container">
  <?= $this->fetch('content') ?>
</div>

<footer>
  <hr>
  <nav>
    <ul>
      <li><a href="./">メニューへ</a></li>
      <li><a href="../">ブログへ</a></li>
    </ul>
  </nav>
</footer>

</body>
</html>
