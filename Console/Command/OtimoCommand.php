<?php
namespace Juniorfreitas\Movie\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OtimoCommand extends Command{

    protected function configure()
    {
        $this->setName("jrfreitas:otimo")
            ->setDescription("Executa alguns comando uteis para layout, isto funciona apenas como alias para os comandos");

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        passthru("clear");
        $output->writeln("<info> EXECUTAR ROTINA OTIMIZADA </info> \n");

        $output->writeln("<info> REMOVE STATIC FILES</info>  ");
        passthru(" rm -rf var/cache/* var/generation var/page_cache var/tmp/* var/view_preprocessed/* var/generation var/di pub/static/frontend/* pub/static/adminhtml/*");

        $output->writeln("<info> REMOVE CACHE CLEAN  bin/magento cache:clean</info>  ");
        passthru("bin/magento cache:clean");

        $output->writeln("<info>REMOVE CACHE FLUSH bin/magento cache:flush </info>");
        passthru("bin/magento cache:flush");

        $output->writeln("<info>COMPILE  bin/magento setup:di:compile</info>");
        passthru("bin/magento setup:di:compile");

        $output->writeln("<info>UPGRADE bin/magento setup:upgrade</info>");
        passthru("bin/magento setup:upgrade");

        $output->writeln("<info> CONTENT DEPLOY bin/magento setup:static-content:deploy -f</info>");
        passthru("bin/magento setup:static-content:deploy  -f");

    }

}
