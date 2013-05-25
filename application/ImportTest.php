<?php
class ImportTest extends PHPUnit_Framework_TestCase
{
    function testShouldImport()
    {
        $generator = new VF_Schema_Generator;
        $generator->dropExistingTables();
        $generator->execute(array('make','model','trim','year','country','category1','category2'));

        VF_Singleton::getInstance()
            ->setReadAdapter(Zend_Registry::get('db'));

        $import = "make,model,trim,year,country,category1,category2
Goldoni,140,Base,1992,USA,Agricultural Equipment,2-Wheel Tractor
Goldoni,140,Base,1993,USA,Agricultural Equipment,2-Wheel Tractor
Goldoni,140,Base,1990,USA,Agricultural Equipment,2-Wheel Tractor
Goldoni,140,Base,1994,USA,Agricultural Equipment,2-Wheel Tractor";

        $file = sys_get_temp_dir() . '/'. uniqid();
        file_put_contents($file,$import);

        $importer = new VF_Import_VehiclesList_CSV_Import($file);
        $importer->import();

        unlink($file);

        $finder = new VF_Vehicle_Finder(new VF_Schema);
        $exists = $finder->vehicleExists(array(
            'make'=>'Goldoni',
            'model'=>'140',
            'trim'=>'Base',
            'year'=>'1992',
            'country'=>'USA',
            'category1'=>'Agricultural Equipment'
        ));
        $this->assertTrue($exists);
    }
}