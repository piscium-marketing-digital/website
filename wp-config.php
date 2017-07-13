<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa user o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações
// com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'piscium');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** Nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Charset do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'YDsi}|-. 27J}5En4WRSRD?nFzy^%r.v5wl;/CvK+jMJca}a;!7V$*nB8t~*T(Hz');
define('SECURE_AUTH_KEY',  '`bVX>XXm.6HrHE1hve#JsiTyyB}a@YO@fd*oWu6:K,e*i6sqC~7O^X=s$r$U*$n.');
define('LOGGED_IN_KEY',    '{ip@#N8k~2AAkf3T[Sbk1:Y_s&x-2?IVMK$jx|sDFw7qaq[|K;%f9+8wa6C^S_Q4');
define('NONCE_KEY',        '!4W8r$aH/MOrxL]Bv$GR`/faE_B2a44^@r&9ANe0kifQc{SgDX{1l{-v+Gc%F:dk');
define('AUTH_SALT',        ',PWSPE;B95j[{%;#CSkIibO1y2DO4Q9!6]d(<w>*B8dgwulkW;V4DKmB%WK:OKtY');
define('SECURE_AUTH_SALT', 'kdsGJPj(@a<NoK2nwxl5VHH;MBH<>}}(&V3HmnnxB14Ymcp}YeUnt|tz%<`vB@Sz');
define('LOGGED_IN_SALT',   '<eS7={m~Mf_ro^*asQS{S22a?u}{_FF[JEz1V8;YoO5`zP)4^[h[ZwZR}#6ZizL8');
define('NONCE_SALT',       'g0u{#8Yw-AcGjNfi:`Cj5].}/uVEIP0ff{ 6O,j#j%S)ZeK3#(T)pFlLfQt80dxs');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * para cada um um único prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
