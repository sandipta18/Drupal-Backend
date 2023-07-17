<?php

namespace Drupal\configform;

use Drupal\Core\Database\Connection;

/**
 * Instantaniates database connection and inserts data.
 */
class DatabaseService {

  /**
   * Database Connection Handler.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new Database Connection Instance.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Database Connection Handler.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * This function facilitates insertion of data.
   *
   * @param mixed $tableName
   *   Stores the name of table where data will be inserted.
   * @param array $data
   *   Stores the data that is to be inserted.
   *
   * @return void
   */
  public function insertData($tableName, array $data) {
    $this->database->insert($tableName)
      ->fields($data)->execute();
  }

}
