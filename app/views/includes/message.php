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
 * @param string $message 通知メッセージ。
 */
?><?php if (!empty($message)): ?>
  <p class="message"><?= htmlspecialchars($message, ENT_HTML5, APP_CHARSET) ?></p>
<?php endif; ?>