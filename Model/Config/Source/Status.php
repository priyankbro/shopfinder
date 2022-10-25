<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Priyank\Shopfinder\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
     /**
     * @return array
     */
    public function toOptionArray()
    {
            $options = [
                0 => [
                    'label' => 'Disable',
                    'value' => '0'
                ],
                1 => [
                    'label' => 'Enable',
                    'value' => '1'
                ],
            ];
            
            return $options;
      
    }
}