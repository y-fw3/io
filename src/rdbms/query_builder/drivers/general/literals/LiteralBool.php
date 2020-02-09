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
 * 真偽値リテラルインスタンスクラス
 *
 * @method LiteralBool|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralBool|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method LiteralBool with()
 */
class LiteralBool implements
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
     * @var string  文字列表現：TRUE
     */
    public const STRING_REPRESENTATION_TRUE     = 'TRUE';

    /**
     * @var string  文字列表現：FALSE
     */
    public const STRING_REPRESENTATION_FALSE    = 'FALSE';

    //==============================================
    // property
    //==============================================
    /**
     * @var bool    値
     */
    protected bool $value;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     *
     * @param   bool|LiteralBool    $value  真偽値
     */
    protected function __construct($value)
    {
        $this->value($value);
    }

    /**
     * factory
     *
     * @param   bool|LiteralBool    $value  真偽値
     * @return  LiteralBool         真偽値リテラル
     */
    public static function factory($value): LiteralBool
    {
        return new static($value);
    }

    //==============================================
    // property access
    //==============================================
    /**
     * 真偽値リテラルを取得・設定します。
     *
     * @param   bool|LiteralBool    $value  真偽値
     * @return  bool|LiteralBool    真偽値リテラルまたはこのインスタンス
     */
    public function value($value = false)
    {
        if ($value === false && func_num_args() === 0) {
            return $this->value;
        }

        if ($value instanceof LiteralBool) {
            $value = $value->value();
        }

        if (!is_bool($value)) {
            UnavailableVarException::raise('使用できない型の値を渡されました。', ['value' => $value]);
        }

        $this->value = $value;
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
        return BuildResult::factory($this->value ? static::STRING_REPRESENTATION_TRUE : static::STRING_REPRESENTATION_FALSE, [], []);
    }
}
