<?php

namespace MediaMonks\RestApi\EventSubscriber;

use MediaMonks\RestApi\Model\ResponseModel;
use MediaMonks\RestApi\Model\ResponseModelFactory;
use MediaMonks\RestApi\Request\PathRequestMatcher;
use MediaMonks\RestApi\Request\RequestTransformer;
use MediaMonks\RestApi\Response\ResponseTransformer;
use MediaMonks\RestApi\Serializer\JsonSerializer;

class RestApiEventSubscriberFactory
{
    /**
     * @param string $path
     * @param array $options
     * @return RestApiEventSubscriber
     */
    public static function create($path = '/api', array $options = [])
    {
        if (empty($options['serializer'])) {
            $options['serializer'] = new JsonSerializer();
        }
        if (empty($options['response_model'])) {
            $options['responseModel'] = new ResponseModel();
        }
        $responseTransformerOptions = [];
        if (isset($options['debug'])) {
            $responseTransformerOptions['debug'] = $options['debug'];
        }
        if (isset($options['post_message_origin'])) {
            $responseTransformerOptions['post_message_origin'] = $options['post_message_origin'];
        }

        $requestMatcher = new PathRequestMatcher($path);
        $requestTransformer = new RequestTransformer($options['serializer']);
        $responseModelFactory = new ResponseModelFactory($options['responseModel']);
        $responseTransformer = new ResponseTransformer(
            $options['serializer'],
            $responseModelFactory,
            $responseTransformerOptions
        );

        return new RestApiEventSubscriber($requestMatcher, $requestTransformer, $responseTransformer);
    }
}
