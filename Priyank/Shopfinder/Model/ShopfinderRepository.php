<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Priyank\Shopfinder\Api\Data\ShopfinderInterface;
use Priyank\Shopfinder\Api\Data\ShopfinderInterfaceFactory;
use Priyank\Shopfinder\Api\Data\ShopfinderSearchResultsInterfaceFactory;
use Priyank\Shopfinder\Api\ShopfinderRepositoryInterface;
use Priyank\Shopfinder\Model\ResourceModel\Shopfinder as ResourceShopfinder;
use Priyank\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory as ShopfinderCollectionFactory;

class ShopfinderRepository implements ShopfinderRepositoryInterface
{

    /**
     * @var Shopfinder
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceShopfinder
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ShopfinderCollectionFactory
     */
    protected $shopfinderCollectionFactory;

    /**
     * @var ShopfinderInterfaceFactory
     */
    protected $shopfinderFactory;


    /**
     * @param ResourceShopfinder $resource
     * @param ShopfinderInterfaceFactory $shopfinderFactory
     * @param ShopfinderCollectionFactory $shopfinderCollectionFactory
     * @param ShopfinderSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceShopfinder $resource,
        ShopfinderInterfaceFactory $shopfinderFactory,
        ShopfinderCollectionFactory $shopfinderCollectionFactory,
        ShopfinderSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->shopfinderFactory = $shopfinderFactory;
        $this->shopfinderCollectionFactory = $shopfinderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(ShopfinderInterface $shopfinder)
    {
        try {
            $this->resource->save($shopfinder);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the shopfinder: %1',
                $exception->getMessage()
            ));
        }
        return $shopfinder;
    }

    /**
     * @inheritDoc
     */
    public function get($shopfinderId)
    {
        $shopfinder = $this->shopfinderFactory->create();
        $this->resource->load($shopfinder, $shopfinderId);
        if (!$shopfinder->getId()) {
            throw new NoSuchEntityException(__('Shopfinder with id "%1" does not exist.', $shopfinderId));
        }
        return $shopfinder;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->shopfinderCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(ShopfinderInterface $shopfinder)
    {
        try {
            $shopfinderModel = $this->shopfinderFactory->create();
            $this->resource->load($shopfinderModel, $shopfinder->getShopfinderId());
            $this->resource->delete($shopfinderModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Shopfinder: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($shopfinderId)
    {
        return $this->delete($this->get($shopfinderId));
    }
}

