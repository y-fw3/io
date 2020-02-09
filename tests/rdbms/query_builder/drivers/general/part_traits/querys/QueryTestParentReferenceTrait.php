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
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * @var string
 */
trait QueryTestParentReferenceTrait
{
    public function testParentReference()
    {
        //==============================================
        // ParentReferenceProperty
        //==============================================
        $this->assertInstanceOf(ParentReferencePropertyInterface::class, Query::where(1));
        $this->assertInstanceOf(WhereClause::class, Query::where(1));

        $this->assertInstanceOf(ParentReferencePropertyInterface::class, Query::column(Spec::TABLE_NAME));
        $this->assertInstanceOf(ColumnExpression::class, Query::column(Spec::TABLE_NAME));

        //----------------------------------------------
        $column = Query::column(Spec::TABLE_NAME);
        $this->assertEquals([], $column->getParentReferenceChain(true));

        $where  = Query::where($column, 1);
        $this->assertEquals([], $where->getParentReferenceChain(true));
        $this->assertEquals([
            'fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate',
            'fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection',
            'fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause',
        ], $where->getByParentReferencePath(['collection', 0, 'leftExpression'])->getParentReferenceChain(true));

        //----------------------------------------------
        $column = Query::column(Spec::TABLE_NAME);
        $where  = Query::where($column, 1);

        $where->where($where)->where($where);

        $expected   = [
            'fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate',
            'fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection',
            'fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause',
        ];
        $this->assertEquals([], $where->getParentReferenceChain(true));

        $this->assertEquals($expected, $where->getByParentReferencePath(['collection', 0, 'leftExpression'])->getParentReferenceChain(true));
        $this->assertEquals($expected, $where->getByParentReferencePath(['collection', 1, 'leftExpression'])->getParentReferenceChain(true));
        $this->assertEquals($expected, $where->getByParentReferencePath(['collection', 2, 'leftExpression'])->getParentReferenceChain(true));
        $this->assertEquals($expected, $where->getByParentReferencePath(['collection', 3, 'leftExpression'])->getParentReferenceChain(true));
    }

    public function testParentReferenceWhere()
    {
        $column = Query::column(Spec::COLUMN_NAME);

        $where1 = Query::where($column, 1);
        $where2 = Query::where($column, 2, Query::OP_N_EQ);

        $column->table(Spec::TABLE_NAME);
        $where3 = Query::where($column, 3, Query::OP_GT);

        $column = $column->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $where4 = Query::where($column, 4, Query::OP_LT);

        $clause     = '`message_id` = ?';
        $conditions = [1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where1->build());

        $clause     = '`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where2->build());

        $clause     = '`message`.`message_id` > ?';
        $conditions = [3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where3->build());

        $clause     = '`m`.`message_id` < ?';
        $conditions = [4];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where4->build());
    }

    public function testParentReferenceSubQuery()
    {
        $column     = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME_2);
        $subQuery   = Query::where($column, [5, 6])->convertSubQuery();

        $where1 = Query::where($column, 1)->where($subQuery);
        $where2 = Query::where($column, 2, Query::OP_N_EQ)->where($subQuery);

        $column->table(Spec::TABLE_NAME);
        $where3 = Query::where($column, 3, Query::OP_GT)->where($subQuery);

        $column = $column->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $where4 = Query::where($column, 4, Query::OP_LT)->where($subQuery);

        $clause     = '`contributer`.`message_id` = ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [1, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where1->build());

        $clause     = '`contributer`.`message_id` <> ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [2, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where2->build());

        $clause     = '`message`.`message_id` > ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [3, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where3->build());

        $clause     = '`m`.`message_id` < ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [4, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where4->build());
    }

    public function testParentReferenceClosureSubQuery()
    {
        $column     = Query::column(Spec::COLUMN_NAME);
        $subQuery   = fn () => Query::where($column, [5, 6]);

        // where 1
        $where1     = Query::where($column, 1)->where($subQuery);
        $clause     = '`message_id` = ? AND (`message_id` IN (?, ?))';
        $conditions = [1, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where1->build());

        // where 2
        $column2    = $column->withTable(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $where2     = Query::where($column2, 2, Query::OP_N_EQ)->where($subQuery);

        $clause     = '`m`.`message_id` <> ? AND (`message_id` IN (?, ?))';
        $conditions = [2, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where2->build());

        // where 3
        $where3 = Query::where($column, 3, Query::OP_GT)->where($subQuery)->table(Spec::TABLE_NAME_2);


        var_dump($where3->debugParentReferenceTree(true));

        $clause     = '`contributer`.`message_id` > ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [3, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where3->build());

        // where 4
        $where4 = Query::where($column, 4, Query::OP_LT)->where($subQuery->withTable(Spec::TABLE_NAME, Spec::TABLE_ALIAS));

        $clause     = '`m`.`message_id` < ? AND (`m`.`message_id` IN (?, ?))';
        $conditions = [4, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where4->build());

        // where 1 2nd
        $clause     = '`message_id` = ? AND (`message_id` IN (?, ?))';
        $conditions = [1, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where1->build());

        // where 2 2nd
        $clause     = '`m`.`message_id` <> ? AND (`m`.`message_id` IN (?, ?))';
        $conditions = [2, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where2->build());

        // where 3 2nd
        $clause     = '`contributer`.`message_id` > ? AND (`contributer`.`message_id` IN (?, ?))';
        $conditions = [3, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where3->build());

        // where 4 2nd
        $clause     = '`m`.`message_id` < ? AND (`m`.`message_id` IN (?, ?))';
        $conditions = [4, 5, 6];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $where4->build());
    }


    public function _testParentReference2()
    {
        //----------------------------------------------
        $column     = Query::column(Spec::TABLE_NAME);
        $where      = Query::where($column, 1);
        $where1     = Query::where($column, 1);
        $subQuery   = $where->where($where)->convertSubQuery();

        $where->where($subQuery)->where($where1);
        var_dump($where->build()->getClause());


        $where1->table('aaa');
        $where->where($subQuery)->where($where1);
        var_dump($where->build()->getClause());

//        var_dump($where->getByParentReferencePath(['collection', 3, 'leftExpression'])->getParentReferenceChain(true));
/*
            ["fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery"]=>
            array(1) {
                ["fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause"]=>
                array(1) {
                    ["collection"]=>
                    array(1) {
                        ["fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause => fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection"]=>
                        array(6) {
                            [0]=>
                            array(1) {
                                ["fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection"]=>
                                array(1) {
                                    ["fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate"]=>
                                    array(1) {
                                        ["leftExpression"]=>
                                        string(143) "fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate => fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression"
                                    }
                                }
                            }
*/
        var_dump($where->debugParentReferenceTree(true));

var_dump($where->getParentReferenceChain(true));
var_dump($subQuery->getParentReferenceChain(true));

        $expected   = [
            'fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate',
            'fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection',
            'fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause',
        ];
        $this->assertEquals([], $where->getParentReferenceChain(true));


//         //----------------------------------------------
//         $column = Query::column(Spec::TABLE_NAME);
//         $where  = Query::where($column, 1);

//         $where->where(fn() => $where->where($where));

//         $expected   = [
//             'fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate',
//             'fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection',
//             'fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause',
//         ];
//         $this->assertEquals([], $where->getParentReferenceChain(true));
    }
}
