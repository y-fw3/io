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

namespace fw3\io\rdbms\dbi\drivers\traits\properties\encodings;

/**
 * エンコーディングプロパティ特性
 */
trait EncodingPropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ?string エンコーディング
     */
    protected $encoding = null;

    //==============================================
    // property access
    //==============================================
    /**
     * エンコーディングを取得・設定します。
     *
     * @param   null|string|EncodingProperty    $encoding   エンコーディング
     * @return  null|string|EncodingProperty    エンコーディングまたはこのインスタンス
     */
    public function encoding($encoding = null)
    {
        if (is_null($encoding) && func_num_args() === 0) {
            return $this->encoding;
        }

        $this->encoding = $encoding;
        return $this;
    }
}
