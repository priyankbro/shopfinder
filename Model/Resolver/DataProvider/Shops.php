<?php


namespace Priyank\Shopfinder\Model\Resolver\DataProvider;

use Priyank\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory;

class Shops
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Shops constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {

        $this->collectionFactory = $collectionFactory;
    }

    public function getShops($args){
        $collection = $this->collectionFactory->create()
        ->setPageSize($args['pageSize'])
        ->setCurPage($args['currentPage']);

        return $collection->getData();
    }

    public function getShopByIdentifier(array $args)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('identifier', $args['identifier']);
        return $collection->getFirstItem()->getData();

    }

}