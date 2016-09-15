<?php

/*
 * This file is part of the Cilex framework.
 *
 * (c) Mike van Riel <mike.vanriel@naenius.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cilex\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;

/**
 * Example command for testing purposes.
 */
class ConvertCsvCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('csv:convert')
            ->addArgument('input_file', InputArgument::REQUIRED)
            ->addOption('columns',null, InputOption::VALUE_REQUIRED,'',19)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputF = $input->getArgument('input_file');

        if (!file_exists($inputF)) {
            $errOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;

            return $errOutput->writeln("<error>Input doesn't exist</error>");
        }

        $data = [];
        $y = [];
        foreach(file($inputF) as $k => $row) {
            $data[$k] = explode(',', $row);
            $y[$k] = array_shift($data[$k]);
        }

        array_shift($y);
        $x = array_shift($data);

        $max = $input->getOption("columns");
        while (count($x) > $max) {
            array_pop($x);
        }

        $output->writeln("x,y,value");
        foreach ($x as $kX => $valX) {
            foreach ($y as $kY => $valY) {
                $output->writeln(sprintf("%s,%s,%s",trim($valX),trim($valY),trim($data[$kY][$kX])));
            }
        }

    }
}

