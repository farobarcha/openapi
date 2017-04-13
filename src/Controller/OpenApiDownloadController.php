<?php

namespace Drupal\openapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class OpenApiDownloadController extends ControllerBase {

  /**
   * List all REST Doc pages.
   */
  public function downloadsList() {
    $return['direct_download'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->t('Open API files') . '</h2>' .
        // @todo Which page should the docs link to?
        '<p>' . $this->t('The following links provide the REST API resources documented in <a href=":open_api_spec">Open API(fka Swagger)</a> format.', [':open_api_spec' => 'https://github.com/OAI/OpenAPI-Specification/tree/OpenAPI.next']) . ' ' .
        $this->t('This JSON file can be used in tools such as the <a href=":swagger_editor">Swagger Editor</a> to provide a more detailed version of the API documentation.', [':swagger_editor' => 'http://editor.swagger.io/#/']) . '</p>',
    ];
    $open_api_links['entities'] = [
      'url' => Url::fromRoute('openapi.entities', [], ['query' => ['_format' => 'json']]),
      'title' => $this->t('Open API: Entities'),
    ];
    $open_api_links['other'] = [
      'url' => Url::fromRoute('openapi.non_entities', [], ['query' => ['_format' => 'json']]),
      'title' => $this->t('Open API: Other resources'),
    ];
    $return['direct_download']['links'] = [
      '#theme' => 'links',
      '#links' => $open_api_links,
    ];

    return $return;
  }
}
