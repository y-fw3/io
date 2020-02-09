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

namespace fw3\io\rdbms\query_builder\drivers\general\clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseInterface;
use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;


/**
 * Limit句
 */
class LimitClause implements
    ClauseInterface
{
    use ClauseTrait;

    protected ?int $offset      = null;
    protected ?int $rowCount    = null;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // constructor
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
     * @param   array           ...$arguments   引数
     * @return  SelectClause    Select文
     */
    public static function factory(...$arguments): LimitClause
    {
        return new static(...$arguments);
    }

    //==============================================
    // features
    //==============================================
    /**
     *
     * リミットとオフセットを設定します。
     *
     * @param   int|LimitClause|SelectStatement         $row_count  リミット
     * @param   null|int|LimitClause|SelectStatement    $offset     オフセット
     * @return  LimitClause このインスタンス
     */
    public function limit($row_count = null, $offset = null)
    {
        if (is_null($row_count) && func_num_args() === 0) {
            return $this;
        }

        $this->rowCount($row_count);

        if (is_null($offset) && func_num_args() === 0) {
            if ($row_count instanceof SelectStatement) {
                $this->offset($offset);
                return $this;
            }

            if ($row_count instanceof LimitClause) {
                $this->offset($offset);
                return $this;
            }
        }

        $this->offset($offset);
        return $this;
    }

    //==============================================
    // properties
    //==============================================
    public function offset($offset = null)
    {
        if (is_null($offset) && func_num_args() === 0) {
            return $this->offset;
        }

        if ($offset instanceof SelectStatement) {
            $offset = $offset->limit();
        }

        if ($offset instanceof LimitClause) {
            $offset = $offset->offset();
        }

        $this->offset = $offset;
        return $this;
    }

    public function rowCount($row_count)
    {
        if (is_null($row_count) && func_num_args() === 0) {
            return $this->rowCount;
        }

        if ($row_count instanceof SelectStatement) {
            $row_count = $row_count->limit();
        }

        if ($row_count instanceof LimitClause) {
            $row_count = $row_count->rowCount($row_count);
        }

        $this->rowCount = $row_count;
        return $this;
    }

    public function hasEmpty()
    {
        return is_null($this->rowCount);
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResultInterface    ビルド結果
     */
    public function build(): BuildResultInterface
    {
        $clause_stack   = [];
        $conditions     = [];
        $values         = [];

        $clause_stack[] = $this->rowCount;
        if (!is_null($this->offset)) {
            $clause_stack[] = 'OFFSET';
            $clause_stack[] = $this->offset;
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
