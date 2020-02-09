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

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\querys;

use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;

/**
 * SubQueryファクトリ
 */
abstract class SubQueryFactory
{
    //==============================================
    // facade
    //==============================================
    /**
     * SubQueryを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  SubQuery    SubQuery
     */
    public static function factory(...$arguments): SubQuery
    {
        return SubQuery::factory(...$arguments);
    }

    public static function query($query): SubQuery
    {
        return SubQuery::factory()->query($query);
    }
}
