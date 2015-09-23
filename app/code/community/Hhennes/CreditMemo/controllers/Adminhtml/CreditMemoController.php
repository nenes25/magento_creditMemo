<?php
/**
 * Hhennes_CreditMemo : Admin Controller
 */
class Hhennes_CreditMemo_Adminhtml_CreditMemoController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Annulation d'un avoir
     */
    public function cancelAction()
    {

        //Récupération de l'avoir
        $creditMemoId = $this->getRequest()->getParam('id');

        //Chargement de l'avoir
        $creditMemo = Mage::getModel('sales/order_creditmemo')->load($creditMemoId);

        //Si l'avoir n'est pas encore annulé
        if ( $creditMemo->getState() != 3) {

            try {
                $creditMemo->cancel(); //Annulation des remboursements
                $creditMemo->setState(Mage_Sales_Model_Order_Creditmemo::STATE_CANCELED); //Changement du statut de l'avoir
                $creditMemo->save(); //Sauvegarde de l'avoir
                $this->_cancelRefundOrder($creditMemo->getOrder()); //Annulation des données de remboursement sur la commande

            } catch ( Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('adminhtml/sales_creditmemo/view',array('creditmemo_id'=> $creditMemoId));
            }

            Mage::getSingleton('adminhtml/session')->addSuccess('Credit Memo annule avec succes');
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError('Credit Memo deja annule');
        }

        $this->_redirect('adminhtml/sales_creditmemo/view',array('creditmemo_id'=> $creditMemoId));
    }

    /**
     * Suppression d'un avoir
     */
    public function deleteAction()
    {
        //Récupération de l'avoir
        $creditMemoId = $this->getRequest()->getParam('id');

        //Chargement de l'avoir
        $creditMemo = Mage::getModel('sales/order_creditmemo')->load($creditMemoId);

        //On annule l'avoir avant de le supprimer
        try {
            $creditMemo->cancel(); //Annulation des remboursements
            $this->_cancelRefundOrder($creditMemo->getOrder()); //Annulation des données de remboursement sur la commande
            $creditMemo->delete(); //Suppression de l'avoir
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('adminhtml/sales_creditmemo', array('creditmemo_id' => $creditMemoId));
        }

        Mage::getSingleton('adminhtml/session')->addSuccess('Credit Memo supprimé avec succes');
        $this->_redirect('adminhtml/sales_creditmemo', array('creditmemo_id' => $creditMemoId));
    }

    /**
     * Annulation des remboursement de la commande
     * @param type $order
     */
    protected function _cancelRefundOrder($order)
    {
        //On mets tous les champs relatifs au remboursement à NULL
        $order->setBaseDiscountRefunded(NULL);
        $order->setBaseShippingTaxRefunded(NULL);
        $order->setBaseSubtotalRefunded(NULL);
        $order->setBaseTaxRefunded(NULL);
        $order->setBaseTotalOfflineRefunded(NULL);
        $order->setBaseTotalOnlineRefunded(NULL);
        $order->setBaseTotalRefunded(NULL);
        $order->setBaseHiddenTaxRefunded(NULL);

        $order->setDiscountRefunded(NULL);
        $order->setShippingTaxRefunded(NULL);
        $order->setSubtotalRefunded(NULL);
        $order->setTaxRefunded(NULL);
        $order->setTotalOfflineRefunded(NULL);
        $order->setTotalOnlineRefunded(NULL);
        $order->setTotalRefunded(NULL);
        $order->setBaseHiddenTaxRefunded(NULL);

        $order->save();
    }
}
