<?php
class IndexController extends Zend_Controller_Action
{

    function indexAction()
    {
        $this->view->request = $this->getRequest();
        $this->view->selected_vehicle = $this->flexibleSearch()->vehicleSelection()->getFirstVehicle();
    }

    function hasVehicleSelection()
    {
        return !$this->flexibleSearch()->vehicleSelection()->isEmpty();
    }

    function vafProductIds()
    {
        $this->flexibleSearch()->storeFitInSession();
        $productIds = $this->flexibleSearch()->doGetProductIds();
        return $productIds;
    }

    /** @return VF_FlexibleSearch */
    function flexibleSearch()
    {
        $search = new VF_FlexibleSearch(new VF_Schema, $this->getRequest());
        $search->setConfig(VF_Singleton::getInstance()->getConfig());
        return $search;
    }
}