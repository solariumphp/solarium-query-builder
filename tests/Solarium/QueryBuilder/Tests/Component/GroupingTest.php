<?php
/**
 * Copyright 2016 Bram Gerritsen. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 */

namespace Solarium\QueryBuilder\Tests\Component;

use Solarium\QueryType\Select\Query\Query;
use Solarium\QueryBuilder\Component\Grouping;
use Solarium\QueryType\Select\Query\Component\Grouping as Component;
use Solarium\Core\Client\Request;

class GroupingTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildQuery()
    {
        $builder = new Grouping();

        $request = new Request();
        $request->setParams(
            array(
                'group' => 'true',
                'group.field' => array('fieldA', 'fieldB'),
                'group.query' => array('cat:1', 'cat:2'),
                'group.limit' => 12,
                'group.offset' => 2,
                'group.sort' => 'score desc',
                'group.main' => 'true',
                'group.ngroups' => 'false',
                'group.cache.percent' => 50,
                'group.truncate' => 'true',
                'group.func' => 'log(foo)',
                'group.facet' => 'true',
                'group.format' => 'grouped',
            )
        );

        $query = new Query();
        $builder->buildQuery($query, $request);

        /** @var Component $component */
        $component = $query->getComponent('grouping');

        $this->assertEquals(array('fieldA', 'fieldB'), $component->getFields());
        $this->assertEquals(array('cat:1', 'cat:2'), $component->getQueries());
        $this->assertEquals(12, $component->getLimit());
        $this->assertEquals(2, $component->getOffset());
        $this->assertEquals('score desc', $component->getSort());
        $this->assertTrue($component->getMainResult());
        $this->assertFalse($component->getNumberOfGroups());
        $this->assertEquals(50, $component->getCachePercentage());
        $this->assertTrue($component->getTruncate());
        $this->assertEquals('log(foo)', $component->getFunction());
        $this->assertTrue($component->getFacet());
        $this->assertEquals('grouped', $component->getFormat());
    }
}
