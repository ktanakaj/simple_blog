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
 * @param string $body コンテンツ本体。
 */
?><!DOCTYPE html>
<html>
<head>
  <meta charset="<?= APP_CHARSET ?>">
  <title><?= htmlspecialchars(ADMIN_TITLE, ENT_HTML5, APP_CHARSET) ?></title>
  <link rel="stylesheet" href="default.css">
</head>
<body>

<header>
  <h1><?= htmlspecialchars(ADMIN_TITLE, ENT_HTML5, APP_CHARSET) ?></h1>
</header>

<div class="container">
  <?= $body; ?>
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
