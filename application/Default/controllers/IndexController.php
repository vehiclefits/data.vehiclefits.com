<?php
class IndexController extends Zend_Controller_Action
{

    function indexAction()
    {
        $this->view->request = $this->getRequest();
        $this->view->selected_vehicle = $this->selection();
        $this->view->count = $this->count();
        $this->view->vehicles_list = $this->listVehicles();
        $this->view->paginator = $this->paginator();
    }

    function selection()
    {
        return $this->flexibleSearch()
            ->vehicleSelection()
            ->getFirstVehicle();
    }

    function hasVehicleSelection()
    {
        return !$this->flexibleSearch()
            ->vehicleSelection()
            ->isEmpty();
    }

    function listVehicles()
    {
        if(!$this->selection()) {
            return $this->vehicleFinder()
                ->findAll($this->perPage(), $this->offset());
        }

        $params = $this->selection()->toValueArray();
        return $this->vehicleFinder()
            ->findByLevelIds($params, false, $this->perPage(), $this->offset());
    }

    /** @return VF_FlexibleSearch */
    function flexibleSearch()
    {
        $search = new VF_FlexibleSearch(new VF_Schema, $this->getRequest());
        $search->setConfig(VF_Singleton::getInstance()->getConfig());
        return $search;
    }

    function vehicleFinder()
    {
        $finder = new VF_Vehicle_Finder(new VF_Schema());
        return $finder;
    }

    function paginator()
    {
        $adapter = new Zend_Paginator_Adapter_Null($this->count());
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($this->perPage());
        $paginator->setCurrentPageNumber($this->page());
        return $paginator;
    }

    function count()
    {
        if(!$this->selection()) {
            return $this->vehicleFinder()->countAll();
        }

        $params = $this->selection()->toValueArray();
        return $this->vehicleFinder()
            ->countByLevelIds($params);
    }

    function offset()
    {
        return $this->page() * $this->perPage() - $this->perPage();
    }

    function perPage()
    {
        return 50;
    }

    function page()
    {
        return $this->_getParam('page',1);
    }
}