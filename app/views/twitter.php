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
 * @param stdClass $user Twitterユーザー情報。
 * @param stdClass $timeline Twitterタイムライン。
 */

// ※ ウィジェットを使った方が便利だが、勉強もかねてAPIから取る。
?>
<?php if (!empty($user)): ?>
<section class="twitter">
  <h2>Twitter</h2>
  <p class="userinfo">Tweets by <a href="<?= preg_replace('|\{\$screen_name\}|', urlencode($user->screen_name), Twitter::TWITTER_USER_URL) ?>">
    @<?= htmlspecialchars($user->name, ENT_HTML5, APP_CHARSET) ?>
  </a></p>
  <?php foreach ($timeline as $tweet): ?>
    <hr>
    <div class="tweet">
      <p class="text"><?= nl2br(htmlspecialchars($tweet->text, ENT_HTML5, APP_CHARSET), false) ?></p>
      <span class="date">at <a href="<?= preg_replace(['|\{\$screen_name\}|', '|\{\$id_str\}|'], [urlencode($user->screen_name), $tweet->id_str], Twitter::TWITTER_TWEET_URL) ?>">
        <?= date_create($tweet->created_at)->format('Y-m-d H:i:s') ?>
      </a></span>
    </div>
  <?php endforeach; ?>
</section>
<?php endif; ?>
