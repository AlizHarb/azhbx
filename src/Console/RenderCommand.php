<?php

namespace AlizHarb\AzHbx\Console;

use AlizHarb\AzHbx\Engine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * RenderCommand class.
 *
 * @package AlizHarb\AzHbx
 */
class RenderCommand extends Command
{
    protected static $defaultName = 'render';

    protected function configure(): void
    {
        $this
            ->setName('render')
            ->setDescription('Render a template')
            ->addArgument('template', InputArgument::REQUIRED, 'The template to render')
            ->addOption('data', 'd', InputOption::VALUE_OPTIONAL, 'JSON data to pass to the template', '{}')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Path to views directory', getcwd() . '/views');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = $input->getArgument('template');
        $jsonData = $input->getOption('data');
        $viewsPath = $input->getOption('path');

        $data = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $output->writeln('<error>Invalid JSON data provided.</error>');

            return Command::FAILURE;
        }

        $engine = new Engine([
            'views_path' => $viewsPath,
            'cache_path' => getcwd() . '/cache',
        ]);

        try {
            $result = $engine->render($template, $data);
            $output->write($result);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return Command::FAILURE;
        }
    }
}
