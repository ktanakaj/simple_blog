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
?>
<h1>ブログの設定</h1>

<?= $this->Form->create($blog) ?>
  <fieldset class="form-group">
    <?= $this->Form->input('title', ['label' => 'ブログタイトル']); ?>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('mail_address', ['label' => 'メールアドレス']); ?>
    <small>※ 一意な値であればメールアドレス以外でも指定可能です。</small>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('password', ['label' => 'パスワード']); ?>
    <small>※ 変更する場合のみ値を設定してください。</small>
  </fieldset>

  <fieldset class="form-group">
    <label for="password2">パスワード再入力</label>
    <input type="password" name="password_raw2" id="password2" value="<?= h($blog->password_raw2) ?>" maxlength="100">
  </fieldset>

  <?= $this->Form->button('設定', ['class' => 'btn btn-primary btn-lg']) ?>
<?= $this->Form->end() ?>
