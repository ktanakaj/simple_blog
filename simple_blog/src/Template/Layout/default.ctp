<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 *
 * @param string $widget ウィジェット。
 */
?><!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->fetch('title') ?></title>
  <?= $this->Html->css('default.css') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
<body>

<?php if (!empty($widget)): ?>
  <aside class="widget">
    <?= $widget ?>
  </aside>
<?php endif; ?>

<div class="layout">
  <?= $this->Flash->render() ?>
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
        <li><a href="./">ブログトップへ</a></li>
        <li><a href="admin/">ブログの管理</a></li>
      </ul>
    </nav>
  </footer>
</div>

</body>
</html>
