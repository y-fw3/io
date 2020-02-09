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

namespace fw3\io\rdbms\dbi\drivers\traits\properties\names;

/**
 * 接続名プロパティ特性
 */
trait NamePropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ?string 接続名
     */
    protected $name = null;

    //==============================================
    // property access
    //==============================================
    /**
     * 接続名を取得・設定します。
     *
     * @param   null|string|NameProperty    $name   接続名
     * @return  null|string|NameProperty    接続名またはこのインスタンス
     */
    public function name($name = null)
    {
        if (is_null($name) && func_num_args() === 0) {
            return $this->name;
        }

        $this->name = $name;
        return $this;
    }
}
