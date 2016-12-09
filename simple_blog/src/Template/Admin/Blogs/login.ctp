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
<h1>管理画面ログイン</h1>
<?= $this->Form->create() ?>
  <fieldset class="form-group">
    <?= $this->Form->input('mail_address', ['label' => 'メールアドレス']) ?>
  </fieldset>
  <fieldset class="form-group">
    <?= $this->Form->input('password', ['label' => 'パスワード']) ?>
  </fieldset>
  <?= $this->Form->button('ログイン') ?>
<?= $this->Form->end() ?>