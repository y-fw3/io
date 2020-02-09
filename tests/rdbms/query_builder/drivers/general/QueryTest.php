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
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralBool;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDate;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDefault;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNull;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNumber;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralString;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTime;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTimestamp;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\part_traits\querys\QueryTestSubQueriableTrait;
use fw3\tests\io\rdbms\query_builder\drivers\general\part_traits\querys\QueryTestWherePredicateTrait;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * Queryファクトリテスト
 */
class QueryTest extends AbstractQueryBuilderTestCase
{
//    use QueryTestParentReferenceTrait;
//     use QueryTestTableReferenceTrait;
//     use QueryTestTablePropertyTrait;
//     use QueryTestColumnTrait;
//     use QueryTestWhereTrait;

    // element tests
    use QueryTestWherePredicateTrait;
    use QueryTestSubQueriableTrait;

    public function testSelectQueryBuilder()
    {
        $select = Query::select()
        ->where(1, 1)
        ->useIndex()
        ->ignoreKey('hoge')
        ->innerJoin(Spec::TABLE_NAME_2, Spec::COLUMN_NAME)
        ->limit(1, 4)
        ->orderBy('aaa')
        ->table(Spec::TABLE_NAME);

        $this->assertSame(1, 1);
    }

    public function testLiteral()
    {
        $this->assertInstanceOf(LiteralBool::class, Query::bool(true));
        $this->assertInstanceOf(LiteralDate::class, Query::date(Spec::DATE));
        $this->assertInstanceOf(LiteralDefault::class, Query::default());
        $this->assertInstanceOf(LiteralNull::class, Query::null());
        $this->assertInstanceOf(LiteralNumber::class, Query::number(Spec::FLOAT));
        $this->assertInstanceOf(LiteralString::class, Query::string(Spec::STRING));
        $this->assertInstanceOf(LiteralTime::class, Query::time(Spec::TIME));
        $this->assertInstanceOf(LiteralTimestamp::class, Query::timestamp(Spec::TIMESTAMP));
    }

/*
    public function testWhereClause()
    {
        //----------------------------------------------
        $whereClause    = WhereClauseFactory::where(1, 2, Query::OP_N_EQ);

        $expected   = WhereClause::class;
        $actual     = $whereClause;
        $this->assertInstanceOf($expected, $actual);

        $expected   = 1;
        $actual     = $whereClause[0]->leftExpression();
        $this->assertSame($expected, $actual);

        $expected   = 2;
        $actual     = $whereClause[0]->rightExpression();
        $this->assertSame($expected, $actual);

        $expected   = Query::OP_N_EQ;
        $actual     = $whereClause[0]->operator();
        $this->assertSame($expected, $actual);

        $expected   = Query::LOP_AND;
        $actual     = $whereClause[0]->logicalOperator();
        $this->assertSame($expected, $actual);

        //----------------------------------------------
        $actual = WhereClauseFactory::where(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }
    */
}
