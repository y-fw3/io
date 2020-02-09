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

declare(strict_types = 1);

namespace fw3\io\rdbms\query_builder\drivers\general\literals;

use fw3\io\rdbms\query_builder\drivers\general\literals\traits\LiteralInterface;
use fw3\io\rdbms\query_builder\drivers\general\literals\traits\LiteralTrait;
use fw3\io\rdbms\query_builder\drivers\general\literals\traits\datetime\LiteralDateTimeInterface;
use fw3\io\rdbms\query_builder\drivers\general\literals\traits\datetime\LiteralDateTimeTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;

/**
 * タイムスタンプリテラルインスタンスクラス
 *
 * @method static LiteralTimestamp factory(string|int|float|LiteralTime $timestamp = 'now', null|string|\DateTimeZone $timezone = null)
 * @method LiteralTimestamp|string[] value(null|string|int|float|LiteralTime $timestamp = 'now', null|string|\DateTimeZone $timezone = null)
 * @method LiteralTimestamp|float timestamp(null|string|int|float|LiteralTime $timestamp = null)
 * @method LiteralTimestamp|null|\DateTimeZone timestamp(null|string|\DateTimeZone|LiteralTime $timezone = null)
 * @method LiteralTimestamp|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralTimestamp|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralTimestamp with()
 */
class LiteralTimestamp implements
    // group
    LiteralInterface,
    // trait
    LiteralDateTimeInterface
{
    use LiteralTrait;
    use LiteralDateTimeTrait;

    //==============================================
    // const
    //==============================================
    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'timezone',
    ];

    /**
     * @var string  型キーワード
     */
    public const TYPE_KEY_WORD  = 'TIMESTAMP';

    /**
     * @var string  日付フォーマット
     */
    public const FORMAT         = 'Y-m-d H:i:s.u';

    /**
     * @var string  句フォーマット
     */
    public const CLAUSE_FORMAT  = '%s "%s"';

    /**
     * @var int     タイムスタンプの有効マイクロ秒桁数
     */
    public const MICROTIME_PRECISION    = 6;

    //==============================================
    // builder
    //==============================================
    /**
     * フォーマットした文字列として返します。
     *
     * @return  string  フォーマットした文字列
     */
    public function toFormatString(): string
    {
        return $this->toDateTime()->format(static::FORMAT);
    }

    /**
     * 有効な桁長のタイムスタンプ文字列として返します。
     *
     * @return  float   有効な桁長のタイムスタンプ文字列
     */
    public function toTimestampString(): string
    {
        $dot_pos = strpos($this->timestamp, '.');
        if (false === $dot_pos) {
            return $this->timestamp;
        }
        return sprintf('%s.%s', substr($this->timestamp, 0, $dot_pos), substr($this->timestamp, $dot_pos + 1, static::MICROTIME_PRECISION));
    }

    /**
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResult
    {
        return BuildResult::factory(sprintf(static::CLAUSE_FORMAT, static::TYPE_KEY_WORD, $this->toTimestampString()), [], []);
    }
}
