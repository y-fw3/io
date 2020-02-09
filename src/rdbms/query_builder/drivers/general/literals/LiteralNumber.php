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
 * 数値リテラルインスタンスクラス
 *
 * @method LiteralNumber|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralNumber|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralNumber with()
 */
class LiteralNumber implements
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

    //==============================================
    // property
    //==============================================
    /**
     * @var int|float   値
     */
    protected $value;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     *
     * @param   int|float|LiteralNumber 数値
     */
    protected function __construct($value)
    {
        $this->value($value);
    }

    /**
     * factory
     *
     * @param   int|float|LiteralNumber 数値
     * @return  LiteralNumber           数値リテラル
     */
    public static function factory($value): LiteralNumber
    {
        return new static($value);
    }

    //==============================================
    // property access
    //==============================================
    /**
     * 数値リテラルを取得・設定します。
     *
     * @param   null|int|float|LiteralNumber    $value  数値
     * @return  int|float|LiteralNumber         数値リテラルまたはこのインスタンス
     */
    public function value($value = null)
    {
        if (is_null($value) && func_num_args() === 0) {
            return $this->value;
        }

        if ($value instanceof LiteralNumber) {
            $value = $value->value();
        }

        if (is_int($value)) {
            $this->value = $value;
            return $this;
        }

        if (is_float($value)) {
            $this->value = $value;
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました。', ['value' => $value]);
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
        return BuildResult::factory((string) $this->value, [], []);
    }
}
