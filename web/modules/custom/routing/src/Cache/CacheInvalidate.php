<?php

namespace Drupal\routing\Cache;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\user\Entity\User;

class CacheInvalidate {
  /**
   * The cache tags invalidator service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * CustomCacheInvalidator constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *   The cache tags invalidator service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cacheTagsInvalidator)
  {
    $this->cacheTagsInvalidator = $cacheTagsInvalidator;
  }

  /**
   * Invalidates the cache associated with a user.
   *
   * @param \Drupal\user\Entity\UserInterface $user
   *   The user entity.
   */
  public function invalidateUserCache(User $user)
  {
    $cacheTags = $user->getCacheTags();
    $this->cacheTagsInvalidator->invalidateTags($cacheTags);
  }

}

