<?php

/**
 * @file
 * Documentation for a custom hook.
 */

use Drupal\node\NodeInterface;

/**
 * @addtogroup hooks
 * @{
 */

/**
 * This hook facilitates displaying a message upon viewing a node for the first
 * time, Further it can be also utilised to display the number of times a node
 * is being viewed over a particular session .
 *
 * @param int $current_count
 *   The current view count for the node.
 * @param \Drupal\node\NodeInterface $node
 *   The node being viewed.
 *
 * @see hook_incremented()
 */
function customhook_incremented($current_count, NodeInterface $node) {
  if ($current_count == 1) {
    \Drupal::messenger()->addMessage('This is the first time you have viewed the node
 %title.', ['%title' => $node->label()]);
  }
}

/**
 * @} End of "addtogroup hooks".
 */
