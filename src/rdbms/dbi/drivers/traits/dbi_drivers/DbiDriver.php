<?php
/**    _______       _______
 *    / ____/ |     / /__  /
 *   / /_   | | /| / / /_ <
 *  / __/   | |/ |/ /___/ /
 * /_/      |__/|__//____/
 *
 * Flywheel3: the inertia php framework
 *
 * @category    Flywheel3
 * @package     io
 * @author      wakaba <wakabadou@gmail.com>
 * @copyright   2017 - Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/)
 * @license     http://opensource.org/licenses/MIT The MIT License MIT
 * @varsion     0.0.1
 */

declare(strict_types = 1);

namespace fw3\io\rdbms\dbi\drivers\traits\dbi_drivers;

use fw3\io\rdbms\dbi\drivers\traits\properties\encodings\EncodingProperty;
use fw3\io\rdbms\dbi\drivers\traits\properties\names\NameProperty;

/**
 * DBIドライバ特性インターフェース
 */
interface DbiDriver extends
    EncodingProperty,
    NameProperty
{
    //==============================================
    // feature
    //==============================================
    /**
     * 文字列をエスケープして返します。
     *
     * @param   string  $string     エスケープする文字列
     * @param   string  $encoding   対象の文字列のエンコーディング
     * @return  string  エスケープされた文字列
     */
    public function escape($string, $encoding = null): string;
}
