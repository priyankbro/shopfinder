<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Model;

use Magento\Framework\Model\AbstractModel;
use Priyank\Shopfinder\Api\Data\ShopfinderInterface;

class Shopfinder extends AbstractModel implements ShopfinderInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Priyank\Shopfinder\Model\ResourceModel\Shopfinder::class);
    }

    /**
     * @inheritDoc
     */
    public function getShopfinderId()
    {
        return $this->getData(self::SHOPFINDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setShopfinderId($shopfinderId)
    {
        return $this->setData(self::SHOPFINDER_ID, $shopfinderId);
    }

    /**
     * @inheritDoc
     */
    public function getShopName()
    {
        return $this->getData(self::SHOP_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setShopName($shopName)
    {
        return $this->setData(self::SHOP_NAME, $shopName);
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @inheritDoc
     */
    public function getAddress1()
    {
        return $this->getData(self::ADDRESS_1);
    }

    /**
     * @inheritDoc
     */
    public function setAddress1($address1)
    {
        return $this->setData(self::ADDRESS_1, $address1);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @inheritDoc
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * @inheritDoc
     */
    public function setState($state)
    {
        return $this->setData(self::STATE, $state);
    }

    /**
     * @inheritDoc
     */
    public function getPostalCode()
    {
        return $this->getData(self::POSTAL_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setPostalCode($postalCode)
    {
        return $this->setData(self::POSTAL_CODE, $postalCode);
    }
}

