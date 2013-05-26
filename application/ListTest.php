<?php
class ListTest extends VF_TestCase
{
    function doSetUp()
    {
        $this->switchSchema('make,model,trim,year,country,category1,category2');
    }

    function testShouldListVehicles()
    {
        $expectedVehicle = $this->createVehicle(array(
            'make'=>'Honda',
            'model'=>'Civic',
            'trim'=>'EX',
            'year'=>'2000',
            'country'=>'USA',
            'category1'=>'foo',
            'category2'=>'bar'
        ));

        $lister = new VF_Vehicle_Finder(new VF_Schema);
        $vehicles = $lister->findAll();

        $this->assertEquals(1, count($vehicles), 'should list 1 vehicle');
        $this->assertEquals('Honda Civic EX 2000 USA foo bar', $vehicles[0]->__toString(), 'should list the vehicle');
        //$this->assertEquals($expectedVehicle->getId(), $vehicles[0]->getId(), 'should have the vehicle id');
        return $this->markTestIncomplete();
    }
}