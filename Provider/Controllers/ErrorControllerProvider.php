<?php

namespace EXS\ErrorProvider\Controllers;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use EXS\ErrorProvider\Controllers\ErrorController;

/**
 * Description of ErrorControllerProvider
 *
 * Defines and provides services to the controllers
 *
 * Created      04/09/2015
 * @author      Charles Weiss
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ErrorControllerProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    public function register(Container $app)
    {
        // Define controller services
        $app['exs.controller.error'] = (function ($app) {
            return new ErrorController($app['twig']);
        });
    }

    public function connect(Application $app)
    {
        // Get the controllers
        $controllers = $app['controllers_factory'];

        $controllers->get('/error/{slug}', 'exs.controller.error:responseAction')
            ->method('GET')
            ->bind('exs.route.error');

        return $controllers;
    }
}
