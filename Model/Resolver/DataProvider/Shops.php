<?php


namespace Priyank\Shopfinder\Model\Resolver\DataProvider;

use Priyank\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory;
use Priyank\Shopfinder\Model\ShopfinderFactory;
use Priyank\Shopfinder\Api\Data\ShopfinderInterface;
use Priyank\Shopfinder\Api\Data\ShopfinderInterfaceFactory;
use Priyank\Shopfinder\Api\ShopfinderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\Api\DataObjectHelper;


class Shops
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ShopfinderFactory
     */
    private $shopfinderFactory;

    /**
     * @var ShopfinderInterfaceFactory
     */
    private $shopfinderInterface;

    private $requiredFields = [
        ShopfinderInterface::SHOP_NAME
    ];
    /**
     * @var ShopfinderRepositoryInterface
     */
    private $shopfinderRepository;
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * Shops constructor.
     * @param CollectionFactory $collectionFactory
     * @param ShopfinderFactory $shopfinderFactory
     * @param ShopfinderInterfaceFactory $shopfinderInterface
     * @param ShopfinderRepositoryInterface $shopfinderRepository
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ShopfinderFactory $shopfinderFactory,
        ShopfinderInterfaceFactory $shopfinderInterface,
        ShopfinderRepositoryInterface $shopfinderRepository,
        DataObjectHelper $dataObjectHelper
    )
    {

        $this->collectionFactory = $collectionFactory;
        $this->shopfinderFactory = $shopfinderFactory;
        $this->shopfinderInterface = $shopfinderInterface;
        $this->shopfinderRepository = $shopfinderRepository;
        $this->dataObjectHelper = $dataObjectHelper;
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

    public function saveShop(array $args){
        try {
            $this->validateData($args);
            $shop = $this->saveStore($this->createShop($args));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }

    /**
     * Guard function to handle bad request.
     * @param array $args
     * @throws LocalizedException
     */
    private function validateData($args){

        foreach($this->requiredFields as $field){
            if (!isset($args['input'][$field])) {
                throw new LocalizedException(__($field . ' must be set'));
            }
        }
    }

    /**
     * Create a shop dto by given data array.
     *
     * @param array $arg
     * @return ShopfinderInterface
     * @throws CouldNotSaveException
     */
    private function createShop(array $arg)
    {
        $shop = $this->shopfinderInterface->create();
        $shop->load($arg['input']['identifier'], 'identifier');
        if(!$shop->getId()){
            $shop->setStoreId([0]);
        }
        $this->dataObjectHelper->populateWithArray(
            $shop,
            $arg['input'],
            ShopfinderInterface::class
        );

        return $shop;
    }

    /**
     * Persists the given Shop in the data base.
     * +
     * @param ShopfinderInterface $shop
     * @return ShopfinderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function saveStore(ShopfinderInterface $shop)
    {
        $this->shopfinderRepository->save($shop);
        return $shop;
    }

}