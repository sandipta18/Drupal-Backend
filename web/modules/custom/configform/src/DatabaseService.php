<?php

namespace Drupal\configform;

use Drupal\Core\Database\Connection;

class DatabaseService {

  /**
   * Database Connection Handler
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new Database Connection Instance
   *
   * @param Connection $database
   *   Database Connection Handker
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * @param mixed $tableName
   *   Stores the name of table where data will be inserted
   * @param array $data
   *   Stores the data that is to be inserted
   *
   * @return void
   */
  public function insertData($tableName,array $data) {
    $this->database->insert($tableName)
    ->fields($data)->execute();
  }


}
