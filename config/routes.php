<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

$routes->connect('/transaction', ['controller' => 'Admin', 'action' => 'transaction']);

$routes->connect('/profile/', ['controller' => 'Admin', 'action' => 'profile']);
$routes->connect('/details/id', ['controller' => 'Admin', 'action' => 'details']);
$routes->connect('/edit/id', ['controller' => 'Admin', 'action' => 'editpost']);
$routes->connect('/viewpost', ['controller' => 'Admin', 'action' => 'viewpost']);
$routes->connect('/wallet/*', ['controller' => 'Admin', 'action' => 'wallet']);
$routes->connect('/post', ['controller' => 'Admin', 'action' => 'post']);
$routes->connect('/admin', ['controller' => 'Admin', 'action' => 'index']);
  $routes->connect('/', ['controller' => 'Main', 'action' => 'index']);

$routes->connect('/post/category', ['controller' => 'Post', 'action' => 'category']);
$routes->connect('/login', ['controller' => 'Main', 'action' => 'login']);


$routes->connect('/search/:param1', ['controller' => 'Appreciate', 'action' => 'search'],["pass"=>["param1"]]);
$routes->connect('/register', ['controller' => 'Main', 'action' => 'register']);

$routes->connect('/contact', ['controller' => 'Main', 'action' => 'contact']);

  $routes->connect('/post/:param1', ['controller' => 'Post', 'action' => 'post'],["pass"=>["param1"]]);

$routes->connect('/giveappri', ['controller' => 'Post', 'action' => 'giveappri']);

$routes->connect('/forgot', ['controller' => 'Main', 'action' => 'forgot']);

 //$routes->connect('/:param1/:param2', ['controller' => 'Test', 'action' => 'Index', 'index'],["pass"=>["param1","param2"]]);

Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true
    ]));

    /**
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered via `Application::routes()` with `registerMiddleware()`
     */
   // $routes->applyMiddleware('csrf');

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
   // $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
