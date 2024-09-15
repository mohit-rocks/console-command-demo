<?php

declare(strict_types=1);

namespace Drupal\console_demo\Command;

use Composer\Console\Input\InputArgument;
use Composer\Console\Input\InputOption;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Service argument command.
 */
#[AsCommand(
  name:'console_demo:service_argument',
  description: 'Demo for symfony console features.',
  aliases: ['service_argument']
)]
final class ServiceArgumentExample extends Command {

  public const CACHE_ID = 'service_argument';

  /**
   * {@inheritdoc}
   */
  public function __construct(
    private readonly TimeInterface $dateTime,
    #[Autowire(service: 'cache.data')]
    private readonly CacheBackendInterface $cacheData,
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function configure(): void {
    $this
      ->addArgument('argument', mode: InputArgument::OPTIONAL)
      ->addArgument('scenario', mode: InputArgument::OPTIONAL)
      ->addOption('option-test', mode: InputOption::VALUE_OPTIONAL);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
    $highlight = new OutputFormatterStyle('black', 'bright-cyan', ['bold', 'blink']);
    $tableHeader = new OutputFormatterStyle('bright-cyan');
    $io = new SymfonyStyle($input, $output);

    // Set the various styles that we can use for the output.
    $io->getFormatter()->setStyle('highlight', $highlight);
    $io->getFormatter()->setStyle('table-header', $tableHeader);

    // Add a helper question and ask for inputs.
    $io->section('<highlight>Asking a question!</highlight>❓');
    $helper = $this->getHelper('question');
    $question = new Question('Please enter name: ', 'John Doe!');
    $name = $helper->ask($input, $output, $question);

    $io->newLine();
    $io->section('<highlight>Asking for a confirmation!</highlight>🤔 ');
    // Confirmation step before the command run.
    $confirm = new ConfirmationQuestion('Continue with this action? ', false);
    if (!$helper->ask($input, $output, $confirm)) {
      $io->error('Command aborted.');
      return Command::FAILURE;
    }

    // Simple note message on terminal.
    if (!$this->cacheData->get(self::CACHE_ID)) {
      $io->note('The cache value is not set, setting up value:' . $this->cacheData->set(self::CACHE_ID, '1234'));
    }
    $now = new \DateTimeImmutable('@' . $this->dateTime->getRequestTime());

    $io->newLine();
    $io->choice('<highlight>Select the option</highlight>📋 ', ['hello', 'world', 'pick'], 'hello');

    $io->newLine();
    $io->section('<highlight>Starting progress..</highlight>⏳ ');

    // Set the fake progressbar for demo.
    $io->progressStart(100);
    sleep(2);
    $io->progressFinish();

    // Table output on the terminal.
    $io->newLine();
    $io->section('<highlight>Table output</highlight>');
    $table = new Table($output);

    $table
      ->setHeaders(['<table-header>Title</table-header>', '<table-header>Value</table-header>'])
      ->setRows([
        ['The current time is', $now->format('r')],
        ['The cache value is', $this->cacheData->get(self::CACHE_ID)->data],
        ['option-test', ($input->getOption('option-test') ?? 'No value set.')],
        ['Argument 1 (argument)', ($input->getArgument('argument'))],
        ['Argument 2 (scenario)', ($input->getArgument('scenario'))],
        ['Question response (User name)', $name],
      ]);
    $table->render();
    $io->note('Command successful. 💧');
    return static::SUCCESS;
  }

}
