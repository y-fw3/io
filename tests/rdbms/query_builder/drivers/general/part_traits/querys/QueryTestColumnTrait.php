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
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

trait QueryTestColumnTrait
{
    public function testColumn()
    {
        //==============================================
        // ColumnExpression
        //==============================================
        $this->assertInstanceOf(ColumnExpression::class, Query::column(Spec::COLUMN_NAME));
        $this->assertInstanceOf(ColumnExpression::class, Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS));

        $this->assertInstanceOf(ColumnExpression::class, Query::column(Query::column(Spec::COLUMN_NAME)));
        $this->assertInstanceOf(ColumnExpression::class, Query::column(Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)));

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(Spec::COLUMN_ALIAS, $actual->alias());

        $clause     = '`message_id` AS `m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME);
        $actual = Query::column($actual);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $actual = Query::column($actual);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(Spec::COLUMN_ALIAS, $actual->alias());

        $clause     = '`message_id` AS `m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(Spec::COLUMN_ALIAS, $actual->alias());

        $clause     = '`message`.`message_id` AS `m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(null, $actual->alias());

        $clause     = '`m`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $this->assertEquals(Spec::COLUMN_NAME, $actual->name());
        $this->assertEquals(Spec::COLUMN_ALIAS, $actual->alias());

        $clause     = '`m`.`message_id` AS `m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME);
        $actual = Query::where($column, $column);

        $clause     = '`message_id` = `message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $actual = Query::where($column, $column);

        $clause     = '`m_id` = `m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $actual = Query::where($column, $column);

        $clause     = '`message`.`message_id` = `message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME);
        $actual = Query::where($column, $column);

        $clause     = '`message`.`m_id` = `message`.`m_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2, Spec::TABLE_ALIAS_2);
        $actual = Query::where($column1, $column2);

        $clause     = '`m`.`message_id` = `c`.`contributer_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $column2 = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2)->table(Spec::TABLE_NAME_2, Spec::TABLE_ALIAS_2);
        $actual = Query::where($column1, $column2);

        $clause     = '`m`.`m_id` = `c`.`c_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }
}
