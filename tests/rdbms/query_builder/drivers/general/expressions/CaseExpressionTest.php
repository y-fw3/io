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
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * CaseExpressionテスト
 */
class CaseExpressionTest extends AbstractQueryBuilderTestCase
{
    public function testCase()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE ? WHEN ? THEN ? END',
            'conditions'    => [1, 2, 3],
            'values'        => [],
        ];
        $actual     = Query::case(1)->when(2, 3)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE `message_id` WHEN ? THEN ? END',
            'conditions'    => [2, 3],
            'values'        => [],
        ];
        $actual     = Query::case(Query::column(Spec::COLUMN_NAME))->when(2, 3)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE `message_id` WHEN ? THEN ? WHEN ? THEN ? END',
            'conditions'    => [2, 3, 4, 5],
            'values'        => [],
        ];
        $actual     = Query::case(Query::column(Spec::COLUMN_NAME))->when(2, 3)->when(4, 5)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE `message_id` WHEN ? THEN ? WHEN ? THEN ? ELSE ? END',
            'conditions'    => [2, 3, 4, 5, 6],
            'values'        => [],
        ];
        $actual     = Query::case(Query::column(Spec::COLUMN_NAME))->when(2, 3)->when(4, 5)->elseResult(6)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE WHEN `message_id` = ? THEN ? END',
            'conditions'    => [2, 3],
            'values'        => [],
        ];
        $actual     = Query::case()->when(Query::where(Spec::COLUMN_NAME, 2), 3)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'CASE WHEN `message_id` = ? THEN ? WHEN `contributer_id` = ? THEN ? ELSE ? END',
            'conditions'    => [2, 3, 4, 5, 6],
            'values'        => [],
        ];
        $actual     = Query::case()->when(Query::where(Spec::COLUMN_NAME, 2), 3)->when(Query::where(Spec::COLUMN_NAME_2, 4), 5)->elseResult(6)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
