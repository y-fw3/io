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

namespace fw3\io\rdbms\dbi\interfaces\specs;

/**
 * Specインターフェース特性
 */
trait SpecTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ?string 対象とするドライバ名
     */
    protected ?string $driver   = null;

    /**
     * @var ?string 接続名
     */
    protected ?string $name = null;

    /**
     * @var ?string 接続先ホスト名
     */
    protected ?string $host = null;

    /**
     * @var ?int    接続先ポート名
     */
    protected ?int $port    = null;

    /**
     * @var ?string 接続先データベース名
     */
    protected ?string $databaseName = null;

    /**
     * @var ?string 接続ユーザ名
     */
    protected ?string $userName = null;

    /**
     * @var ?string 接続ユーザパスワード
     */
    protected ?string $userPassword = null;

    /**
     * @var ?mixed  既存コネクションを流用する場合のコネクション
     */
    protected $connection   = null;

    //==============================================
    // factory
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
    }

    /**
     * factory
     *
     * @return  SpecInterface   このインスタンス
     */
    public static function factory(): SpecInterface
    {
        return new static();
    }

    //==============================================
    // property access
    //==============================================
    public function driver($driver = null)
    {
        if (is_null($driver) && func_num_args() === 0) {
            return $this->driver;
        }
        $this->driver = $driver;
        return $this;
    }

    public function name($name = null)
    {
        if (is_null($name) && func_num_args() === 0) {
            return $this->name;
        }
        $this->name = $name;
        return $this;
    }

    public function host($host = null)
    {
        if (is_null($host) && func_num_args() === 0) {
            return $this->host;
        }
        $this->host = $host;
        return $this;
    }

    public function port($port = null)
    {
        if (is_null($port) && func_num_args() === 0) {
            return $this->port;
        }
        $this->port = $port;
        return $this;
    }

    public function databaseName($databaseName = null)
    {
        if (is_null($databaseName) && func_num_args() === 0) {
            return $this->databaseName;
        }
        $this->databaseName = $databaseName;
        return $this;
    }

    public function userName($userName = null)
    {
        if (is_null($userName) && func_num_args() === 0) {
            return $this->userName;
        }
        $this->userName = $userName;
        return $this;
    }

    public function userPassword($userPassword = null)
    {
        if (is_null($userPassword) && func_num_args() === 0) {
            return $this->userPassword;
        }
        $this->userPassword = $userPassword;
        return $this;
    }

    public function connection($connection = null)
    {
        if (is_null($connection) && func_num_args() === 0) {
            return $this->connection;
        }
        $this->connection = $connection;
        return $this;
    }
}
