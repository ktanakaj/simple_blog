<?php
/**
 * 簡易ブログアプリ「Simple Blog」
 *
 * @package    SimpleBlog
 * @subpackage models
 * @version    0.2
 * @author     Koichi Tanaka
 * @copyright  Copyright © 2016 Koichi Tanaka
 */
require_once(dirname(__FILE__) . '/../config.php');

/**
 * モデルクラスの基盤を提供する抽象クラス。
 *
 * モデルはActive Recordパターン風の実装とする。
 * PDOは薄くしか隠蔽しない。
 *
 * @package  SimpleBlog
 */
abstract class ModelBase {

	/** バリデーションエラー時のエラー情報格納域。 */
	public $errors = [];

	/** DB接続。 */
	private static $_db;

	/** マジックプロパティ用の格納域。 */
	private $_data = [];

	/**
	 * PDOでのDB接続を取得する。
	 *
	 * 接続が開始されていない場合のみ接続を取得、
	 * それ以外は取得済みの接続を返す。
	 * 一度のリクエストでは常に同じ接続が返される。
	 *
	 * @return PDO PDOのDB接続。
	 */
	protected static function db() : PDO {
		$retry = 3;
		while (is_null(self::$_db)) {
			// DBに接続。持続的接続でかつエラー時は例外を投げる
			try {
				self::$_db = new PDO(DATA_SOURCE_NAME, DB_USER, DB_PASSWORD, [
						PDO::ATTR_PERSISTENT => true,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_CASE => PDO::CASE_LOWER,
				]);
			} catch (PDOException $e) {
				// ※ 原因不明だが、稀に接続に失敗することがあるようなので、失敗した場合時間を置きリトライする
				if (--$retry < 0) {
					throw $e;
				}
				error_log(APP_NAME . ': Modelbase::db() Connecting to DB is retried.');
				sleep(1);
			}
		}
		return self::$_db;
	}

	/**
	 * データを取得するためのSELECT文を実行し、1行を取得する。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return array 検索結果を格納した配列、検索失敗時はnull。
	 */
	protected static function getRow(string $query, array $data = []) : ?array {
		return self::select($query, $data, false, true);
	}

	/**
	 * データを取得するためのSELECT文を実行し、全行を取得する。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return array 検索結果を格納した配列の配列、検索失敗時はnull。
	 */
	protected static function getRows(string $query, array $data = []) : ?array {
		return self::select($query, $data, true, true);
	}

	/**
	 * モデルクラスを取得するためのSELECT文を実行し、1行を取得する。
	 * 
	 * 各々の実装クラスで、各クラス自身のオブジェクトを取得するために使用する。
	 * 
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return ModelBase 検索結果を格納したモデルクラスのオブジェクト、検索失敗時はnull。
	 */
	protected static function getModel(string $query, array $data = []) : ?ModelBase {
		return self::select($query, $data);
	}

	/**
	 * モデルクラスを取得するためのSELECT文を実行し、全行を取得する。
	 * 
	 * 各々の実装クラスで、各クラス自身のオブジェクトを取得するために使用する。
	 * 
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return array 検索結果を格納したモデルクラスのオブジェクトの配列、検索失敗時はnull。
	 */
	protected static function getModels(string $query, array $data = []) : ?array {
		return self::select($query, $data, true);
	}

	/**
	 * 指定されたSELECT文を実行し、結果を取得する。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @param bool $all 結果を全て取得する場合true、1件のみはfalse。デフォルトはfalse。
	 * @param bool $array 結果を配列で取得する場合true、実装クラスのオブジェクト型で取得する場合false。デフォルトはfalse。
	 * @return mixed 検索結果を格納した1行分のオブジェクトまたは配列、もしくはそれを格納した配列、検索失敗時はnull。
	 */
	private static function select(string $query, array $data, bool $all = false, bool $array = false) {
		$stmt = self::prepareAndBindValues($query, $data);
		if (!$stmt->execute()) {
			return null;
		}
		if (!$array) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		}
		if ($all) {
			$result = $stmt->fetchAll();
			return $result !== FALSE ? $result : null;
		} else {
			$row = $stmt->fetch();
			$stmt->closeCursor();
			return $row !== FALSE ? $row : null;
		}
	}

	/**
	 * 指定されたSQL文を実行する。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return bool SQL成功時 true, 失敗時false。
	 */
	protected static function execute(string $query, array $data = []) : bool {
		return self::prepareAndBindValues($query, $data)->execute();
	}

	/**
	 * SQL文とパラメータを設定したプリペアドステートメントを返す。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @return PDOStatement パラメータを設定したプリペアドステートメント。
	 */
	private static function prepareAndBindValues(string $query, array $data) : PDOStatement {
		$stmt = self::db()->prepare($query);
		foreach ($data as $key => $value) {
			$dataType = PDO::PARAM_STR;
			if (is_null($value)) {
				$dataType = PDO::PARAM_NULL;
			} else if (is_int($value)) {
				$dataType = PDO::PARAM_INT;
			} else if (is_bool($value)) {
				$dataType = PDO::PARAM_BOOL;
			}
			$stmt->bindValue(':' . $key, $value, $dataType);
		}
		return $stmt;
	}

	/**
	 * 指定されたINSERT文を実行し、発行されたID値を取得する。
	 * @param string $query SQL文。パラメータは名前付けされたプレースホルダの形で指定する必要がある。
	 * @param array $data 名前付けプレースホルダ用のパラメータ。
	 * @param string $property ID値のプロパティ名。デフォルトは'id'。
	 * @return bool INSERT成功時true、失敗時false。
	 */
	protected function executeAndGetId(string $query, array $data = [], string $property = 'id') : bool {
		$result = self::execute($query, $data);
		if ($result) {
			$this->{$property} = self::db()->lastInsertId();
		}
		return $result;
	}

	/**
	 * マジックプロパティのセッター。
	 *
	 * セッターは特に何もせず、普通に値を格納する。
	 *
	 * @param string $property プロパティ名。
	 * @param mixed $value プロパティ値。
	 * @return void
	 */
	public function __set(string $property, $value) : void {
		$this->_data[$property] = $value;
	}

	/**
	 * マジックプロパティのゲッター。
	 *
	 * 設定されていないプロパティを指定した場合、nullを返す。
	 * （普通のプロパティだと警告が出る。）
	 *
	 * @param string $property プロパティ名。
	 * @return mixed プロパティ値、存在しないプロパティの場合null。
	 */
	public function __get(string $property) {
		if (isset($this->_data[$property])) {
			return $this->_data[$property];
		}
		return null;
	}

	/**
	 * マジックプロパティの設定有無確認。
	 * @param string $property プロパティ名。
	 * @return bool プロパティが存在する場合true, しない場合false。
	 */
	public function __isset(string $property) : bool {
		return isset($this->_data[$property]);
	}

	/**
	 * マジックプロパティの解除。
	 * @param string $property プロパティ名。
	 * @return void
	 */
	public function __unset(string $property) : void {
		unset($this->_data[$property]);
	}

	/**
	 * 指定されたプロパティが数値かをチェックし、数値でない場合エラー情報を登録。
	 *
	 * 空の場合も不可と判定する。
	 * @param string $property プロパティ名。
	 * @param string $name プロパティの表示名。
	 * @return bool 数値以外の場合true、数値はfalse。
	 */
	protected function addErrorIfNotNumeric(string $property, string $name = '') : bool {
		if (is_null($this->{$property}) || !is_numeric($this->{$property})) {
			$this->errors[] = ($name == '' ? $property : $name) . 'には数値を入力してください。';
			return true;
		}
		return false;
	}

	/**
	 * 指定されたプロパティが空白（null, '', スペースのみ）かをチェックし、空白の場合エラー情報を登録。
	 * @param string $property プロパティ名。
	 * @param string $name プロパティの表示名。
	 * @return bool 空白の場合true、それ以外はfalse。
	 */
	protected function addErrorIfBlank(string $property, string $name = '') : bool {
		if (is_null($this->{$property}) || trim($this->{$property}) === '') {
			$this->errors[] = ($name == '' ? $property : $name) . 'を入力してください。';
			return true;
		}
		return false;
	}

	/**
	 * 指定されたプロパティが日時に変換できないかをチェックし、できない場合エラー情報を登録。
	 *
	 * 空の場合も不可と判定する。
	 * @param string $property プロパティ名。
	 * @param string $name プロパティの表示名。
	 * @return bool 変換不可の場合true、可能な場合はfalse。
	 */
	protected function addErrorIfNotDate(string $property, string $name = '') : bool {
		if ($this->addErrorIfBlank($property, $name)) {
			return true;
		}
		try {
			// 日付型に変換できればOK。変換した値からDB取得時と同じフォーマットになるよう上書きする
			$date = new DateTime($this->{$property});
			$this->{$property} = $date->format('Y-m-d H:i:s');
		} catch (Exception $e) {
			$this->errors[] = ($name == '' ? $property : $name) . 'には日時を入力してください。';
			return true;
		}
		return false;
	}
}
