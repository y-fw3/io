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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables;

use fw3\io\rdbms\query_builder\drivers\general\factorys\querys\SubQueryFactory;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;

/**
 * サブクエリ化特性
 */
trait SubQueriableTrait
{
    /**
     * 強制的にサブクエリとして展開されるインスタンスを返します。
     *
     * @return  SubQuery    サブクエリ
     */
    public function convertSubQuery(): SubQuery
    {
        return SubQueryFactory::query($this);
    }
}
