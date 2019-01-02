<?php
/**
 * The base configurations of the WordPress.
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、言語、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// プロクシでIPが入るとSSLアクセス状態をセットする
if( isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']) ) {
    $_SERVER['HTTPS'] = 'on';
    $_ENV['HTTPS'] = 'on';
    $_SERVER['HTTP_HOST'] = 'www2.any-danceosaka.org';
    $_SERVER['SERVER_NAME'] = 'www2.any-danceosaka.org';
    $_ENV['HTTP_HOST'] = 'www2.any-danceosaka.org';
    $_ENV['SERVER_NAME'] = 'www2.any-danceosaka.org';
}

// ** MySQL 設定 - こちらの情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'anydance_main');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'anydance');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'qi6Ug9Bnqmc');

/** MySQL のホスト名 */
define('DB_HOST', 'mysql702.db.sakura.ne.jp');

/** データベースのテーブルを作成する際のデータベースのキャラクターセット */
define('DB_CHARSET', 'utf8mb4');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '3|J{TnRMNC7Ooi$Cm:AH0U57[+nut-2{$eCXT<wtN,rBvdLL6ik<Q !;9>h uSd[');
define('SECURE_AUTH_KEY',  ']nsgp?F#+h[DAt!4[(V;>64_Ts,~hUh3F=;dEWJqNBOzCZS9V 4nLjvrE{rA+]G#');
define('LOGGED_IN_KEY',    'I8@k;u9msW:BfJOVw.P/,uP8P!yk45kKwZcTr;hs:mcr `)cK7~6O[%SImiBr-/$');
define('NONCE_KEY',        'sLh9Y}`chN_3^Y`]=T+5!9>1?33oaC/`GxN0.ZlF5Sq?]24~b$,p_ tHv+#z7BIm');
define('AUTH_SALT',        'HcP=s{K5xL? jz^W*{wypo8C=)ppZI@cx~4fk6B%U]L8{E>Xt+55GY|1>ds>#-;%');
define('SECURE_AUTH_SALT', 'Q0gY=W!OXmiiTK!+Xk.2D:!UM`QT}kpoT.Ai^:~b.@!>`O9}kZ<9&>;SEN$nSz[Y');
define('LOGGED_IN_SALT',   ',nV6&8LQfN/V%fRMX1Hn;Ajvej4[z1&&HWJ,75{q/{Pk^(UAvl A<UVb[c&?~IJq');
define('NONCE_SALT',       '.>MCB9n.4eELl6s9yZ)[gP)y+8-2,|v<x!&Q/CPNL1vZNIlBVh6~eS&}~U,ljb1y');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wa_';

/**
 * ローカル言語 - このパッケージでは初期値として 'ja' (日本語 UTF-8) が設定されています。
 *
 * WordPress のローカル言語を設定します。設定した言語に対応する MO ファイルが
 * wp-content/languages にインストールされている必要があります。例えば de_DE.mo を
 * wp-content/languages にインストールし WPLANG を 'de_DE' に設定することでドイツ語がサポートされます。
 */
define('WPLANG', 'ja');

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
