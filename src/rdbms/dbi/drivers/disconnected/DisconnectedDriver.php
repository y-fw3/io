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

namespace fw3\io\rdbms\dbi\drivers\disconnected;

use fw3\io\rdbms\dbi\drivers\traits\dbi_drivers\DbiDriver;
use fw3\io\rdbms\dbi\drivers\traits\dbi_drivers\DbiDriverTrait;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * 未接続時に使用するDBI
 */
class DisconnectedDriver implements
    DbiDriver
{
    use DbiDriverTrait;

    //==============================================
    // const
    //==============================================
    /**
     * @var array   デフォルトでの文字エンコーディング検出順序
     */
    public const DETECT_ORDER_DEFAULT   = [
        'eucJP-win',
        'SJIS-win',
        'JIS',
        'ISO-2022-JP',
        'UTF-8',
        'ASCII',
    ];

    protected function __construct()
    {
    }

    public static function factory($spec)
    {
        $spec = static::generateSpec($spec);

        $instance   = new static();
        $instance->name($spec->name());
        return $instance;
    }

    //==============================================
    // feature
    //==============================================
    public static function generateSpec($spec)
    {
        return DisconnectedSpec::factory()->fromArray($spec);
    }

    /**
     * 文字列をエスケープして返します。
     *
     * @param   string  $string     エスケープする文字列
     * @param   string  $encoding   対象の文字列のエンコーディング
     * @return  string  エスケープされた文字列
     */
    public function escape($string, $encoding = null): string
    {
        $encoding = $encoding ?? $this->encoding;
        if (!mb_check_encoding($string, $encoding)) {
            $string_encoding = mb_detect_encoding($string, static::DETECT_ORDER_DEFAULT);
            if ($string_encoding === false) {
                UnavailableVarException::raise('使用できないエンコーディングの文字列です。エンコーディングの検出に失敗しているため、base64encodeした値を出力します。', ['string' => base64encode($string), 'encoding' => $encoding]);
            }
            UnavailableVarException::raise('使用できないエンコーディングの文字列です。', ['string' => mb_convert_encoding($string, $encoding, $string_encoding), 'test_encoding' => $string_encoding, 'encoding' => $encoding]);
        }

        return str_replace('"', '""', $string);
    }
}
