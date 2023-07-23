<?php

namespace Drupal\entity_movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Movie type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "movie_type",
 *   label = @Translation("Movie type"),
 *   label_collection = @Translation("Movie types"),
 *   label_singular = @Translation("movie type"),
 *   label_plural = @Translation("movies types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movies type",
 *     plural = "@count movies types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\entity_movie\Form\MovieTypeForm",
 *       "edit" = "Drupal\entity_movie\Form\MovieTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\entity_movie\MovieTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer movie types",
 *   bundle_of = "movie",
 *   config_prefix = "movie_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/movie_types/add",
 *     "edit-form" = "/admin/structure/movie_types/manage/{movie_type}",
 *     "delete-form" = "/admin/structure/movie_types/manage/{movie_type}/delete",
 *     "collection" = "/admin/structure/movie_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class MovieType extends ConfigEntityBundleBase {

  /**
   * The machine name of this movie type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the movie type.
   *
   * @var string
   */
  protected $label;

}
