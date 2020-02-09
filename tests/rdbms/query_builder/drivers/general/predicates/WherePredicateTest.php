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

namespace fw3\tests\io\rdbms\query_builder\drivers\general;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection;
use fw3\io\rdbms\query_builder\drivers\general\factorys\clauses\WhereClauseFactory;
use fw3\io\rdbms\query_builder\drivers\general\factorys\expressions\RawExpressionFactory;
use fw3\io\rdbms\query_builder\drivers\general\factorys\literals\LiteralFactory;
use fw3\io\rdbms\query_builder\drivers\general\factorys\predicates\WherePredicateFactory;
use fw3\io\rdbms\query_builder\drivers\general\statements\DeleteStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\UpdateStatement;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;
use fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate;

/**
 * WherePredicateテスト
 */
class WherePredicateTest extends AbstractQueryBuilderTestCase
{
    public function testOperator()
    {
        $values = [];
        $messageIdColumn    = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` = ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_EQ)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` <=> ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_NULL_SAFE_EQ)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IN (?)',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_IN)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` NOT IN (?)',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_N_IN)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` <> ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_N_EQ)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` < ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_LT)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` > ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_GT)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` <= ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_LT_EQ)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` >= ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_GT_EQ)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS TRUE',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, true,  Query::OP_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS FALSE',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, false,  Query::OP_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS NULL',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, null,  Query::OP_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS NOT TRUE',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, true,  Query::OP_N_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS NOT FALSE',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, false,  Query::OP_N_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` IS NOT NULL',
            'conditions'    => [],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, null,  Query::OP_N_IS)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` BETWEEN ? AND ?',
            'conditions'    => [1, 2],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, [1, 2],  Query::OP_BETWEEN)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` NOT BETWEEN ? AND ?',
            'conditions'    => [1, 2],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, [1, 2],  Query::OP_N_BETWEEN)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` LIKE ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_LIKE)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` NOT LIKE ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_N_LIKE)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` SOUNDS LIKE ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_SOUNDS_LIKE)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` REGEX ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_REGEX)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '`message`.`message_id` NOT REGEX ?',
            'conditions'    => [1],
            'values'        => $values,
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1,  Query::OP_NOT_REGEX)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLeftExpression()
    {
        //----------------------------------------------
        // WherePredicate
        //----------------------------------------------
        $where  = WherePredicateFactory::where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(`message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // WhereCollection
        //----------------------------------------------
        $where  = WhereCollection::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(`message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // WhereClause
        //----------------------------------------------
        $where  = WhereClauseFactory::where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(`message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // SelectStatement
        //----------------------------------------------
        $where  = SelectStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // UpdateStatement
        //----------------------------------------------
        $where  = UpdateStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(`message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // DeleteStatement
        //----------------------------------------------
        $where  = DeleteStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '(`message`.`message_id` = ?) = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($where, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // RawExpression
        //----------------------------------------------
        $expression = RawExpressionFactory::clause('?')->conditions([1]);
        $expected   = [
            'clause'        => '? = `message`.`message_id`',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($expression, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expression = RawExpressionFactory::clause('1');
        $expected   = [
            'clause'        => '1 = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($expression, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testRightExpression()
    {
        //----------------------------------------------
        // WherePredicate
        //----------------------------------------------
        $where  = WherePredicateFactory::where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` = (`message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // WhereCollection
        //----------------------------------------------
        $where  = WhereCollection::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` = (`message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // WhereClause
        //----------------------------------------------
        $where  = WhereClauseFactory::where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` = (`message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // SelectStatement
        //----------------------------------------------
        $where  = SelectStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` IN (SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // UpdateStatement
        //----------------------------------------------
        $where  = UpdateStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` = (`message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // DeleteStatement
        //----------------------------------------------
        $where  = DeleteStatement::factory()->where(Spec::messageIdColumn(), 1);
        $expected   = [
            'clause'        => '`message`.`message_id` = (`message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $where)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // RawExpression
        //----------------------------------------------
        $expression = RawExpressionFactory::clause('?')->conditions([1]);
        $expected   = [
            'clause'        => '`message`.`message_id` = ?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $expression)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expression = RawExpressionFactory::clause('1');
        $expected   = [
            'clause'        => '`message`.`message_id` = 1',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $expression)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteral()
    {
        //----------------------------------------------
        // bool
        //----------------------------------------------
        $literal    = LiteralFactory::bool(true);
        $expected   = [
            'clause'        => '`message`.`message_id` IS TRUE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TRUE = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // date
        //----------------------------------------------
        $literal    = LiteralFactory::date('2020-06-07');
        $expected   = [
            'clause'        => '`message`.`message_id` = DATE "2020-06-07"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'DATE "2020-06-07" = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // default
        //----------------------------------------------
        $literal    = LiteralFactory::default();
        $expected   = [
            'clause'        => '`message`.`message_id` = default',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'default = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // null
        //----------------------------------------------
        $literal    = LiteralFactory::null();
        $expected   = [
            'clause'        => '`message`.`message_id` IS NULL',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'NULL = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // Number
        //----------------------------------------------
        $literal    = LiteralFactory::number(12345.6);
        $expected   = [
            'clause'        => '`message`.`message_id` = 12345.6',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '12345.6 = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // String
        //----------------------------------------------
        $literal    = LiteralFactory::string('"あ"い"');
        $expected   = [
            'clause'        => '`message`.`message_id` = """あ""い"""',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '"""あ""い""" = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // time
        //----------------------------------------------
        $literal    = LiteralFactory::time('2020-06-07 12:13:14');
        $expected   = [
            'clause'        => '`message`.`message_id` = TIME "12:13:14"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIME "12:13:14" = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        // timestamp
        //----------------------------------------------
        $literal    = LiteralFactory::timestamp('2020-06-07 12:13:14.9876');
        $expected   = [
            'clause'        => '`message`.`message_id` = TIMESTAMP "1591531994.9876"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), $literal)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.9876" = `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = WherePredicate::factory()->table(Spec::TABLE_NAME)->where($literal, Spec::messageIdColumn())->build();
        $this->assertBuildResult($expected, $actual);
    }
}
