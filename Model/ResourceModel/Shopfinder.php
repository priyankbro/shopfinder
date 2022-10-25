<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Priyank\Shopfinder\Model\ImageUploader;

class Shopfinder extends AbstractDb
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * Shopfinder constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param null $connectionName
     */
    public function __construct(Context $context, StoreManagerInterface $storeManager, ImageUploader $imageUploader, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
        $this->imageUploader = $imageUploader;
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('priyank_shopfinder_shopfinder', 'shopfinder_id');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->getIsUniqueIdentifierToStores($object)) {
            throw new LocalizedException(
                __('A identifier with the same properties already exists in the selected store.')
            );
        }
        return $this;
    }

    /**
     * Check for unique of identifier of block to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueIdentifierToStores(AbstractModel $object)
    {
        $stores = $this->storeManager->isSingleStoreMode()
            ? [Store::DEFAULT_STORE_ID]
            : (array)$object->getData('store_id');

        $select = $this->getConnection()->select()
            ->from(['sf' => $this->getMainTable()])
            ->join(
                ['sfs' => $this->getTable('priyank_shopfinder_store')],
                'sf.shopfinder_id = sfs.shop_id',
                []
            )
            ->where('sf.identifier = ?  ', $object->getData('identifier'))
            ->where('sfs.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('sf.shopfinder_id <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }



    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table = $this->getTable('priyank_shopfinder_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = ['shop_id = ?' => (int)$object->getId(), 'store_id IN (?)' => $delete];

            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];

            foreach ($insert as $storeId) {
                $data[] = ['shop_id' => (int)$object->getId(), 'store_id' => (int)$storeId];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }

        $this->saveImage($object);


        return parent::_afterSave($object);
    }

    private function saveImage($object){
        if($object->hasData('shop_image') && $object->dataHasChangedFor('shop_image')){
            $shopImage = $object->getData('shop_image');
            $this->imageUploader->moveFileFromTmp($shopImage);
        }
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $shopId
     * @return array
     */
    public function lookupStoreIds($shopId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('priyank_shopfinder_store'),
            'store_id'
        )->where(
            'shop_id = ?',
            (int)$shopId
        );

        return $connection->fetchCol($select);
    }
}