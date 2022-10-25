<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Model\ResourceModel\Shopfinder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'shopfinder_id';

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param null $connection
     * @param AbstractDb $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        $connection = null,
        AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Priyank\Shopfinder\Model\Shopfinder::class,
            \Priyank\Shopfinder\Model\ResourceModel\Shopfinder::class
        );
        $this->_map['fields']['store_id'] = 'store_table.store_id';
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     * @SuppressWarnings(PHPMD.Ecg.Sql.SlowQuery)
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('priyank_shopfinder_store')],
                'main_table.shopfinder_id = store_table.shop_id',
                []
            )
                // @codingStandardsIgnoreStart
                ->group('main_table.shopfinder_id');
            // @codingStandardsIgnoreEnd
        }
        parent::_renderFiltersBefore();
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, true);
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add filter by store
     *
     * @param int|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Store) {
                $store = [$store->getId()];
            }
            if (!is_array($store)) {
                $store = [$store];
            }
            if ($withAdmin) {
                $store[] = Store::DEFAULT_STORE_ID;
            }
            $this->addFilter('store_id', ['in' => $store], 'public');
        }
        return $this;
    }

    /**
     * after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('priyank_shopfinder_store', 'shopfinder_id');
        return parent::_afterLoad();
    }

    /**
     * @param $tableName
     * @param $linkField
     */
    protected function performAfterLoad($tableName, $linkField)
    {
        $linkedIds = $this->getColumnValues($linkField);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['store_table' => $this->getTable($tableName)])
                ->where('store_table.shop_id' . ' IN (?)', $linkedIds);
            // @codingStandardsIgnoreStart
            $result = $connection->fetchAll($select);
            // @codingStandardsIgnoreEnd
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData['shop_id']][] = $storeData['store_id'];
                }
                foreach ($this as $item) {
                    $linkedId = $item->getData($linkField);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('store_id', $storesData[$linkedId]);
                }

            }
        }
    }
}

