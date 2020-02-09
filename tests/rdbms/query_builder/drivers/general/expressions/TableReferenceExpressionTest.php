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
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * TableReferenceExpressionテスト
 */
class TableReferenceExpressionTest extends AbstractQueryBuilderTestCase
{
    public function testFactory()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory(Spec::TABLE_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory(Spec::TABLE_NAME, Spec::TABLE_ALIAS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory([Spec::TABLE_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory([Spec::TABLE_NAME, Spec::TABLE_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory(['table' => Spec::TABLE_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory([Spec::TABLE_NAME, 'alias' => Spec::TABLE_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory(['name' => Spec::TABLE_NAME, 'alias' => Spec::TABLE_ALIAS])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory([Spec::TABLE_NAME], Spec::TABLE_ALIAS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $table = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory($table)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $table = Query::table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory($table, $table)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testTable()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory()->table(Spec::TABLE_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message` AS `m`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory()->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testCreateColumn()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = TableReferenceExpression::factory(Spec::TABLE_NAME, Spec::TABLE_ALIAS)->createColumn(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
