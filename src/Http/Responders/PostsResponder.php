<?php
namespace TypeRocket\Http\Responders;

use \TypeRocket\Http\Request,
    \TypeRocket\Http\Response,
    \TypeRocket\Registry;

class PostsResponder extends Responder
{

    /**
     * Respond to posts hook
     *
     * Detect the post types registered resource and run the Kernel
     * against that resource.
     *
     * @param $postId
     */
    public function respond( $postId )
    {
        if ( ! $id = wp_is_post_revision( $postId ) ) {
            $id = $postId;
        }

        $type       = get_post_type( $id );
        $resource   = Registry::getPostTypeResource( $type );
        $prefix     = ucfirst( $resource );

        $controller = "\\" . TR_APP_NAMESPACE . "\\Controllers\\{$prefix}Controller";
        $model      = "\\" . TR_APP_NAMESPACE . "\\Models\\{$prefix}";

        if ( empty($prefix) || ! class_exists( $controller ) || ! class_exists( $model )) {
            $resource = 'posts';
        }

        $request  = new Request( $resource, 'PUT', $postId, 'update' );
        $response = new Response();
        $response->blockFlash();

        $this->runKernel($request, $response);

    }

}