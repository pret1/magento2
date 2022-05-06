<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Review\Model\ResourceModel\Review\Summary;

use Magento\Framework\DB\Select;
use Magento\Review\Model\ResourceModel\Review\Summary\Collection;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests some functionality of the Product Review collection
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Collection|MockObject
     */
    protected Collection|MockObject $_model;

    protected function setUp(): void
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(Collection::class);
    }

    /**
     * @param array|int $storeId
     * @dataProvider storeIdDataProvider
     */
    public function testAddStoreFilter(array|int $storeId)
    {
        $expectedWhere = is_numeric($storeId) ? 'store_id = ?' : 'store_id IN (?)';

        $select = $this->createPartialMock(Select::class, ['where']);
        $select->expects( $this->any())
            ->method('where')
            ->with($this->equalTo($expectedWhere))
            ->willReturnSelf();

        $this->assertEquals($this->_model, $this->_model->addStoreFilter($storeId));
    }

    /**
     * @return array
     */
    public function storeIdDataProvider(): array
    {
        return [
            [1],
           [1, [1,2]]
        ];
    }
}
