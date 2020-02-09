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
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * @var string DEF
 */
trait QueryTestWhereTrait
{
    public function testWhere()
    {
        //==============================================
        // WhereClause
        //==============================================
        $this->assertInstanceOf(WhereClause::class, Query::where(1));
        $this->assertInstanceOf(WhereClause::class, Query::where(1, 1, Query::OP_EQ));
        $this->assertInstanceOf(WhereClause::class, Query::where(1, 2, Query::OP_N_EQ));

        //----------------------------------------------
        $actual = Query::where(1);

        $clause     = '?';
        $conditions = [1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2);

        $clause     = '? = ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2, Query::OP_N_EQ);

        $clause     = '? <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(1, 2, Query::OP_N_EQ);

        $clause     = '? <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1);
        $actual = Query::where($where);

        $clause     = '?';
        $conditions = [1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2);
        $actual = Query::where($where);

        $clause     = '? = ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2, Query::OP_N_EQ);
        $actual = Query::where($where);

        $clause     = '? <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1);
        $actual = Query::where($where, 3);

        $clause     = '(?) = ?';
        $conditions = [1, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2);
        $actual = Query::where($where, 3);

        $clause     = '(? = ?) = ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where  = Query::where(1, 2, Query::OP_N_EQ);
        $actual = Query::where($where, 3);

        $clause     = '(? <> ?) = ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
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

        $clause     = '`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2);

        $clause     = '`message_id` = ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2, Query::OP_N_EQ);

        $clause     = '`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME));

        $clause     = '`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2);

        $clause     = '`message_id` = ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2, Query::OP_N_EQ);

        $clause     = '`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id` = ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Spec::COLUMN_NAME, 2, Query::OP_N_EQ)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME))->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id` = ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME), 2, Query::OP_N_EQ)->table(Spec::TABLE_NAME);

        $clause     = '`message`.`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME));

        $clause     = '`message`.`message_id`';
        $conditions = [];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 2);

        $clause     = '`message`.`message_id` = ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $actual = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 2, Query::OP_N_EQ);

        $clause     = '`message`.`message_id` <> ?';
        $conditions = [2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }

    public function testWhereWhere()
    {
        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2);

        $actual = Query::where($column1, 1)->where($column2, 2, Query::OP_N_EQ);

        $clause     = '`message`.`message_id` = ? AND `contributer`.`contributer_id` <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2);

        $actual = Query::where($column1, 1)->where($column2, 2, Query::OP_N_EQ)->where($column1, 3, Query::OP_N_EQ);

        $clause     = '`message`.`message_id` = ? AND `contributer`.`contributer_id` <> ? AND `message`.`message_id` <> ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2);
        $column3 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);

        $actual = Query::where($column1, 1)->where($column2, 2, Query::OP_N_EQ)->where($column3, 3, Query::OP_N_EQ)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS_2);

        $clause     = '`c`.`message_id` = ? AND `contributer`.`contributer_id` <> ? AND `m`.`message_id` <> ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2);
        $column3 = Query::column(Spec::COLUMN_NAME);

        $actual = Query::where($column1, 1)->where($column2, 2, Query::OP_N_EQ)->where($column3, 3, Query::OP_N_EQ);

        $clause     = '`m`.`message_id` = ? AND `contributer`.`contributer_id` <> ? AND `message_id` <> ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $column1 = Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME, Spec::TABLE_ALIAS);
        $column2 = Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2);
        $column3 = Query::column(Spec::COLUMN_NAME);

        $where1 = Query::where($column1, 1);
        $where2 = Query::where($column2, 2, Query::OP_N_EQ);
        $where3 = Query::where($column3, 3, Query::OP_N_EQ);

        $actual = Query::where($where1)->where($where2)->where($where3);

        $clause     = '`m`.`message_id` = ? AND `contributer`.`contributer_id` <> ? AND `message_id` <> ?';
        $conditions = [1, 2, 3];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }

    public function testWhereSubPart()
    {
        //----------------------------------------------
        $where1 = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 1);
        $actual = Query::where(fn() => $this->where($where1));

        $clause     = '(`message`.`message_id` = ?)';
        $conditions = [1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where1 = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 1);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2), 2, Query::OP_N_EQ);

        $actual = Query::where(fn() => $this->where($where1)->where($where2));

        $clause     = '(`message`.`message_id` = ? AND `contributer`.`contributer_id` <> ?)';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where1 = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 1);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2), 2, Query::OP_N_EQ);

        $actual = Query::where(Query::where($where1)->where($where2)->convertSubQuery());

        $clause     = '(`message`.`message_id` = ? AND `contributer`.`contributer_id` <> ?)';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where1 = Query::where(Query::column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME), 1);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME_2), 2, Query::OP_N_EQ);

        $actual = Query::where(Query::where($where1->convertSubQuery())->where($where2)->convertSubQuery());

        $clause     = '((`message`.`message_id` = ?) AND `contributer`.`contributer_id` <> ?)';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1)->table(Spec::TABLE_NAME);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);

        $where      = Query::where(Query::where($where1->convertSubQuery())->where($where2)->convertSubQuery()->table(Spec::TABLE_NAME_2));
        var_dump($where->build());

        $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1)->table(Spec::TABLE_NAME);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);

        $where      = Query::where($where1->convertSubQuery());
        var_dump($where->build());

        $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1)->table(Spec::TABLE_NAME);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);
        $where      = Query::where($where2)->convertSubQuery()->table(Spec::TABLE_NAME_2);
        var_dump($where->build());

        $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1)->table(Spec::TABLE_NAME);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);

        $subQuery = Query::where($where1)->where($where2)->convertSubQuery()->table(Spec::TABLE_NAME_2);

        $where  = Query::where($subQuery);

        //$where  = Query::where(Query::where($where1)->where($where2)->convertSubQuery()->table(Spec::TABLE_NAME_2));
        var_dump($where->build()->getClause(), $subQuery->build()->getClause());

//         $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1)->table(Spec::TABLE_NAME);
//         $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);
//         $where      = Query::where(Query::where($where1->convertSubQuery())->where($where2)->convertSubQuery()->table(Spec::TABLE_NAME_2));
//         var_dump($where->build());

        $where1 = Query::where(Query::column(Spec::COLUMN_NAME), 1);
        $where2 = Query::where(Query::column(Spec::COLUMN_NAME_2), 2, Query::OP_N_EQ);

        $actual = Query::where(Query::where($where1->convertSubQuery())->where($where2)->convertSubQuery()->table(Spec::TABLE_NAME));

        $clause     = '((`message`.`message_id` = ?) AND `message`.`contributer_id` <> ?)';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }

    public function _testWhereLogicalOperator()
    {
        //----------------------------------------------
        $table      = Query::table(Spec::TABLE_NAME);
        $column1    = Query::column(Spec::COLUMN_NAME)->table($table);
        $column2    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2)->table($table);

        $actual     = Query::where($column1, 1)->andWhere($column2, 2, Query::OP_N_EQ);

        $clause     = '`message`.`message_id` = ? AND `message`.`c_id` <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $table      = Query::table(Spec::TABLE_NAME);
        $column1    = Query::column(Spec::COLUMN_NAME)->table($table);
        $column2    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2)->table($table);

        $actual     = Query::where($column1, 1)->orWhere($column2, 2, Query::OP_N_EQ);

        $clause     = '`message`.`message_id` = ? OR `message`.`c_id` <> ?';
        $conditions = [1, 2];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $table      = Query::table(Spec::TABLE_NAME);
        $column1    = Query::column(Spec::COLUMN_NAME)->table($table);
        $column2    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2)->table($table);

        $actual     = Query::where($column1, 1)->orWhere($column2, 2, Query::OP_N_EQ)->andWhere($column1, 1);

        $clause     = '`message`.`message_id` = ? OR `message`.`c_id` <> ? AND `message`.`message_id` = ?';
        $conditions = [1, 2, 1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());

        //----------------------------------------------
        $table      = Query::table(Spec::TABLE_NAME);
        $column1    = Query::column(Spec::COLUMN_NAME)->table($table);
        $column2    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2)->table($table);

        $where      = Query::where($column1, 1);
        $actual     = Query::where($where)->orWhere($column2, 2, Query::OP_N_EQ)->andWhere($where);

        $clause     = '`message`.`message_id` = ? OR `message`.`c_id` <> ? AND `message`.`message_id` = ?';
        $conditions = [1, 2, 1];
        $values     = [];
        $this->assertBuildResult($clause, $conditions, $values, $actual->build());
    }
}
