<?php

namespace Drupal\entity_movie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\entity_movie\MovieConfigurationInterface;

/**
 * Defines the movie configuration entity type.
 *
 * @ConfigEntityType(
 *   id = "movie_configuration",
 *   label = @Translation("Movie Configuration"),
 *   label_collection = @Translation("Movie Configurations"),
 *   label_singular = @Translation("movie configuration"),
 *   label_plural = @Translation("movie configurations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie configuration",
 *     plural = "@count movie configurations",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\entity_movie\MovieConfigurationListBuilder",
 *     "form" = {
 *       "add" = "Drupal\entity_movie\Form\MovieConfigurationForm",
 *       "edit" = "Drupal\entity_movie\Form\MovieConfigurationForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie_configuration",
 *   admin_permission = "administer movie_configuration",
 *   links = {
 *     "collection" = "/admin/structure/movie-configuration",
 *     "add-form" = "/admin/structure/movie-configuration/add",
 *     "edit-form" = "/admin/structure/movie-configuration/{movie_configuration}",
 *     "delete-form" = "/admin/structure/movie-configuration/{movie_configuration}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *      "year" = "year",
 *      "movie" = "movie",
 *      "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *      "year",
 *      "movie",
 *   }
 * )
 */
class MovieConfiguration extends ConfigEntityBase implements MovieConfigurationInterface {

  /**
   * The movie configuration ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The movie configuration label.
   *
   * @var string
   */
  protected $label;

  /**
   * The movie configuration status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The movie_configuration description.
   *
   * @var string
   */
  protected $description;

  /**
   * The year on which movie was released
   *
   * @var mixed
   */
  protected $year;

}
