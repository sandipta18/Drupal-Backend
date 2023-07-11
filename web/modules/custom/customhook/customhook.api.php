<?php

/**
 * @file
 * Documentation for a custom hook.
 */

/**
 * Implements hook_incremented().
 *
 * Increments the view count for a node and displays a message if it's the first view.
 *
 * @param int $current_count
 * The current view count for the node.
 * @param \Drupal\node\NodeInterface $node
 * The node being viewed.
 *
 * @see hook_incremented()
 */
function customhook_incremented($current_count, \Drupal\node\NodeInterface $node) {
  if ($current_count == 1) {
    \Drupal::messenger()->addMessage('This is the first time you have viewed the node
 %title.', ['%title' => $node->label()]);
  }
}
