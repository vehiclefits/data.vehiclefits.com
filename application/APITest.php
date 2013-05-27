<?php
class APITest extends VF_TestCase
{
    function setUp()
    {
        VF_Singleton::getInstance()->setReadAdapter(new VF_TestDbAdapter(array(
            'dbname' => VAF_DB_NAME,
            'username' => VAF_DB_USERNAME,
            'password' => VAF_DB_PASSWORD
        )));
        $this->getReadAdapter()->query('truncate vfdata_user');
        $this->getReadAdapter()->query('truncate vfdata_uploads');
        $schemaGenerator = new VF_Schema_Generator();
        $schemaGenerator->dropExistingTables();
        $schemaGenerator->execute(array('year','make','model'));
        VF_Schema::$levels = null;
    }

    function tearDown()
    {

    }

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

    function testShouldUploadFileWithToken()
    {
        $this->getReadAdapter()->insert('vfdata_user',array(
            'id'=>1,
            'api_token'=>'token123'
        ));

        $url = 'http://vfdata.localhost/api/upload?token=token123';
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

        $upload_row = $this->getReadAdapter()->select()
            ->from('vfdata_uploads')
            ->where('id=?',$result)
            ->query()
            ->fetch();
        $this->assertEquals(1, $upload_row['user_id'], 'should associate upload to user based on his token');
    }

    function testShouldRejectDownloadWithBadToken()
    {
        $data = file_get_contents('http://vfdata.localhost/api/download?token=bad_token');
        $this->assertEquals('invalid token',$data);
    }

    function testShouldDownloadWithGoodToken()
    {
        $this->getReadAdapter()->insert('vfdata_user',array(
            'id'=>1,
            'api_token'=>'token123'
        ));

        $this->createVehicle(array('year'=>'2000','make'=>'Honda','model'=>'Civic'));

        $data = file_get_contents('http://vfdata.localhost/api/download?token=token123');
        $expected = "year,make,model
2000,Honda,Civic\n";

        $this->assertEquals($expected,$data,'should download data when using a good token');
    }

}