<?php

namespace fw3\tests\io\rdbms\query_builder;

use fw3\tests\io\rdbms\AbstractTestCase;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

abstract class AbstractQueryBuilderTestCase extends AbstractTestCase
{
    public function assertBuildResult(array $expected, BuildResultInterface $actual)
    {
        $this->assertSame($expected['clause'] ?? null, $actual->getClause());
        $this->assertSame($expected['conditions'] ?? null, $actual->getConditions());
        $this->assertSame($expected['values'] ?? null, $actual->getValues());
    }
}
