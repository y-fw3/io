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

declare(strict_types=1);

namespace fw3\io\rdbms\query_builder\drivers\general\traits\properties\aliases;

/**
 * 別名プロパティ特性
 */
trait AliasPropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ?string 別名
     */
    protected $alias    = null;

    //==============================================
    // property access
    //==============================================
    /**
     * 別名を取得・設定します。
     *
     * @param   null|string|AliasPropertyTrait  $alias  別名
     * @return  null|string|AliasPropertyTrait  別名またはこのインスタンス
     */
    public function alias($alias = null)
    {
        if (is_null($alias) && func_num_args() === 0) {
            return $this->alias;
        }

        $this->alias    = $alias;
        return $this;
    }
}
