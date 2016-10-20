<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage controllers
 * @version    0.1
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2014 Koichi Tanaka
 */
require_once(dirname(__FILE__) . '/controllerbase.php');

/** 1ページの表示件数。 */
define('PAGE_MAX', 5);

// ブログ情報の取得
$blog = Blog::findAtHead();
if (!$blog) {
	$blog = new Blog();
	$blog->title = APP_NAME;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	// 各記事のIDが指定されている場合、個別記事を表示
	$content = Content::findById($_GET['id']);
	if (!$content) {
		headerForNotFound();
	}
	$body = render('content', ['blog' => $blog, 'content' => $content]);
} else {
	// 記事の指定がない場合は一覧を表示
	$page = getPage();
	$tag = null;
	if (isset($_GET['tag'])) {
		$tag = $_GET['tag'];
	}
	$contents = $blog->contents($page, PAGE_MAX, $tag);
	if (($page != 1 || !is_null($tag)) && empty($contents)) {
		// 存在しないページの場合は404
		headerForNotFound();
	}
	$body = render('index', ['blog' => $blog, 'contents' => $contents, 'page' => $page, 'last' => ceil($blog->countContents($tag) / PAGE_MAX)]);
}

// Twiiter連携がONの場合、ウィジェットとしてTwitterのタイムラインを出力
$widget = '';
$twitter = $blog->twitterAuth();
if ($twitter) {
	$timeline = $twitter->timeline();
	if (!empty($timeline)) {
		$widget = render('twitter', ['user' => $twitter->userFromTimeline($timeline), 'timeline' => $timeline]);
	}
}

// タグ情報のウィジェットも出力
$widget .= render('tag', ['tags' => Tag::findByBlogId($blog->id)]);

echo render('template', ['body' => $body, 'title' => $blog->title, 'widget' => $widget]);
