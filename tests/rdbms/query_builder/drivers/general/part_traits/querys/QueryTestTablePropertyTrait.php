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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\part_traits\querys;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

trait QueryTestTablePropertyTrait
{
    public function testTableProperty()
    {
        //==============================================
        // TablePropertyTrait
        //==============================================
        $actual = Query::column(Spec::COLUMN_NAME);
        $this->assertEquals(null, $actual->table());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $this->assertInstanceOf(TableReferenceExpression::class, $actual->table());
        $this->assertEquals(Spec::TABLE_NAME, $actual->table()->name());
        $this->assertEquals(null, $actual->table()->alias());

        $clause     = '`message`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->table()->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $this->assertInstanceOf(TableReferenceExpression::class, $actual->table());
        $this->assertEquals(Spec::TABLE_NAME, $actual->table()->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->table()->alias());

        $clause     = '`message` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->table()->build());
    }
}
