<?php

declare(strict_types=1);

namespace Drupal\Tests\console_demo\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests the console commands.
 */
class ConsoleCommandTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'autowire_test',
    'console_demo',
  ];

  /**
   * Integration test for a command.
   *
   * Tests command is registered to the container, and has expected input/output
   * from options, arguments, autowiring, and return code.
   *
   * @covers \Drupal\console_demo\Command\ServiceArgumentExample
   */
  public function testConsoleCommand(): void {
    /** @var \Drupal\console_demo\Command\ServiceArgumentExample $command */
    $command = \Drupal::service('console.command.console_demo.service_argument');

    // Pass two question helper instances for the command questions.
    $helperSet = new HelperSet([new QuestionHelper(), new QuestionHelper()]);
    $command->setHelperSet($helperSet);

    $tester = new CommandTester($command);

    $tester->setInputs(['Mohit', 'yes']);
    $code = $tester->execute(['argument' => 'foo', '--option-test' => TRUE]);
    $this->assertStringContainsString('The current time is', $tester->getDisplay());
    $this->assertStringContainsString('The cache value is', $tester->getDisplay());
    $this->assertStringContainsString('1234', $tester->getDisplay());

    $this->assertEquals(Command::SUCCESS, $code);
  }

}
