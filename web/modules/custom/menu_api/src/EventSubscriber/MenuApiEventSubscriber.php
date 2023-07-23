<?php

namespace Drupal\menu_api\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event subscriber to add budget value to "movie" content type.
 */
class MenuApiEventSubscriber implements EventSubscriberInterface {

  // Importing the StringTranslationTrait.
  use StringTranslationTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * MenuApiEventSubscriber constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    RouteMatchInterface $route_match,
    MessengerInterface $messenger,
    ConfigFactoryInterface $config_factory
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
    $this->messenger = $messenger;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() : array {
    $events[KernelEvents::VIEW][] = ['onNodeView', 10];
    return $events;
  }

  /**
   * Event callback to add the budget value to the "movie_info" content type.
   *
   * @param \Drupal\node\NodeViewEvent $event
   *   The node view event.
   */
  public function onNodeView(ViewEvent $event) {
    $request = $event->getRequest();
    // Check if the current route is for viewing a node entity.
    if ($this->routeMatch->getRouteName() === 'entity.node.canonical' &&
    $this->routeMatch->getParameter('node')->getType() === 'movie_info') {
      // Get the budget value from the configuration settings.
      $config = $this->configFactory->get('menu_api.settings');
      $budget = $config->get('budget');
      $node = $this->routeMatch->getParameter('node');
      $price = $node->get('movie_price')->getValue()[0]['value'];
      $message = '';

      if ($budget > $price) {
        $message = $this->t('The movie is under budget');
      }
      elseif ($budget < $price) {
        $message = $this->t('The movie is over budget');
      }
      else {
        $message = $this->t('The movie is within budget');
      }
      $this->messenger->addMessage($message);

    }
  }

}
