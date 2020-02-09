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

namespace fw3\io\rdbms\query_builder\drivers\general\literals\traits\datetime;

/**
 * Datetimeリテラル特性インターフェース
 */
interface LiteralDateTimeInterface
{
    //==============================================
    // constructor
    //==============================================
    /**
     * factory
     *
     * @param   string|int|float|LiteralDateTimeTrait   $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone                    $timezone   タイムゾーン
     * @return  LiteralDateTimeTrait    Datetimeリテラル特性
     */
    public static function factory($timestamp, $timezone = null): LiteralDateTimeInterface;

    //==============================================
    // property access
    //==============================================
    /**
     * Datetimeリテラルを取得・設定します。
     *
     * @param   null|string|int|float|LiteralDateTimeInterface  $timestamp  タイムスタンプ
     * @param   null|string|\DateTimeZone                       $timezone   タイムゾーン
     * @return  string[]|LiteralDateTimeInterface   値セットまたはこのインスタンス
     */
    public function value($timestamp = null, $timezone = null);

    /**
     * タイムスタンプを取得・設定します。
     *
     * @param   null|string|int|float|LiteralDateTimeInterface  $timestamp  タイムスタンプ
     * @return  float|LiteralDateTimeInterface  タイムスタンプまたはこのインスタンス
     */
    public function timestamp($timestamp = null);

    /**
     * タイムゾーンを取得・設定します。
     *
     * @param   null|string|\DateTimeZone|LiteralDateTimeInterface  $timestamp  タイムゾーン
     * @return  null|\DateTimeZone|LiteralDateTimeInterface タイムゾーンまたはこのインスタンス
     */
    public function timezone($timezone = null);

    //==============================================
    // builder
    //==============================================
    /**
     * DateTimeインスタンスを返します。
     *
     * @return  \DateTime
     */
    public function toDateTime(): \DateTime;
}
