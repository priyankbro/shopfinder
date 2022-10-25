<?php


namespace Priyank\Shopfinder\Model\Resolver;


use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;

class Shops implements ResolverInterface
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
        $shopsData = $this->shopsDataProvider->getShops($args);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectManager->create(\Psr\Log\LoggerInterface::class)->info("hello", $args);
        return [
            'items' => $shopsData

        ];
        return $shopsData;
    }
}