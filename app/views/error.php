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
 * @param string $subject エラーサブジェクト。
 * @param string $message エラーメッセージ。
 */
?><!DOCTYPE html>
<html>
<head>
  <meta charset="<?= APP_CHARSET ?>">
  <title><?= htmlspecialchars(APP_NAME, ENT_HTML5, APP_CHARSET) ?></title>
  <link rel="stylesheet" href="default.css">
</head>
<body>

<section class="error">
  <?php if (!empty($subject)): ?>
    <h1><?= htmlspecialchars($subject, ENT_HTML5, APP_CHARSET) ?></h1>
  <?php endif; ?>
  <?php require 'includes/message.php'; ?>
</section>

<footer>
  <hr>
  <nav>
    <ul>
      <li><a href="<?= BLOG_ROOT ?>">ブログトップへ</a></li>
      <li><a href="javascript:history.back();">戻る</a></li>
    </ul>
  </nav>
</footer>

</body>
</html>
