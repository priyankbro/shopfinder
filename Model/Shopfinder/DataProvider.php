<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Model\Shopfinder;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Priyank\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider
{

    CONST IMAGE_DIR = 'shopfinder/image';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @inheritDoc
     */
    protected $collection;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;


    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            $this->getMedia($model);
        }
        $data = $this->dataPersistor->get('priyank_shopfinder_shopfinder');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('priyank_shopfinder_shopfinder');
        }
        //echo "<pre>";print_r($this->loadedData);die;
        return $this->loadedData;
    }


    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . SELF::IMAGE_DIR;
        $mediaUrl.= "/";
        return $mediaUrl;
    }

    public function getMedia($item)
    {
        if ($item->getData('shop_image')) {
            $url = $this->getMediaUrl() . $item->getData('shop_image');
            $this->loadedData[$item->getId()]['shop_image'] = [
                [
                    "name" => $item->getData('shop_image'),
                    "type" => 'image',
                    "url" => $url
                ]
            ];
        }
    }
}

