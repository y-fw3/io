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
use fw3\io\rdbms\query_builder\drivers\general\factorys\predicates\WherePredicateFactory;
use fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * @var string DEF
 */
trait QueryTestWherePredicateTrait
{
    public function testWherePredicate()
    {
        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);

        $expected   = WherePredicate::class;
        $actual     = $wherePredicate;
        $this->assertInstanceOf($expected, $actual);

        $expected   = 1;
        $actual     = $wherePredicate->leftExpression();
        $this->assertSame($expected, $actual);

        $expected   = 2;
        $actual     = $wherePredicate->rightExpression();
        $this->assertSame($expected, $actual);

        $expected   = Query::OP_N_EQ;
        $actual     = $wherePredicate->operator();
        $this->assertSame($expected, $actual);

        $expected   = Query::LOP_AND;
        $actual     = $wherePredicate->logicalOperator();
        $this->assertSame($expected, $actual);

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(Spec::COLUMN_NAME, 2, Query::OP_N_EQ)->table(Spec::TABLE_NAME);

        $expected   = Query::table(Spec::TABLE_NAME);
        $actual     = $wherePredicate->table();
        $this->assertEquals($expected, $actual);

        $expected   = Query::column(Spec::COLUMN_NAME)->name();
        $actual     = $wherePredicate->leftExpression()->name();
        $this->assertSame($expected, $actual);

        $expected   = [
            'clause'        => '`message`.`message_id` <> ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(Spec::COLUMN_NAME, 1, Query::OP_N_EQ);
        $wherePredicate->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $expected   = [
            'clause'        => '`m`.`message_id` <> ?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(Spec::COLUMN_NAME, 1, Query::OP_N_EQ);
        $subQuery       = $wherePredicate->convertSubQuery()->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);

        $expected   = [
            'clause'        => '(`m`.`message_id` <> ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = $subQuery->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(Spec::COLUMN_NAME, 1, Query::OP_N_EQ);
        $wherePredicate = WherePredicateFactory::where($wherePredicate, $wherePredicate->withTable(Spec::TABLE_NAME, Spec::TABLE_ALIAS));
        $wherePredicate->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '(`message`.`message_id` <> ?) = (`m`.`message_id` <> ?)',
            'conditions'    => [1, 1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(Spec::COLUMN_NAME, 1, Query::OP_N_EQ);
        $wherePredicate = WherePredicateFactory::where($wherePredicate->convertSubQuery(), $wherePredicate->convertSubQuery()->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS));
        $wherePredicate->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '(`message`.`message_id` <> ?) = (`m`.`message_id` <> ?)',
            'conditions'    => [1, 1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testWherePredicate1()
    {
        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);

        $expected   = [
            'clause'        => '? <> ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);
        $wherePredicate     = WherePredicateFactory::where($left_expression);

        $expected   = [
            'clause'        => '? <> ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where($left_expression);

        $expected   = [
            'clause'        => '(? <> ?)',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(function () {
            $this->where(1, 2, Query::OP_N_EQ);
        });

        $expected   = [
            'clause'        => '(? <> ?)',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where(function () use ($left_expression) {
            $this->where($left_expression);
        });

        $expected   = [
            'clause'        => '((? <> ?))',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(fn() => $this->where(1, 2, Query::OP_N_EQ));

        $expected   = [
            'clause'        => '(? <> ?)',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where(fn() => $this->where($left_expression));

        $expected   = [
            'clause'        => '((? <> ?))',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $right_expression   = WherePredicateFactory::where(3, 4, Query::OP_LT)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where($left_expression, $right_expression);

        $expected   = [
            'clause'        => '(? <> ?) = (? < ?)',
            'conditions'    => [1, 2, 3, 4],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $right_expression1  = WherePredicateFactory::where(3, 4, Query::OP_LT)->convertSubQuery();
        $right_expression   = WherePredicateFactory::where($right_expression1, TRUE)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where($left_expression, $right_expression);

        $expected   = [
            'clause'        => '(? <> ?) = ((? < ?) IS TRUE)',
            'conditions'    => [1, 2, 3, 4],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testWherePredicate2()
    {
        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(0);

        $expected   = [
            'clause'        => '?',
            'conditions'    => [0],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(null);

        $expected   = [
            'clause'        => 'NULL',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(true);

        $expected   = [
            'clause'        => 'TRUE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(false);

        $expected   = [
            'clause'        => 'FALSE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where('column_name');

        $expected   = [
            'clause'        => '`column_name`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testWherePredicate3()
    {
        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(1, null, Query::OP_EQ);

        $expected   = [
            'clause'        => '? IS NULL',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(1, true, Query::OP_EQ);

        $expected   = [
            'clause'        => '? IS TRUE',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(1, false, Query::OP_EQ);

        $expected   = [
            'clause'        => '? IS FALSE',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(null, null, Query::OP_EQ);

        $expected   = [
            'clause'        => 'NULL IS NULL',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(true, false, Query::OP_N_EQ);

        $expected   = [
            'clause'        => 'TRUE IS NOT FALSE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $wherePredicate     = WherePredicateFactory::where(true, null, Query::OP_N_EQ);

        $expected   = [
            'clause'        => 'TRUE IS NOT NULL',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where($left_expression, true);

        $expected   = [
            'clause'        => '(? <> ?) IS TRUE',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ)->convertSubQuery();
        $wherePredicate     = WherePredicateFactory::where(true, $left_expression);

        $expected   = [
            'clause'        => 'TRUE = (? <> ?)',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = $wherePredicate->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testWherePredicate4()
    {
        //----------------------------------------------
        $wherePredicate1    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);
        $wherePredicate     = WherePredicateFactory::where($wherePredicate1);
        $this->assertNotSame($wherePredicate1, $wherePredicate);
        $this->assertEquals($wherePredicate1, $wherePredicate);

        $this->assertEquals(null, $wherePredicate1->parentReference());
        $this->assertEquals(null, $wherePredicate->parentReference());

        $this->assertEquals([], $wherePredicate1->getParentReferenceInfomation());
        $this->assertEquals([], $wherePredicate->getParentReferenceInfomation());

        $this->assertEquals(1, $wherePredicate->leftExpression());
        $this->assertEquals(2, $wherePredicate->rightExpression());

        $this->assertEquals(1, $wherePredicate1->leftExpression());
        $this->assertEquals(2, $wherePredicate1->rightExpression());

        //----------------------------------------------
        $wherePredicate1    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);
        $wherePredicate2    = WherePredicateFactory::where($wherePredicate1);
        $wherePredicate     = WherePredicateFactory::where($wherePredicate2);
        $this->assertNotSame($wherePredicate1, $wherePredicate2);
        $this->assertNotSame($wherePredicate2, $wherePredicate);
        $this->assertNotSame($wherePredicate, $wherePredicate2);
        $this->assertEquals($wherePredicate1, $wherePredicate2);
        $this->assertEquals($wherePredicate2, $wherePredicate);
        $this->assertEquals($wherePredicate, $wherePredicate2);

        $this->assertEquals(1, $wherePredicate->leftExpression());
        $this->assertEquals(2, $wherePredicate->rightExpression());

        //----------------------------------------------
        $wherePredicate1    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);
        $wherePredicate2    = WherePredicateFactory::where(3, 4);
        $wherePredicate     = WherePredicateFactory::where($wherePredicate1, $wherePredicate2);

        $this->assertNotSame($wherePredicate1, $wherePredicate->leftExpression());
        $this->assertNotSame($wherePredicate2, $wherePredicate->rightExpression());

        $this->assertNotEquals($wherePredicate1, $wherePredicate->leftExpression());
        $this->assertSame($wherePredicate1->leftExpression(), $wherePredicate->leftExpression()->leftExpression());
        $this->assertSame($wherePredicate1->rightExpression(), $wherePredicate->leftExpression()->rightExpression());

        $this->assertNotEquals($wherePredicate2, $wherePredicate->rightExpression());
        $this->assertSame($wherePredicate2->leftExpression(), $wherePredicate->rightExpression()->leftExpression());
        $this->assertSame($wherePredicate2->rightExpression(), $wherePredicate->rightExpression()->rightExpression());

        $wherePredicate1->table('test');
        $wherePredicate2->table('test');

        $this->assertNotEquals($wherePredicate1->table(), $wherePredicate->leftExpression()->table());
        $this->assertNotEquals($wherePredicate2->table(), $wherePredicate->rightExpression()->table());

        $this->assertSame(null, $wherePredicate->leftExpression()->table());
        $this->assertSame(null, $wherePredicate->rightExpression()->table());

        $this->assertNotSame(null, $wherePredicate1->table());
        $this->assertNotSame(null, $wherePredicate2->table());

        //----------------------------------------------
        $wherePredicate = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);

        $expected   = null;
        $actual     = $wherePredicate->parentReference();
        $this->assertSame($expected, $actual);

        //----------------------------------------------
        $left_expression    = WherePredicateFactory::where(1, 2, Query::OP_N_EQ);
        $wherePredicate     = WherePredicateFactory::where($left_expression);

        $expected   = null;
        $actual     = $left_expression->parentReference();
        $this->assertSame($expected, $actual);

        $expected   = null;
        $actual     = $wherePredicate->parentReference();
        $this->assertSame($expected, $actual);
    }
}
