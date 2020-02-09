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

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\literals;

use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralBool;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDate;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDefault;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNull;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNumber;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralString;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTime;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTimestamp;
use fw3\io\utilitys\exceptions\UnavailableVarException;


/**
 * Literalファサード
 */
abstract class LiteralFactory
{
    public static function literal($value, ...$options)
    {
        if (is_bool($value)) {
            return static::bool($value);
        }

        if (is_int($value)) {
            return static::number($value);
        }

        if (is_float($value)) {
            return static::number($value);
        }

        if (is_string($value)) {
            return static::string($value, ...$options);
        }

        UnavailableVarException::raise('自動判定出来ない型の値を渡されました。', ['value' => $value]);
    }

    public static function bool(bool $value): LiteralBool
    {
        return LiteralBool::factory($value);
    }

    public static function date($timestamp = 'now', $timezone = null): LiteralDate
    {
        return LiteralDate::factory($timestamp, $timezone);
    }

    public static function default(): LiteralDefault
    {
        return LiteralDefault::factory();
    }

    public static function null(): LiteralNull
    {
        return LiteralNull::factory();
    }

    public static function number($value): LiteralNumber
    {
        return LiteralNumber::factory($value);
    }

    public static function string($text, $encoding = null): LiteralString
    {
        return LiteralString::factory($text, $encoding);
    }

    public static function time($timestamp = 'now', $timezone = null): LiteralTime
    {
        return LiteralTime::factory($timestamp, $timezone);
    }

    public static function timestamp($timestamp = 'now', $timezone = null): LiteralTimestamp
    {
        return LiteralTimestamp::factory($timestamp, $timezone);
    }
}
