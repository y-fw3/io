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

use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * Datetimeリテラル特性インターフェース
 */
trait LiteralDateTimeTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var string  タイムスタンプ
     */
    protected string $timestamp;

    /**
     * @var null|\DateTimeZone  タイムゾーン
     */
    protected ?\DateTimeZone $timezone = null;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     *
     * @param   string|int|float|LiteralDateTimeInterface   $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone                        $timezone   タイムゾーン
     */
    protected function __construct($timestamp, $timezone = null)
    {
        $this->value($timestamp, $timezone);
    }

    /**
     * factory
     *
     * @param   string|int|float|LiteralDateTimeInterface   $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone                        $timezone   タイムゾーン
     * @return  LiteralDateTimeTrait                        Datetimeリテラル特性インターフェース
     */
    public static function factory($timestamp = 'now', $timezone = null): LiteralDateTimeInterface
    {
        return new static($timestamp, $timezone);
    }

    //==============================================
    // property access
    //==============================================
    /**
     * Datetimeリテラルを取得・設定します。
     *
     * @param   null|string|int|float|LiteralDateTimeInterface  $timestamp  タイムスタンプ
     * @param   null|string|\DateTimeZone                       $timezone   タイムゾーン
     * @return  string[]|LiteralDateTimeInterface               値セットまたはこのインスタンス
     */
    public function value($timestamp = null, $timezone = null)
    {
        if (is_null($timestamp)) {
            return [
                'timestamp' => $timestamp,
                'timezone'  => $timezone,
            ];
        }

        if (is_array($timestamp)) {
            $timestamp  = current($timestamp);
            $timezone   = $timezone ?? next($timezone);
        } elseif ($timestamp instanceof LiteralDateTimeInterface) {
            $timestamp  = $timestamp->timestamp();
            $timezone   = $timezone ?? $timestamp->timezone();
        }

        $this->timestamp($timestamp);

        if (is_null($timezone)) {
            return $this;
        }

        $this->timezone($timezone);

        return $this;
    }

    /**
     * タイムスタンプを取得・設定します。
     *
     * @param   null|string|int|float|LiteralDateTimeInterface  $timestamp  タイムスタンプ
     * @return  string|LiteralDateTimeInterface                 タイムスタンプまたはこのインスタンス
     */
    public function timestamp($timestamp = null)
    {
        if (is_null($timestamp) && func_num_args() === 0) {
            return $this->timestamp;
        }

        if ($timestamp instanceof LiteralDateTimeInterface) {
            $this->timezone = $timestamp->timestamp();
            return $this;
        }

        $timestamp  = (string) $timestamp;
        if ($timestamp === str_replace('-', '', filter_var($timestamp, \FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION))) {
            $dot_pos = strpos($timestamp, '.');
            if ($dot_pos === false) {
                $this->timestamp = $timestamp;
                return $this;
            }

            if (false !== strpos($timestamp, '.', $dot_pos + 1)) {
                UnavailableVarException::raise('使用できない型のタイムスタンプを渡されました。', ['timestamp' => $timestamp]);
            }

            $this->timestamp = $timestamp;
            return $this;
        }

        $int_part   = strtotime($timestamp);
        if (false === $int_part) {
            UnavailableVarException::raise('使用できない型のタイムスタンプを渡されました。', ['timestamp' => $timestamp]);
        }
        $float_part = false === ($dot_pos = strpos($timestamp, '.')) ? null : substr($timestamp, $dot_pos + 1);

        if (is_null($float_part)) {
            $this->timestamp = (string) $int_part;
        } else {
            $this->timestamp = sprintf('%s.%s', (string) $int_part, $float_part);
        }
        return $this;
    }

    /**
     * タイムゾーンを取得・設定します。
     *
     * @param   null|string|\DateTimeZone|LiteralDateTimeInterface  $timestamp  タイムゾーン
     * @return  null|\DateTimeZone                                  タイムゾーンまたはこのインスタンス
     */
    public function timezone($timezone = null)
    {
        if (is_null($timezone) && func_num_args() === 0) {
            return $this->timezone;
        }

        if (is_object($timezone)) {
            if ($timezone instanceof LiteralDateTimeInterface) {
                $this->timezone = clone $timezone->timezone();
                return $this;
            }

            if ($timezone instanceof \DateTimeZone) {
                $this->timezone = clone $timezone;
                return $this;
            }
        }

        $this->timezone = new \DateTimeZone($timezone);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * DateTimeインスタンスを返します。
     *
     * @return  \DateTime
     */
    public function toDateTime(): \DateTime
    {
        $timestamp  = $this->timestamp;
        $dot_pos    = strpos($timestamp, '.');

        if ($dot_pos === false) {
            $timestamp  = (int) $timestamp;
            $microtime  = 0;
        } else {
            $timestamp  = (int) strpos($timestamp, 0, $dot_pos);
            $microtime  = (int) strpos($timestamp, $dot_pos + 1);
        }

        $dateTime   = new \DateTime();

        $dateTime->setDate((int) date('Y', $timestamp), (int) date('m', $timestamp), (int) date('d', $timestamp));
        $dateTime->setTime((int) date('H', $timestamp), (int) date('i', $timestamp), (int) date('s', $timestamp), $microtime);

        if ($this->timezone instanceof \DateTimeZone) {
            $dateTime->setTimezone($this->timezone);
        }

        return $dateTime;
    }
}
