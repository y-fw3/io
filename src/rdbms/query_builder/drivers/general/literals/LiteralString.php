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
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * 文字列リテラルインスタンスクラス
 *
 * @method LiteralString|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralString|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralString with()
 */
class LiteralString implements
    // group
    LiteralInterface
{
    use LiteralTrait;

    //==============================================
    // const
    //==============================================
    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [];

    /**
     * @var string  句フォーマット
     */
    public const CLAUSE_FORMAT = '"%s"';

    /**
     * @var array   デフォルトでの文字エンコーディング検出順序
     */
    public const DETECT_ORDER_DEFAULT   = [
        'eucJP-win',
        'SJIS-win',
        'JIS',
        'ISO-2022-JP',
        'UTF-8',
        'ASCII',
    ];

    //==============================================
    // property
    //==============================================
    /**
     * @var string  文字列
     */
    protected string $string;

    /**
     * @var null|string エンコーディング
     */
    protected string $encoding;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     *
     * @param   string      $string     文字列
     * @param   null|string $encoding   有効な文字列エンコーディング
     */
    protected function __construct($string, $encoding = null)
    {
        $this->value($string, $encoding);
    }

    /**
     * factory
     *
     * @param   string      $string     文字列
     * @param   null|string $encoding   有効な文字列エンコーディング
     * @return  LiteralString   文字列リテラル
     */
    public static function factory($string, $encoding = null): LiteralString
    {
        return new static($string, $encoding);
    }

    //==============================================
    // property access
    //==============================================
    /**
     * 文字列リテラルを取得・設定します。
     *
     * @param   null|string|LiteralString   $string     文字列
     * @param   null|string                 $encoding   有効な文字列エンコーディング
     * @return  string|LiteralString        文字列リテラルまたはこのインスタンス
     */
    public function value($string = null, $encoding = null)
    {
        if (is_null($string) && func_num_args() === 0) {
            return [
                'string'    => $this->string,
                'encoding'  => $this->encoding,
            ];
        }

        if (is_array($string)) {
            $string       = current($string);
            $encoding   = $encoding ?? next($string);
        } elseif ($string instanceof LiteralString) {
            $encoding   = $encoding ?? $string->encoding();
            $string     = $string->string();
        }

        $this->string($string);
        $this->encoding($encoding);

        return $this;
    }

    /**
     * 文字列を取得・設定します。
     *
     * @param   null|string $string     文字列
     * @return  string|LiteralString    文字列またはこのインスタンス
     */
    public function string($string = null)
    {
        if ($string === false && func_num_args() === 0) {
            return $this->string;
        }

        if ($string instanceof LiteralString) {
            $string = $this->string();
        }

        if (!is_string($string)) {
            UnavailableVarException::raise('使用できない型の値を渡されました。', ['string' => $string]);
        }

        $this->string = $string;
        return $this;
    }

    /**
     * エンコーディングを取得・設定します。
     *
     * @param   null|string $encoding   エンコーディング
     * @return  string|LiteralString    エンコーディングまたはこのインスタンス
     */
    public function encoding($encoding = null)
    {
        static $mb_list_encodings;
        if (!isset($mb_list_encodings)) {
            $mb_list_encodings = mb_list_encodings();
        }

        if (is_null($encoding) && func_num_args() === 0) {
            return $this->encoding;
        }

        if ($encoding instanceof LiteralString) {
            $encoding = $this->encoding();
        }

        if (is_null($encoding)) {
            $encoding   = mb_internal_encoding();
        }

        if (!in_array($encoding, $mb_list_encodings, true)) {
            UnavailableVarException::raise('使用できないエンコーディングを渡されました。', ['encoding' => $encoding]);
        }

        $this->encoding = $encoding;
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResult
    {
        $table = $this->closestTable();
        if (!is_null($table)) {
            $string = $table->driver()->escape($this->string, $this->encoding);
        } else {
            if (!mb_check_encoding($this->string, $this->encoding)) {
                $string_encoding = mb_detect_encoding($this->string, static::DETECT_ORDER_DEFAULT);
                if ($string_encoding === false) {
                    UnavailableVarException::raise('使用できないエンコーディングの文字列です。エンコーディングの検出に失敗しているため、base64encodeした値を出力します。', ['string' => base64encode($this->string), 'encoding' => $this->encoding]);
                }
                UnavailableVarException::raise('使用できないエンコーディングの文字列です。', ['string' => mb_convert_encoding($this->string, $this->encoding, $string_encoding), 'test_encoding' => $string_encoding, 'encoding' => $this->encoding]);
            }

            $string = str_replace('"', '""', $this->string);
        }
        return BuildResult::factory(sprintf(static::CLAUSE_FORMAT, $string), [], []);
    }
}
