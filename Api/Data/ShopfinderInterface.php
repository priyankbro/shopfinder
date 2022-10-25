<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Api\Data;

interface ShopfinderInterface
{

    const IDENTIFIER = 'identifier';
    const POSTAL_CODE = 'postal_code';
    const STATE = 'state';
    const COUNTRY = 'country';
    const ADDRESS_1 = 'address_1';
    const SHOPFINDER_ID = 'shopfinder_id';
    const SHOP_NAME = 'shop_name';
    const CITY = 'city';
    const SHOP_IMAGE = 'shop_image';

    /**
     * Get shopfinder_id
     * @return string|null
     */
    public function getShopfinderId();

    /**
     * Set shopfinder_id
     * @param string $shopfinderId
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setShopfinderId($shopfinderId);

    /**
     * Get shop_name
     * @return string|null
     */
    public function getShopName();

    /**
     * Set shop_name
     * @param string $shopName
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setShopName($shopName);

    /**
     * Get identifier
     * @return string|null
     */
    public function getIdentifier();

    /**
     * Set identifier
     * @param string $identifier
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get address_1
     * @return string|null
     */
    public function getAddress1();

    /**
     * Set address_1
     * @param string $address1
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setAddress1($address1);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setCity($city);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setCountry($country);

    /**
     * Get state
     * @return string|null
     */
    public function getState();

    /**
     * Set state
     * @param string $state
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setState($state);

    /**
     * Get postal_code
     * @return string|null
     */
    public function getPostalCode();

    /**
     * Set postal_code
     * @param string $postalCode
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setPostalCode($postalCode);

    /**
     * Get shop_image
     * @return string|null
     */
    public function getShopImage();

    /**
     * Set postal_code
     * @param string $shopImage
     * @return \Priyank\Shopfinder\Shopfinder\Api\Data\ShopfinderInterface
     */
    public function setShopImage($shopImage);
}

