<?php
class APITest extends VF_TestCase
{
    function setUp()
    {
        $this->switchSchema('make,model,trim,year,country,category1,category2');
    }

    function tearDown() {}

    function testShouldUploadFileAnonymously()
    {
        $url = 'http://vfdata.localhost/api/upload';
        $data = "year,make,model\n";
        $data .= "2000,Honda,Civic";

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $data,
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $this->assertTrue($result>0, 'should return an ID for the upload');
        $this->assertEquals($data, file_get_contents('var/uploads/'.$result), 'should store the file in var/uploads');
    }
}