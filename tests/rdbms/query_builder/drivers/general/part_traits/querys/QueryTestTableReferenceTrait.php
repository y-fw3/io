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

trait QueryTestTableReferenceTrait
{
    public function testTableReference()
    {
        //==============================================
        // TableReferenceExpression
        //==============================================
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table(Spec::TABLE_NAME));
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS));

        //----------------------------------------------
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table(Query::table(Spec::TABLE_NAME)));
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table(Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS)));

        //----------------------------------------------
        $actual = Query::table(Spec::TABLE_NAME);
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`message` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Query::table(Spec::TABLE_NAME));
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS));
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`message` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS_2);
        $actual = $actual->table(Query::table(Spec::TABLE_NAME));
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS_2);
        $actual = $actual->table(Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS));
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`message` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS_2);
        $actual = $actual->table(Spec::TABLE_NAME_2, Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS));
        $this->assertEquals(Spec::TABLE_NAME_2, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`contributer` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //==============================================
        // TablePropertyInterface
        //==============================================
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table($column));

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $this->assertInstanceOf(TableReferenceExpression::class, Query::table($column));

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $actual = Query::table($column);
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $actual = Query::table($column);
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`message` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $actual = $actual->table($column, Spec::TABLE_ALIAS_2);
        $this->assertEquals(Spec::TABLE_NAME, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS_2, $actual->alias());

        $clause     = '`message` AS `c`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $actual = $actual->table(Spec::TABLE_NAME_2, $column);
        $this->assertEquals(Spec::TABLE_NAME_2, $actual->name());
        $this->assertEquals(Spec::TABLE_ALIAS, $actual->alias());

        $clause     = '`contributer` AS `m`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }
}