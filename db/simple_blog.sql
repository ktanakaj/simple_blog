SET SESSION FOREIGN_KEY_CHECKS=0;

/* Drop Indexes */

DROP INDEX tags_idx1 ON tags;
DROP INDEX contents_idx1 ON contents;



/* Drop Tables */

DROP TABLE oauth;
DROP TABLE tags;
DROP TABLE contents;
DROP TABLE blogs;




/* Create Tables */

-- ブログ全体の情報を扱うテーブル
CREATE TABLE blogs
(
	-- 各ブログを一意に識別するID
	id INT NOT NULL AUTO_INCREMENT COMMENT 'ブログID',
	-- ブログの名称／タイトル
	title VARCHAR(255) NOT NULL UNIQUE COMMENT 'ブログタイトル',
	-- ブログ所有者の認証に用いるメールアドレス。便宜上メールアドレスとしているが、一意な値であれば何でも構わない（例: 'admin'）
	mail_address VARCHAR(255) NOT NULL UNIQUE COMMENT 'メールアドレス',
	-- ブログ所有者の認証に用いるパスワード。ハッシュ等を格納する。
	password VARCHAR(255) NOT NULL COMMENT 'パスワード',
	-- ブログ所有者が最後に認証した日時
	last_login DATETIME COMMENT '最終ログイン日時',
	PRIMARY KEY (id)
) COMMENT = 'ブログ';


-- ブログの各記事に付けるタグ情報を扱うテーブル
CREATE TABLE tags
(
	-- ブログの各記事を一意に識別するID
	content_id INT NOT NULL COMMENT 'コンテンツID',
	-- ブログの各記事に付けるタグの名称。
	name VARCHAR(100) NOT NULL COMMENT 'タグ名',
	PRIMARY KEY (content_id, name)
) COMMENT = 'タグ';


-- ブログの各記事の情報を扱うテーブル
CREATE TABLE contents
(
	-- ブログの各記事を一意に識別するID
	id INT NOT NULL AUTO_INCREMENT COMMENT 'コンテンツID',
	-- 各ブログを一意に識別するID
	blog_id INT NOT NULL COMMENT 'ブログID',
	-- ブログの各記事のタイトル
	title VARCHAR(255) NOT NULL COMMENT 'コンテンツタイトル',
	-- 各記事の一覧表示用の概要
	summary VARCHAR(1000) NOT NULL COMMENT '概要',
	-- 各記事の投稿日時
	date DATETIME NOT NULL COMMENT '投稿日時',
	-- 記事を公開する場合TRUE
	visible BOOLEAN NOT NULL COMMENT '表示フラグ',
	-- 各記事の本文
	text TEXT NOT NULL COMMENT '本文',
	PRIMARY KEY (id)
) COMMENT = 'ブログコンテンツ';


-- OAUTH認証情報を扱うテーブル
CREATE TABLE oauth
(
	-- 各ブログを一意に識別するID
	blog_id INT NOT NULL COMMENT 'ブログID',
	-- OAUTH認証の接続先を表す種別
	type ENUM('TWITTER') NOT NULL COMMENT '接続先種別',
	-- OAUTH認証に用いるアクセストークン
	access_token VARCHAR(100) NOT NULL COMMENT 'アクセストークン',
	-- OAUTH認証に用いるアクセスシークレット
	access_secret VARCHAR(100) NOT NULL COMMENT 'アクセスシークレット',
	PRIMARY KEY (blog_id, type)
) COMMENT = 'OAUTH認証情報';



/* Create Foreign Keys */

ALTER TABLE oauth
	ADD FOREIGN KEY (blog_id)
	REFERENCES BLOGS (id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE contents
	ADD FOREIGN KEY (blog_id)
	REFERENCES BLOGS (id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;


ALTER TABLE tags
	ADD FOREIGN KEY (content_id)
	REFERENCES CONTENTS (id)
	ON UPDATE CASCADE
	ON DELETE CASCADE
;



/* Create Indexes */

-- タグでの検索用インデックス
CREATE INDEX tags_idx1 ON tags (name ASC);
-- 投稿日時ソート用のインデックス
CREATE INDEX contents_idx1 ON contents (blog_id ASC, date DESC);



