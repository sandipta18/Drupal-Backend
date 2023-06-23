<?php

// This is a documentation of the customhook created for ticket FT2023-340
// The purpose of this hook is to display the number of times a node is viewed
// over a session

/**
 * @param int $current_count
 * It defines the number of times user is viewing a node
 * @param \Drupal\node\NodeInterface $node
 * The node being viewed
 */
function customhook_incremented($current_count, \Drupal\node\NodeInterface $node) {
  if($current_count == 1) {
    \Drupal::messenger()->addMessage('This is the first time you have viewed the node %title. ',['%title' => $node->label()]);
  }
}

