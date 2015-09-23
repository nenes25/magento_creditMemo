<?php

/**
 * Hhennes_CreditMemo : Observer
 */
class Hhennes_CreditMemo_Model_Observer
{

    /**
     * Ajout de boutons pour Annuler et Supprimer les avoir
     * @param Varien_Event_Obsever $observer
     */
    public function addCreditMemoButtons($observer)
    {
        $container = $observer->getBlock();

        //Ajout du boutons sur la page de visulalisation d'un avoir
        if ($container !== null && $container->getType() == 'adminhtml/sales_order_creditmemo_view') {

            //Ajout du bouton d'annulation
            $cancelButtonsDatas = array(
                'label' => Mage::helper('hhennes_creditmemo')->__('Cancel'),
                'onclick' => 'setLocation(\''.Mage::getUrl('hhennes_creditmemo/adminhtml_CreditMemo/cancel/',
                    array('id' => Mage::app()->getRequest()->getParam('creditmemo_id'))).'\')'
            );

            $container->addButton('creditmemo_cancel', $cancelButtonsDatas);

            //Ajout du bouton de suppression
            $deteleButtonsDatas = array(
                'label' => Mage::helper('hhennes_creditmemo')->__('Delete'),
                'class' => 'delete',
                'onclick' => 'deleteConfirm(\'Supprimer Avoir ?\',\''.Mage::getUrl('hhennes_creditmemo/adminhtml_CreditMemo/delete/',
                    array('id' => Mage::app()->getRequest()->getParam('creditmemo_id'))).'\')'
            );

            $container->addButton('creditmemo_delete', $deteleButtonsDatas);
        }
    }

}
?>
