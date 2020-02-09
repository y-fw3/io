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
 * 時間リテラルインスタンスクラス
 *
 * @method static LiteralTime factory(string|int|float|LiteralTime $timestamp = 'now', null|string|\DateTimeZone $timezone = null)
 * @method LiteralTime|string[] value(null|string|int|float|LiteralTime $timestamp = 'now', null|string|\DateTimeZone $timezone = null)
 * @method LiteralTime|float timestamp(null|string|int|float|LiteralTime $timestamp = null)
 * @method LiteralTime|null|\DateTimeZone timestamp(null|string|\DateTimeZone|LiteralTime $timezone = null)
 * @method LiteralTime|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralTime|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralTime with()
 */
class LiteralTime implements
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
    public const TYPE_KEY_WORD  = 'TIME';

    /**
     * @var string  時間フォーマット
     */
    public const FORMAT         = 'H:i:s';

    /**
     * @var string  句フォーマット
     */
    public const CLAUSE_FORMAT  = '%s "%s"';

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
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResult
    {
        return BuildResult::factory(sprintf(static::CLAUSE_FORMAT, static::TYPE_KEY_WORD, $this->toFormatString()), [], []);
    }
}
