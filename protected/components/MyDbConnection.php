<?php


/**
 * Description of MyDbConnection
 *
 */
class MyDbConnection extends CDbConnection {
  protected function initConnection($pdo)
  {
    parent::initConnection($pdo);
    $stmt=$pdo->prepare("set search_path to itikka, public");
    $stmt->execute();
  }
}


?>
