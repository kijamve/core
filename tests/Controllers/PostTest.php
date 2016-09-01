<?php

namespace Controllers;

use TypeRocket\Controllers\WPPostController;
use TypeRocket\Http\Request;
use TypeRocket\Http\Response;
use TypeRocket\Models\WPPost;

class PostTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdateWithMetaMethod()
    {
        $_POST['tr']['post_title'] = 'Hello World! Updated by controller!';
        $_POST['tr']['meta_key'] = 'Hello World! Updated by controller!';
        $_POST = wp_slash($_POST);

        $request = new Request();
        $response = new Response();
        $controller = new WPPostController( $request, $response );
        $controller->update( 1 );

        $model = new WPPost();
        $meta = $model->findById( $response->getData('resourceId') )->getFieldValue('meta_key');

        $this->assertTrue( $response->getData('resourceId') == 1 );
        $this->assertTrue( $meta == $request->getFields('meta_key') );
    }

}