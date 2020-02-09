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

namespace fw3\io\rdbms\dbi;

use fw3\io\rdbms\dbi\interfaces\specs\SpecInterface;
use fw3\io\rdbms\dbi\drivers\disconnected\DisconnectedDriver;

/**
 * DBIファクトリ
 */
abstract class Dbi
{
    /**
     * @var ?DbiCollection  DBIコレクション
     */
    protected static $specCollection = null;

    /**
     * @var ?DisconnectedDriver 未接続時のドライバ
     */
    protected static $dicconnectedDriver = null;

    /**
     * 未接続時のドライバを返します。
     *
     * @return  DisconnectedDriver 未接続時のドライバ
     */
    public static function getDisconnectedDriver(): DisconnectedDriver
    {
        !is_null(static::$dicconnectedDriver) ?: static::$dicconnectedDriver = DisconnectedDriver::factory(['name' => 'disconnected']);
        return static::$dicconnectedDriver;
    }

    public static function generatePdoSpec($connection_name, $spec)
    {
    }

    public static function generateMySqliSpec($connection_name, $spec)
    {
    }

    public static function appendPdoSpec($connection_name, $spec)
    {
        !is_null(static::$specCollection) ?: static::$specCollection = DbiCollection::factory();
        return static::$specCollection->append($connection_name, $spec);
    }

    public static function appendSpec($connection_name, $spec)
    {
        if (is_object($connection_name)) {
            if ($connection_name instanceof SpecInterface) {
                return ;
            }
            return ;
        }

        if (is_array($connection_name)) {
            $spec               = $connection_name;
            $connection_name    = $connection_name['name'] ?? null;
        }

        if (is_array($spec)) {
            if (isset($spec['driver'])) {
                $spec['name'] = $connection_name;
                $spec = DisconnectedDriver::generateSpec($spec);
            }
        }

        !is_null(static::$specCollection) ?: static::$specCollection = DbiCollection::factory();
        return static::$specCollection->append($connection_name, $spec);
    }

    public static function collection()
    {
        return static::$specCollection;
    }

    public static function get($connection_name)
    {
        !is_null(static::$specCollection) ?: static::$specCollection = DbiCollection::factory();
        return static::$specCollection->get($connection_name);
    }

    public static function set($connection_name, $spec)
    {
        !is_null(static::$specCollection) ?: static::$specCollection = DbiCollection::factory();
        return static::$specCollection->set($connection_name, $spec);
    }
}
