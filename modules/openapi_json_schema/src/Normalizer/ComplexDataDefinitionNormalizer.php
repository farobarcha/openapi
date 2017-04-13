<?php

namespace Drupal\openapi_json_schema\Normalizer;

use Drupal\Core\TypedData\ComplexDataDefinitionInterface;
use Drupal\Core\TypedData\DataReferenceTargetDefinition;
use Drupal\Component\Utility\NestedArray;

/**
 * Normalizer for ComplexDataDefinitionInterface.
 *
 * ComplexDataDefinitions represent objects - compound values whose objects
 * have string keys. Almost all fields are complex in this way, with their key
 * data stored in an object property of "value". In turn, these objects are
 * wrapped in an array which is normalized by ListDataDefinitionNormalizer.
 */
class ComplexDataDefinitionNormalizer extends DataDefinitionNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = '\Drupal\Core\TypedData\ComplexDataDefinitionInterface';

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = array()) {
    /* @var $entity \Drupal\Core\TypedData\ComplexDataDefinitionInterface */
    $context['parent'] = $entity;
    $normalized = $this->extractPropertyData($entity);
    $normalized['type'] = 'object';

    // Retrieve 'properties' and possibly 'required' nested arrays.
    $properties = $this->normalizeProperties(
      $entity->getPropertyDefinitions(),
      $format,
      $context
    );

    $normalized = NestedArray::mergeDeep($normalized, $properties);
    return $normalized;
  }

  /**
   * Determine if the current field is a reference field.
   *
   * @param \Drupal\Core\TypedData\ComplexDataDefinitionInterface $entity
   *   The complex data definition to be checked.
   *
   * @return bool
   *   TRUE if it is a reference, FALSE otherwise.
   */
  protected function isReferenceField(ComplexDataDefinitionInterface $entity, $context = NULL) {
    $main = $entity->getPropertyDefinition($entity->getMainPropertyName());
    // @todo use an interface or API call instead of an object check.
    return ($main instanceof DataReferenceTargetDefinition);
  }

}
