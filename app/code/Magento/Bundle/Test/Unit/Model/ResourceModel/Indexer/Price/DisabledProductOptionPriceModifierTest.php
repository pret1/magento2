<?php

declare(strict_types=1);

namespace Magento\Bundle\Test\Unit\Model\ResourceModel\Indexer\Price;

use Magento\Bundle\Model\Product\SelectionProductsDisabledRequired;
use Magento\Catalog\Model\Config;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Bundle\Model\ResourceModel\Selection as BundleSelection;
use Magento\Catalog\Model\ResourceModel\Product\Indexer\Price\IndexTableStructure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Bundle\Model\ResourceModel\Indexer\Price\DisabledProductOptionPriceModifier;

class DisabledProductOptionPriceModifierTest extends TestCase
{
    /**
     * @var ResourceConnection|MockObject
     */
    private $resourceMock;

    /**
     * @var SelectionProductsDisabledRequired|MockObject
     */
    private $selectionProductsDisabledRequired;

    /**
     * @var Config|MockObject
     */
    private $config;

    /**
     * @var MetadataPool|MockObject
     */
    private $metadataPoolMock;

    /**
     * @var BundleSelection|MockObject
     */
    private $bundleSelectionMock;

    /**
     * @var DisabledProductOptionPriceModifier
     */
    private $disabledProductOptionPriceModifier;

    // Helper function creates a nicer interface for mocking Generator behavior
    protected function generate(array $yield_values)
    {
        return $this->returnCallback(function() use ($yield_values) {
            foreach ($yield_values as $value) {
                yield $value;
            }
        });
    }

    protected function setUp(): void
    {
        $this->resourceMock = $this->createMock(ResourceConnection::class);
        $this->selectionProductsDisabledRequired = $this->createMock(SelectionProductsDisabledRequired:: class);
        $this->config = $this->createMock(Config::class);
//        $this->config = $this->getMockBuilder(Config::class)
//            ->disableOriginalConstructor()
//            ->getMock();
        $this->metadataPoolMock = $this->createMock(MetadataPool::class);
        $this->bundleSelectionMock = $this->createMock(BundleSelection::class);

        $this->disabledProductOptionPriceModifier = new DisabledProductOptionPriceModifier(
          $this->resourceMock,
          $this->config,
          $this->metadataPoolMock,
          $this->bundleSelectionMock,
          $this->selectionProductsDisabledRequired
        );
    }

    public function testModifyPrice(): void
    {
        /** @var AdapterInterface|MockObject $connectionMock */
        $connectionMock = $this->getMockForAbstractClass(AdapterInterface::class);
        $this->resourceMock->expects($this->any())->method('getConnection')->willReturn($connectionMock);
//        $connection = $this->resourceMock->expects($this->any())->method('getConnection')->willReturn($connectionMock);

        /** @var Select|MockObject $selectMock */
        $selectMock = $this->createMock(Select::class);
        $connectionMock->expects($this->any())->method('select')->willReturn($selectMock);
        $selectMock->expects($this->any())->method('from')->willReturnSelf();
        $selectMock->expects($this->any())->method('where')->willReturnSelf();
        $selectMock->expects($this->any())->method('deleteFromSelect')->willReturn('some_string');
        $connectionMock->expects($this->any())->method('getTableName')->willReturn('some_table_name');

        $this->selectionProductsDisabledRequired->expects($this->any())->method('getChildProductIds')->willReturn([]);

        /** @var Zend_Db_Statement|MockObject $zendDbStatement */
        $zendDbStatement = $this->getMockForAbstractClass(\Zend_Db_Statement_Interface::class);
        $selectMock->expects($this->any())->method('query')->willReturn($zendDbStatement);
//        $zendDbStatement->expects($this->any())->method('fetchColumn')->will($this->generate([5,6,7,8]));
        $zendDbStatement->expects($this->any())->method('fetchColumn')->willReturn(1,2,3);
//        $selectMock->expects($this->any())->method('fetchColumn')->willReturn('One_value_from_the_next_row');


//        $selectMock->expects($this->any())->method('fetchCol')->with($connectionMock)->willReturn([1, 2, 3]);
        $connectionMock->expects($this->any())->method('fetchCol')->with($connectionMock)->willReturn([1, 2, 3]);


        /** @var IndexTableStructure|MockObject $priceTable */
        $priceTable = $this->createMock(IndexTableStructure::class);

        $this->disabledProductOptionPriceModifier->modifyPrice($priceTable, $entityIds = []);
    }

}
