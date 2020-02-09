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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\expressions;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * ColumnExpressionテスト
 */
class ColumnExpressionTest extends AbstractQueryBuilderTestCase
{
    public function testFactory()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory([Spec::COLUMN_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        define('a', 1);
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory([Spec::COLUMN_NAME, Spec::COLUMN_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory(['column' => Spec::COLUMN_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory([Spec::COLUMN_NAME, 'alias' => Spec::COLUMN_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory(['name' => Spec::COLUMN_NAME, 'alias' => Spec::COLUMN_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory([Spec::COLUMN_NAME], Spec::COLUMN_ALIAS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME);
        $expected   = [
            'clause'        => '`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory($column)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $column = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS)->table(Spec::TABLE_NAME);
        $expected   = [
            'clause'        => '`message`.`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory($column, $column, $column)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testTable()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` AS `m_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory()->column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS, Spec::TABLE_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = ColumnExpression::factory()->column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
