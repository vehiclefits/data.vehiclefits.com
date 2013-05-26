<?php
class BrowseController extends Zend_Controller_Action
{
    protected $selection;

    function indexAction()
    {
        Zend_Layout::getMvcInstance()->setLayout('browse');
        $this->view->selected_vehicle = $this->selection();

        $this->view->request = $this->getRequest();
        $this->render('sidebar','sidebar',true);

        $this->view->count = $this->count();
        $this->view->vehicles_list = $this->listVehicles();
        $this->view->paginator = $this->paginator();
        $this->render('index','default',false);
    }

    function selection()
    {
        if($this->selection) {
            return $this->selection;
        }
        $this->selection = $this->flexibleSearch()
            ->vehicleSelection()
            ->getFirstVehicle();
        return $this->selection;
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
        return 25;
    }

    function page()
    {
        return $this->_getParam('page',1);
    }
}