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
 * @param Blog $blog ブログ情報。
 * @param string $message 通知メッセージ。
 */
?>
<h2><?= empty($blog->id) ? 'ブログの初期設定' : 'ブログの設定' ?></h2>

<?php require 'includes/message.php'; ?>
<?php $errors = $blog->errors; ?>
<?php require 'includes/errors.php'; ?>

<form method="post" id="config">
  <div class="param">
    <label for="title">ブログタイトル</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($blog->title, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100" required>
  </div>

  <div class="param">
    <label for="mail_address">メールアドレス</label>
    <input type="text" name="mail_address" id="mail_address" value="<?= htmlspecialchars($blog->mail_address, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100" required>
    <small>※ 一意な値であればメールアドレス以外でも指定可能です。</small>
  </div>

  <div class="param">
    <label for="password">パスワード</label>
    <input type="password" name="password_raw" id="password" value="<?= htmlspecialchars($blog->password_raw, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100" <?= empty($blog->id) ? 'required' : '' ?>>
    <?php if (!empty($blog->id)): ?>
      <small>※ 変更する場合のみ値を設定してください。</small>
    <?php endif; ?>
  </div>

  <div class="param">
    <label for="password2">パスワード再入力</label>
    <input type="password" name="password_raw2" id="password2" value="<?= htmlspecialchars($blog->password_raw2, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100" <?= empty($blog->id) ? 'required' : '' ?>>
  </div>

  <div class="submit">
    <input type="submit" value="設定">
  </div>
</form>
