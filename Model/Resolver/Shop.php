<?php


namespace Priyank\Shopfinder\Model\Resolver;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class Shop implements ResolverInterface
{

    private $shopsDataProvider;

    /**
     * @param DataProvider\Shops $shopsDataProvider
     */
    public function __construct(DataProvider\Shops $shopsDataProvider)
    {
        $this->shopsDataProvider = $shopsDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if(!isset($args['identifier'])){
            throw new LocalizedException(__('Identifier is Required'));
        }
        $shopsData = $this->shopsDataProvider->getShopByIdentifier($args);
        if(empty($shopsData)){
            throw new LocalizedException(__('No records found'));
        }
        return $shopsData;
    }
}