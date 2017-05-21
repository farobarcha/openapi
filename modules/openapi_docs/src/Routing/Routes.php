<?php
namespace Drupal\openapi_docs\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines dynamic routes.
 *
 * @internal
 */
class Routes implements ContainerInjectionInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Routes constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler')
    );
  }

  /**
   * Provides dynamic routes.
   */
  public function routes() {
    $collection = new RouteCollection();
    $routes = [];
    if ($this->moduleHandler->moduleExists('rest')) {
      /** @var \Symfony\Component\Routing\Route[] $routes */

      $routes['openapi.swaggerUI.rest.non_entity'] = (new Route('/admin/config/services/openapi/swagger-ui/rest/non-entity'))
        ->setDefault(RouteObjectInterface::CONTROLLER_NAME, '\Drupal\openapi_docs\Controller\SwaggerUIRestController::nonEntityResources');
      $routes['openapi.swaggerUI.rest.bundle'] = (new Route('/admin/config/services/openapi/swagger-ui/rest/{entity_type}/{bundle_name}'))
        ->setDefault(RouteObjectInterface::CONTROLLER_NAME, '\Drupal\openapi_docs\Controller\SwaggerUIRestController::bundleResource');
      $routes['openapi.swaggerUI.rest.list'] = (new Route('/admin/config/services/openapi/swagger-ui/rest/list-resources'))
        ->setDefault(RouteObjectInterface::CONTROLLER_NAME, '\Drupal\openapi_docs\Controller\SwaggerUIRestController::listResources');

    }
    if ($this->moduleHandler->moduleExists('jsonapi')) {
      $routes['openapi.swaggerUI.jsonapi'] = (new Route('/admin/config/services/openapi/swagger-ui/jsonapi'))
        ->setDefault(RouteObjectInterface::CONTROLLER_NAME, '\Drupal\openapi_docs\Controller\SwaggerUIController::openApiResources');
    }
    if ($routes) {
      foreach ($routes as $route_name => $route) {
        $route->setMethods(['GET'])
          ->setRequirements([
            '_permission' => 'access openapi api docs',
          ]);
        $collection->add($route_name, $route);
      }
    }
    return $collection;
  }
}
