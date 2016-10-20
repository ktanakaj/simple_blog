<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage views
 * @version    0.1
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2014 Koichi Tanaka
 * 
 * @param string $title ブログタイトル。
 * @param string $body コンテンツ本体。
 * @param string $widget ウィジェット。
 */
?><!DOCTYPE html>
<html>
<head>
  <meta charset="<?= APP_CHARSET ?>">
  <title><?= htmlspecialchars($title, ENT_HTML5, APP_CHARSET) ?></title>
  <link rel="stylesheet" href="default.css">
</head>
<body>

<?php if (!empty($widget)): ?>
  <aside class="widget">
    <?= $widget ?>
  </aside>
<?php endif; ?>

<div class="layout">
  <header>
    <h1><?= htmlspecialchars($title, ENT_HTML5, APP_CHARSET) ?></h1>
  </header>

  <div class="container">
    <?= $body; ?>
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
