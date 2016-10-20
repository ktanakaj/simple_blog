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
 * @param array $errors エラーメッセージ配列。
 */
?><?php if (!empty($errors)): ?>
  <ul class="errors">
    <?php foreach ($errors as $error): ?>
      <li><?= htmlspecialchars($error, ENT_HTML5, APP_CHARSET) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>