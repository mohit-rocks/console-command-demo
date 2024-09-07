<?php

declare(strict_types=1);

namespace Drupal\console_demo\Command;

use Drupal\Component\Datetime\TimeInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Example module command.
 */
#[AsCommand(name: 'console_demo:example', description: 'An example command.')]
final class DateExample extends Command {

  /**
   * {@inheritdoc}
   */
  public function __construct(
    private readonly TimeInterface $dateTime,
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $now = new \DateTimeImmutable('@' . $this->dateTime->getRequestTime());
    $output->note('The current time is ' . $now->format('r'));
    return static::SUCCESS;
  }

}
