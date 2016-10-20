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
 * @param Content $content ブログ記事。
 * @param string $message 通知メッセージ。
 * @param Twitter $twitter Twitter情報。
 */
?>
<h2>記事の投稿／編集</h2>

<?php require 'includes/message.php'; ?>
<?php $errors = $content->errors; ?>
<?php require 'includes/errors.php'; ?>

<form method="post" id="content">
  <div class="param">
    <label for="title">タイトル</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($content->title, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100" required>
  </div>

  <div class="param">
    <label for="text">本文</label>
    <textarea name="text" id="text" maxlength="20000" required><?= htmlspecialchars($content->text, ENT_HTML5, APP_CHARSET) ?></textarea>
  </div>

  <div class="param">
    <label for="summary">概要</label>
    <textarea name="summary" id="summary" maxlength="300" required><?= htmlspecialchars($content->summary, ENT_HTML5, APP_CHARSET) ?></textarea>
    <input type='button' value="本文をコピー" onclick="this.form.summary.value = this.form.text.value.substring(0, 300)">
    <small>※ 一覧に表示される概要です。</small>
  </div>

  <div class="param">
    <label for="date">投稿日時</label>
    <input type="text" name="date" id="date" value="<?= htmlspecialchars($content->date, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" <?= !empty($content->date_now) ? 'disabled' : '' ?>>
    <input type="checkbox" name="date_now" id="date_now" value="1" onclick="this.form.date.disabled = this.checked" <?= !empty($content->date_now) ? 'checked' : '' ?>>
    <label for="date_now">現在日時を使用</label>
  </div>

  <div class="param">
    <label for="tag">タグ</label>
    <input type="text" name="tag" id="tag" value="<?= htmlspecialchars($content->tag, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>" maxlength="100">
    <small>※ スペース区切りで複数指定できます。</small>
  </div>

  <div class="param">
    <input type="radio" name="visible" id="visible_true" value="1" <?= $content->visible ? 'checked' : '' ?>>
    <label for="visible_true">表示</label>
    <input type="radio" name="visible" id="visible_false" value="0" <?= !$content->visible ? 'checked' : '' ?>>
    <label for="visible_false">非表示</label>
  </div>

  <?php if (!empty($twitter)): ?>
    <div class="param">
      <input type="checkbox" name="twitter" id="twitter" value="1" <?= !empty($content->twitter) ? 'checked' : '' ?>>
      <label for="twitter">Twitterに投稿を通知する。</label>
      <small>※ 投稿日時が未来、非表示の場合は通知されません。</small>
    </div>
  <?php endif; ?>

  <div class="submit">
    <input type="hidden" name="id" value="<?= htmlspecialchars($content->id, ENT_COMPAT | ENT_HTML5, APP_CHARSET) ?>">
    <input type="submit" value="投稿">
  </div>
</form>
