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
 * ブログの各記事を扱うモデルクラス。
 * 
 * DBの"CONTENTS"テーブルに相当する。
 * 記事名や投稿日時、本文といった情報を持つ。
 * 
 * @package  SimpleBlog
 */
class Content extends ModelBase
{
	/**
	 * コンテンツIDからコンテンツを取得する。
	 * @param int $id コンテンツID。
	 * @param int $blogId ブログID、null以外の場合はブログIDの一致もチェックする（権限チェック用）。
	 * @param boolean $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return mixed コンテンツ、取得失敗時はfalse。
	 */
	public static function findById($id, $blogId = null, $public = true) {
		$query = "SELECT * FROM CONTENTS WHERE ID = :id";
		$data = ['id' => (int) $id];

		if (!is_null($blogId)) {
			// ブログIDの指定がある場合、一致しない場合は無しと判定する
			$query .= " AND BLOG_ID = :blog_id";
			$data['blog_id'] = $blogId;
		}

		// 表示対象のみの場合、投稿日時が過去で表示対象のものだけに限定
		self::addWhereForPublic($query, $public);

		$content = parent::getModel($query, $data);
		if (!$content) {
			return $content;
		}

		// タグ情報も読み込んでから返す
		$content->loadTags();
		return $content;
	}

	/**
	 * ブログIDからそのコンテンツを取得する。
	 * @param int $blogId ブログID。
	 * @param int $page ページ番号、未指定時は全て。デフォルトは未指定。
	 * @param int $pageMax 1ページ当たりの表示件数。
	 * @param string $tag タグによる絞り込みを行う場合のタグ名。
	 * @param boolean $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return array ブログ記事の配列、本文情報は含まない。
	 */
	public static function find($blogId, $page = null, $pageMax = 10, $tag = null, $public = true) {
		$query = "SELECT c.ID, c.BLOG_ID, c.TITLE, c.SUMMARY, c.DATE, c.VISIBLE FROM CONTENTS c";
		$where = " WHERE c.BLOG_ID = :blog_id";
		$data = ['blog_id' => (int) $blogId];

		// タグによる絞り込み
		if (!is_null($tag) && $tag !== '') {
			$query .= " JOIN TAGS t ON t.CONTENT_ID = c.ID";
			$where .= " AND t.NAME = :tag";
			$data['tag'] = $tag;
		}

		$query .= $where;

		// 表示対象のみの場合、投稿日時が過去で表示対象のものだけに限定
		self::addWhereForPublic($query, $public);

		$query .= " ORDER BY c.DATE DESC";

		if (!empty($page) && $page > 0) {
			// ページ番号の指定がある場合、ページングする
			$query .= " LIMIT :limit OFFSET :offset";
			$data['limit'] = $pageMax;
			$data['offset'] = ($page - 1) * $pageMax;
		}

		$contents = parent::getModels($query, $data);
		if (!$contents) {
			return $contents;
		}

		// タグ情報も読み込んでから返す
		// ※ まとめて検索した方が効率がよいが、とりあえず実装が楽な形にしておく
		foreach ($contents as $content) {
			$content->loadTags();
		}
		return $contents;
	}

	/**
	 * コンテンツの登録件数を返す。
	 * @param int $blogId ブログID。
	 * @param string $tag タグによる絞り込みを行う場合のタグ名。
	 * @param boolean $public 表示対象コンテンツのみ対象とする場合はtrue。デフォルトtrue。
	 * @return int 登録件数。
	 */
	public static function count($blogId, $tag = null, $public = true) {
		$query = "SELECT COUNT(*) AS CNT FROM CONTENTS c";
		$where = " WHERE c.BLOG_ID = :blog_id";
		$data = ['blog_id' => (int) $blogId];

		// タグによる絞り込み
		if (!is_null($tag) && $tag !== '') {
			$query .= " JOIN TAGS t ON t.CONTENT_ID = c.ID";
			$where .= " AND t.NAME = :tag";
			$data['tag'] = $tag;
		}

		$query .= $where;
		self::addWhereForPublic($query, $public);
		$row = parent::getRow($query, $data);
		return $row['cnt'];
	}

	/**
	 * 表示対象コンテンツのみに絞り込むためのWHERE句を追加する。
	 * @param string $query WHERE句を追加するSQL文。参照渡しのためこのSQL文に追加される。
	 * @param boolean $public 表示対象コンテンツのみ対象とする場合はtrue。
	 * @see Content::isPublic
	 */
	private static function addWhereForPublic(&$query, $public) {
		if ($public) {
			if (stristr($query, 'WHERE') !== FALSE) {
				$query .= " AND";
			} else {
				$query .= " WHERE";
			}
			$query .= " DATE < NOW() AND VISIBLE = TRUE ";
		}
	}

	/**
	 * コンテンツをDBに保存する。
	 *
	 * 保存前にはバリデートを行う。
	 * コンテンツIDが設定されている場合はINSERTを、
	 * 設定されていない場合はUPDATEを実行する。
	 * @return boolean 保存が成功した場合true、失敗した場合false。
	 */
	public function save() {
		if (!$this->validate()) {
			return false;
		}

		// コンテンツとタグ情報を更新するためトランザクションする
		self::db()->beginTransaction();
		try {
			$result = false;
			if (empty($this->id)) {
				$result = $this->insert();
			} else {
				$result = $this->update();
			}

			// タグ情報の更新
			if (!$result || !$this->saveTags()) {
				self::db()->rollback();
				return false;
			}

			self::db()->commit();
			return true;
		} catch (Exception $e) {
			self::db()->rollback();
			throw $e;
		}
	}

	/**
	 * 有効なコンテンツかのチェックを行う。
	 * @return boolean 問題ない場合はtrue、不可の場合はfalse。
	 */
	protected function validate() {
		$this->errors = [];
		$this->addErrorIfNotNumeric('blog_id', 'ブログID');
		$this->addErrorIfBlank('title', 'タイトル');
		$this->addErrorIfBlank('text', '本文');
		$this->addErrorIfBlank('summary', '概要');
		$this->addErrorIfNotDate('date', '投稿日時');
		if (!empty($this->id)) {
			// 投稿済み記事が違うブログIDに変わるのも不可
			// ※ アプリ上はできないがパラメータ的には渡せる
			$diff = static::findById($this->id);
			if ($diff && $diff->blog_id !== $this->blog_id) {
				$this->errors[] = '想定外のIDが指定されました。処理をやり直してください。';
			}
		}
		return empty($this->errors);
	}

	/**
	 * コンテンツをDBに登録する。
	 * @return boolean 保存が成功した場合true、失敗した場合false。
	 */
	protected function insert() {
		return parent::executeAndGetId(
				"INSERT INTO CONTENTS (BLOG_ID, TITLE, SUMMARY, DATE, VISIBLE, TEXT)"
				. " VALUES (:blog_id, :title, :summary, CAST(:date AS DATETIME), :visible, :text)",
				[
					'blog_id' => (int) $this->blog_id,
					'title' => $this->title,
					'summary' => $this->summary,
					'date' => $this->date,
					'visible' => (bool) $this->visible,
					'text' => $this->text,
				]);
	}

	/**
	 * コンテンツをDBに上書きする。
	 * @return boolean 保存が成功した場合true、失敗した場合false。
	 */
	protected function update() {
		return parent::execute(
				"UPDATE CONTENTS SET"
				. " BLOG_ID = :blog_id,"
				. " TITLE = :title,"
				. " SUMMARY = :summary,"
				. " DATE = CAST(:date AS DATETIME),"
				. " VISIBLE = :visible,"
				. " TEXT = :text"
				. " WHERE ID = :id",
				[
					'blog_id' => (int) $this->blog_id,
					'title' => $this->title,
					'summary' => $this->summary,
					'date' => $this->date,
					'visible' => $this->visible,
					'text' => $this->text,
					'id' => (int) $this->id,
				]);
	}

	/**
	 * タグ情報をDBに登録する。
	 * 
	 * $this->tag の文字列から登録する。
	 * 登録時には$this->tagsにタグ情報を配列の形でコピーする。
	 * @return boolean 更新が成功した場合true、失敗した場合false。
	 */
	protected function saveTags() {
		// 一度全て消してから再度登録
		Tag::deleteByContentId($this->id);
		$this->tags = [];
		if (is_null($this->tag) || trim($this->tag) === '') {
			return true;
		}

		// スペース区切りの文字列として渡されるため、分解して登録する
		// ※ もし同じタグが複数指定されている場合マージする
		$this->tags = array_unique(preg_split("/\s+/", $this->tag));
		foreach ($this->tags as $name) {
			$tag = new Tag();
			$tag->content_id = $this->id;
			$tag->name = $name;
			if (!$tag->insert()) {
				return false;
			}
		}
		return true;
	}

	/**
	 * コンテンツをDBから削除する。
	 * @return boolean 削除が成功した場合true、失敗した場合false。
	 */
	public function remove() {
		return parent::execute(
				"DELETE FROM CONTENTS WHERE ID = :id",
				['id' => (int) $this->id]);
	}

	/**
	 * コンテンツの投稿日時に現在時刻を設定する。
	 * @return void
	 */
	public function setNow() {
		$this->date = date('Y-m-d H:i:s');
	}

	/**
	 * コンテンツは表示対象か？
	 * @return boolean 表示対象の場合true、それ以外はfalse。
	 * @see Content::addWhereForPublic
	 */
	public function isPublic() {
		// addWhereForPublicと同じ条件のPHP版。必要な個所があったので
		try {
			$date = new DateTime($this->date);
		} catch (Exception $e) {
			return false;
		}
		$now = new DateTime();
		return $this->visible && $date->getTimestamp() <= $now->getTimestamp();
	}

	/**
	 * コンテンツのタグを読み込む。
	 * 
	 * 読み込んだタグは $this->tag, $this->tags に格納される。
	 * @return void
	 */
	protected function loadTags() {
		$this->tag = '';
		$this->tags = [];
		if (empty($this->id)) {
			return;
		}

		// ※ 直接$this->tagsに代入していないのは、オーバーロードプロパティだと怒られたため
		$tags = [];
		$tagObjs = Tag::find($this->id);
		if ($tagObjs) {
			foreach ($tagObjs as $tag) {
				$tags[] = $tag->name;
			}
		}
		$this->tags = $tags;
		$this->tag = implode(' ', $this->tags);
	}
}
