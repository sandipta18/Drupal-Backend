<?php

namespace Drupal\database_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for database_api routes.
 */
class DatabaseApiController extends ControllerBase {
  /**
   * Database Connection Handler.
   *
   * @var \Druapl\Core\Database\Connection
   */
  protected $connection;

  /**
   * Initializes the database connection object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   Database connection handler.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
    );
  }

  /**
   * This function retrieves the events taking place yearly.
   *
   * @return array
   *   Returns result in an associative array format.
   */
  public function getEventsYearly() {
    $data = [];
    try {
      $query = $this->connection->select('node__event_date', 'Year')
        ->fields('Year', ['event_date_value'])
        ->groupBy('(event_date_value)')
        ->orderBy('(event_date_value)');
      $query->addExpression('YEAR(event_date_value)', 'year');
      $query->addExpression('COUNT(*)', 'event_count');
      $results = $query->execute()->fetchAll();
      return $results;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to save data'));
    }
  }

  /**
   * This function retrieves the events taking place quarterly.
   *
   * @return array
   *   Returns result in an associative array format.
   */
  public function getEventsQuarterly() {
    try {
      $query = $this->connection->select('node__event_date', 'Year')
        ->fields('Year', ['event_date_value'])
        ->groupBy('(event_date_value)')
        ->orderBy('(event_date_value)');
      $query->addExpression('QUARTER(event_date_value)', 'quarter');
      $query->addExpression('COUNT(*)', 'event_count');
      $results = $query->execute()->fetchAll();
      return $results;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to save data'));
    }
  }

  /**
   * This function retrieves the events distinguishing by its type.
   *
   * @return array
   *   Returns results in an associative array format.
   */
  public function getEventsType() {
    try {
      $query = $this->connection->select('node__field_event_type', 'type')
        ->fields('type', ['field_event_type_value'])
        ->groupBy('field_event_type_value')
        ->orderBy('field_event_type_value');
      $query->addExpression('COUNT(*)', 'count');
      $results = $query->execute()->fetchAll();
      return $results;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to save data'));
    }
  }

  /**
   * Facilitates displaying data.
   *
   * @return array
   *   Returns the data to be displayed in twig file.
   */
  public function build() {
    $events_per_year = $this->getEventsYearly();
    $events_per_quarter = $this->getEventsQuarterly();
    $events_type = $this->getEventsType();
    // dd($events_per_year);
    return [
      '#theme' => 'custom-theme',
      '#events_per_year' => $events_per_year,
      '#events_per_quarter' => $events_per_quarter,
      '#events_type' => $events_type,
      '#attached' => [
        'library' => [
          'database_api/custom_theme',
        ],
      ],
    ];
  }

}
