<?php

declare(strict_types=1);

namespace Drupal\console_demo\Command;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Service argument command.
 *
 * In-case we can't autowire the service, we can explicitly pass in the
 * services.yml file.
 */
#[AsCommand(name: 'console_demo:service_argument', description: 'An example command to show how to pass the services argument using services.yml.')]
final class ServiceArgumentExample extends Command {

  public const CACHE_ID = 'service_argument';

  /**
   * {@inheritdoc}
   */
  public function __construct(
    private readonly TimeInterface $dateTime,
    private readonly CacheBackendInterface $cacheData,
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $io = new SymfonyStyle($input, $output);

    if (!$this->cacheData->get(self::CACHE_ID)) {
      $io->note('The cache value is not set, setting up value:' . $this->cacheData->set(self::CACHE_ID, '1234'));
    }
    $now = new \DateTimeImmutable('@' . $this->dateTime->getRequestTime());
    $io->note('The current time is ' . $now->format('r'));

    $io->note('The cache value is:' . $this->cacheData->get(self::CACHE_ID)->data);
    return static::SUCCESS;
  }

}
