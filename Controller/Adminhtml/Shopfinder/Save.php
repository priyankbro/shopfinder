<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Priyank\Shopfinder\Controller\Adminhtml\Shopfinder;

use Priyank\Shopfinder\Model\Shopfinder;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    /**
     * @var Shopfinder
     */
    private $shopfinder;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param Shopfinder $shopfinder
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        Shopfinder $shopfinder
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->shopfinder = $shopfinder;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('shopfinder_id');
        
            $model = $this->shopfinder->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Shopfinder no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);

            if (isset($data['shop_image'][0]['name']) && !empty($data['shop_image'][0]['name'])) {
                $shopImage = $data['shop_image'][0]['name'];
                $model->setShopImage($shopImage);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Shopfinder.'));
                $this->dataPersistor->clear('priyank_shopfinder_shopfinder');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['shopfinder_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Shopfinder.'));
            }
        
            $this->dataPersistor->set('priyank_shopfinder_shopfinder', $data);
            return $resultRedirect->setPath('*/*/edit', ['shopfinder_id' => $this->getRequest()->getParam('shopfinder_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

