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

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\FromClause;

/**
 * From句ファクトリ
 */
abstract class FromClauseFactory
{
    //==============================================
    // factory
    //==============================================
    /**
     * From句を作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  FromClause   From句
     */
    public static function factory(...$arguments): FromClause
    {
        return FromClause::factory(...$arguments);
    }

    //==============================================
    // property access shortcut
    //==============================================
    /**
     * From句構築時にテーブル参照を設定したFrom句を返します。
     *
     * @param   string|TableReferenceExpression|AliasKeywordEntity    $table  テーブル参照
     * @return  FromClause    From句
     */
    public static function table($table, $alias = null): FromClause
    {
        return FromClause::factory()->table($table, $alias);
    }

    //==============================================
    // feature shortcut
    //==============================================
}
