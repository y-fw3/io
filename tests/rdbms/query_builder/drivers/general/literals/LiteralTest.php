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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\literals;

use fw3\io\rdbms\query_builder\drivers\general\factorys\literals\LiteralFactory;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;

/**
 * Literalテスト
 */
class LiteralTest extends AbstractQueryBuilderTestCase
{
    public function testLiteralBool()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'TRUE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::bool(true)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'FALSE',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::bool(false)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralDate()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => sprintf('DATE "%s"', date('Y-m-d')),
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::date()->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'DATE "2020-06-07"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::date('2020-06-07')->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralDefault()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'default',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::default()->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralNull()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'NULL',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::null()->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralNumber()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '0',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(0)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '1',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '-1',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(-1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '1.1',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(1.1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '-1.1',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(-1.1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '1.123456',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::number(1.123456)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralString()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => '"0"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::string('0')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '"asdf"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::string('asdf')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '"あ"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::string('あ')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '"あ"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::string('あ', 'UTF-8')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '"""あ""い"""',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::string('"あ"い"', 'UTF-8')->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralStringException()
    {
        //----------------------------------------------
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('使用できないエンコーディングの文字列です。string:\'あ\', test_encoding:\'SJIS-win\', encoding:\'UTF-8\'');
        LiteralFactory::string(mb_convert_encoding('あ', 'SJIS-win', 'UTF-8'), 'UTF-8')->build();
    }

    public function testLiteralTime()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => sprintf('TIME "%s"', date('H:i:s')),
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::time()->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIME "12:13:14"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::time('12:13:14')->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testLiteralTimestamp()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp('2020-06-07 12:13:14')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.123456"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp('2020-06-07 12:13:14.123456')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.123456"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp('2020-06-07 12:13:14.1234567')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.1235"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp(1591531994.123456)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.123456"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp('1591531994.123456')->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'TIMESTAMP "1591531994.123456"',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = LiteralFactory::timestamp('1591531994.1234567')->build();
        $this->assertBuildResult($expected, $actual);
    }
}
