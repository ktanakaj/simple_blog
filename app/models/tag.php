<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage models
 * @version    0.1
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2014 Koichi Tanaka
 */

/**
 * ブログ記事のタグ情報を扱うモデルクラス。
 * 
 * DBの"TAGS"テーブルに相当する。
 * タグ名やIDの情報を持つ。
 * 
 * @package  SimpleBlog
 */
class Tag extends ModelBase
{
	/**
	 * コンテンツIDから紐づく全タグを取得する。
	 * @param mixed $contentId コンテンツID、複数指定してまとめて検索も可（IN句の限界まで可）。
	 * @return array タグの配列。
	 */
	public static function find($contentId) {
		$query = "SELECT * FROM TAGS WHERE CONTENT_ID";
		$data = [];
		if (is_array($contentId)) {
			// 配列で渡された場合はINで複数まとめて検索する
			$query .= " IN (";
			for ($i = 0; $i < count($contentId); $i++) {
				if ($i > 0) {
					$query .= ", ";
				}
				$query .= ":content_id$i";
				$data["content_id$i"] = (int) $contentId;
			}
			$query .= ")";
		} else {
			$query .= " = :content_id";
			$data = ['content_id' => (int) $contentId];
		}
		$query .= " ORDER BY CONTENT_ID, NAME";
		return parent::getModels($query, $data);
	}

	/**
	 * ブログIDからそのコンテンツと紐づく全タグを取得する。
	 * @param int $blogId ブログID
	 * @param boolean $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return array タグの配列。
	 */
	public static function findByBlogId($blogId, $public = true) {
		$query = "SELECT DISTINCT t.NAME FROM TAGS t JOIN CONTENTS c ON c.ID = t.CONTENT_ID"
				. " WHERE c.BLOG_ID = :blog_id";
		if ($public) {
			$query .= " AND c.DATE < NOW() AND c.VISIBLE = TRUE";
		}
		$query .= " ORDER BY t.NAME";
		return parent::getModels($query, ['blog_id' => (int) $blogId]);
	}

	/**
	 * タグ情報をDBに登録する。
	 * @return boolean 保存が成功した場合true、失敗した場合false。
	 */
	public function insert() {
		return parent::execute(
				"INSERT INTO TAGS (CONTENT_ID, NAME) VALUES (:content_id, :name)",
				[
					'content_id' => (int) $this->content_id,
					'name' => $this->name,
				]);
	}

	/**
	 * コンテンツに紐づくタグ情報を削除する。
	 * @param $contentId コンテンツID。
	 * @return boolean 削除が成功した場合true、失敗した場合false。
	 */
	public static function deleteByContentId($contentId) {
		return parent::execute(
				"DELETE FROM TAGS WHERE CONTENT_ID = :content_id",
				['content_id' => (int) $contentId]);
	}
}
