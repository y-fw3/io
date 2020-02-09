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

declare(strict_types=1);

namespace fw3\io\rdbms\query_builder\drivers\general\functions\strings;

use fw3\io\rdbms\query_builder\drivers\general\functions\traits\FunctionInterface;
use fw3\io\rdbms\query_builder\drivers\general\functions\traits\FunctionTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\DoUsePlaceholder;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * 文字列関数：CHAR
 */
class CharFunction implements
    FunctionInterface
{
    use FunctionTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'value'
    ];

    /**
     * @var string  絶対値を求める値
     */
    protected $value;

    /**
     * factory
     *
     * @param   array   ...$arguments       初期化引数
     * @return  TableReferenceExpression    このインスタンス
     */
    public static function factory(...$arguments): CharFunction
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments[0]   = $arguments[0]['value'];
            }
            !isset($arguments[0]) ?: $instance->value($arguments[0]);
        }
        return $instance;
    }

    public function value($value = null)
    {
        if (is_null($value) && func_num_args() === 0) {
            return $this->value;
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
     * @return  \fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface  ビルド結果
     */
    public function build(): BuildResultInterface
    {
        $clause     = '';
        $conditions = [];
        $values     = [];

        $clause_stack   = [];

        $clause_format  = null;

        if ($this->value instanceof Buildable) {
            $clause_format  = '%s';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->value->build()->merge($clause_stack, $conditions, $values);
            $clause_format  = 'char(%s)';
            $clause_stack   = [current($clause_stack)];
        } else {
            if ($this->doUsePlaceholder()) {
                $clause_format  = 'char(?)';
                $clause_stack   = [];
                $conditions[]   = $this->value;
            } else {
                $clause_format  = strpos((string) $this->value, '.') ? 'char(%f)' : 'char(%d)';
                $clause_stack   = [$this->value];
            }
        }

        $clause = vsprintf($clause_format, $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
