<?php

namespace Drupal\database_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\MessengerInterface;
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
   * The Messenger Service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Initializes the database connection object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   Database connection handler.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The Messenger Service.
   */
  public function __construct(Connection $connection,
  MessengerInterface $messenger) {
    $this->connection = $connection;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('messenger'),
    );
  }

  /**
   * This function retrieves the events taking place yearly.
   *
   * @return array
   *   Returns result in an associative array format.
   */
  public function getEventsYearly() {
    try {
      $query = $this->connection->select('node__event_date', 'Year')
        ->fields('Year', ['event_date_value']);
      $results = $query->execute()->fetchAll();
      // Group events by the year part of 'event_date_value'.
      $groupedResults = [];
      foreach ($results as $result) {
        $year = date('Y', strtotime($result->event_date_value));
        $groupedResults[$year][] = $result;
      }
      // Calculate the event count for each year.
      $output = [];
      foreach ($groupedResults as $year => $events) {
        $eventCount = count($events);
        $output[] = [
          'year' => $year,
          'event_count' => $eventCount,
        ];
      }
      return $output;
    }
    catch (\Exception $e) {
      $this->messenger->addError($this->t('Error loading'));
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
        ->fields('Year', ['event_date_value']);
      $results = $query->execute()->fetchAll();
      // Group events by the quarter of the year.
      $groupedResults = [];
      foreach ($results as $result) {
        $quarter = ceil(date('n', strtotime($result->event_date_value)) / 3);
        $groupedResults[$quarter][] = $result;
      }
      // Calculate the event count for each quarter.
      $output = [];
      foreach ($groupedResults as $quarter => $events) {
        $eventCount = count($events);
        $output[] = [
          'quarter' => $quarter,
          'event_count' => $eventCount,
        ];
      }
      return $output;
    }
    catch (\Exception $e) {
      $this->messenger->addError($this->t('Error loading'));
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
      $this->messenger->addError($this->t('Error loading'));
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
    $cache_tag = ['node_list:event'];
    return [
      '#theme' => 'custom-theme',
      '#events_per_year' => $events_per_year,
      '#events_per_quarter' => $events_per_quarter,
      '#events_type' => $events_type,
      '#cache' => [
        'tags' => $cache_tag,
      ],
      '#attached' => [
        'library' => [
          'database_api/custom_theme',
        ],
      ],
    ];
  }

}
