<?php
namespace Sas\Movies\Console\Command;

use Magento\Framework\Console\Cli;
use Sas\Movies\Model\Api\MovieRepository;
use Sas\Movies\Model\Movie\Import;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Add extends Command
{
    /**
     * @var MovieRepository
     */
    private $movieRepository;
    /**
     * @var Import
     */
    private $import;
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    public function __construct(
        MovieRepository $movieRepository,
        Import $import,
        \Magento\Framework\App\State $state,
        string $name = null
    ) {
        parent::__construct($name);
        $this->movieRepository = $movieRepository;
        $this->import = $import;
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sas:movies:add')
            ->setDescription('Add movie to catalog');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (\Exception $exception) {
            $this->state->setAreaCode('adminhtml');
        }

        try {
            $movie = $this->movieRepository->get(550);
            $this->import->execute($movie);
            $output->writeln('<info>Movie was added successfully</info>');

            return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>There was an error during the import: {$e->getMessage()}</error>");
            $output->writeln("<error>{$e->getTraceAsString()}</error>");
        }

        return Cli::RETURN_FAILURE;
    }
}
