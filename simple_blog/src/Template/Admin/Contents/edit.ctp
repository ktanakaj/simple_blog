<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @version    0.3
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 *
 * @param Content $content ブログ記事。
 */
?>
<h1>記事の編集</h1>

<?= $this->Form->create($content) ?>
  <fieldset class="form-group">
    <?= $this->Form->input('title', ['label' => 'タイトル']); ?>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('text', ['label' => '本文']); ?>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('summary', ['label' => '概要']); ?>
    <input type='button' class="btn" value="本文をコピー" onclick="this.form.summary.value = this.form.text.value.substring(0, 200)">
    <small>※ 一覧に表示される概要です。</small>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('date', ['label' => '投稿日時']); ?>
    <input type="checkbox" name="date_now" id="date_now" value="1" onclick="this.form.date.disabled = this.checked" <?= !empty($content->date_now) ? 'checked' : '' ?>>
    <label for="date_now">現在日時を使用</label>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('tag', ['label' => 'タグ']); ?>
    <small>※ スペース区切りで複数指定できます。</small>
  </fieldset>

  <fieldset class="form-group">
    <?= $this->Form->input('visible'); ?>
  </fieldset>

  <?= $this->Form->button('投稿', ['class' => 'btn btn-primary btn-lg']) ?>
<?= $this->Form->end() ?>
