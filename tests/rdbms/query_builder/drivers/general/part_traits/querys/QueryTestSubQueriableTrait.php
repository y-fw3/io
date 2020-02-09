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
use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * @var string
 */
trait QueryTestSubQueriableTrait
{
    public function testSubQuery()
    {
        //==============================================
        // WhereClause
        //==============================================
        $this->assertInstanceOf(SubQuery::class, Query::subQuery());

        //----------------------------------------------
        $this->assertInstanceOf(SubQuery::class, Query::where(1)->convertSubQuery());

        //----------------------------------------------
        $where1  = Query::where(Spec::COLUMN_NAME, 1);
        $subQuery = $where1->convertSubQuery();
        $where  = Query::where($subQuery);

        $expected = [];
        $actual = $subQuery->getParentReferenceInfomation(true);
        $this->assertEquals($expected, $actual);

        $expected = [];
        $actual = $where1->getParentReferenceInfomation(true);
        $this->assertEquals($expected, $actual);

        $expected = [
            'fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate',
            'fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection',
            'fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause',
        ];
        $actual = $where[0]->leftExpression()->getParentReferenceInfomation(true);
        $this->assertEquals($expected, $actual);

        //----------------------------------------------
        $column         = Query::column(Spec::COLUMN_NAME);
        $where          = Query::where($column, 1);
        $subQuery       = $where->convertSubQuery();
        $whereSubQuery  = Query::where($subQuery);

        $expected = [];
        $actual = $subQuery->getParentReferenceInfomation(true);
        $this->assertEquals($expected, $actual);

        $expected = [];
        $actual = $whereSubQuery->getParentReferenceInfomation(true);
        $this->assertEquals($expected, $actual);

        //----------------------------------------------
        $actual = Query::where(1);

        $expected   = [
            'clause'        => '?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2);

        $expected   = [
            'clause'        => '? = ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2, Query::OP_N_EQ);

        $expected   = [
            'clause'        => '? <> ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2, Query::OP_N_EQ);

        $expected   = [
            'clause'        => '? <> ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1);
        $actual = Query::where($where);

        $expected   = [
            'clause'        => '?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2);
        $actual = Query::where($where);

        $expected   = [
            'clause'        => '? = ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2, Query::OP_N_EQ);
        $actual = Query::where($where);

        $expected   = [
            'clause'        => '? <> ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1);
        $actual = Query::where($where, 3);

        $expected   = [
            'clause'        => '(?) = ?',
            'conditions'    => [1, 3],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2);
        $actual = Query::where($where, 3);

        $expected   = [
            'clause'        => '(? = ?) = ?',
            'conditions'    => [1, 2, 3],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2, Query::OP_N_EQ);
        $actual = Query::where($where, 3);

        $expected   = [
            'clause'        => '(? <> ?) = ?',
            'conditions'    => [1, 2, 3],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());
    }

    public function testWhereAndColumn()
    {
        //==============================================
        // WhereClause and ColumnExpression
        //==============================================
        $this->assertInstanceOf(WhereClause::class, Query::where(Query::column(Spec::COLUMN_NAME)));
        $this->assertInstanceOf(WhereClause::class, Query::where(Query::column(Spec::COLUMN_NAME), 1, Query::OP_EQ));
        $this->assertInstanceOf(WhereClause::class, Query::where(1, Query::column(Spec::COLUMN_NAME), Query::OP_N_EQ));

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME);

        $expected   = [
            'clause'        => '`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2);

        $expected   = [
            'clause'        => '`message_id` = ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2, Query::OP_N_EQ);

        $expected   = [
            'clause'        => '`message_id` <> ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME));

        $expected   = [
            'clause'        => '`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2);

        $expected   = [
            'clause'        => '`message_id` = ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2, Query::OP_N_EQ);

        $expected   = [
            'clause'        => '`message_id` <> ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2)->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id` = ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2, Query::OP_N_EQ)->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id` <> ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME))->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2)->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id` = ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2, Query::OP_N_EQ)->table(Spec::TABLE_NAME);

        $expected   = [
            'clause'        => '`message`.`message_id` <> ?',
            'conditions'    => [2],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual->build());
    }
}
