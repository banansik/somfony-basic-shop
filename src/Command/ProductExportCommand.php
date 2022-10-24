<?php

namespace App\Command;

use App\Repository\Product\Mysql\ProductRepository;
use App\Repository\Product\ProductRepositoryInterface;
use App\Service\Product\ProductExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

class ProductExportCommand extends Command
{
    protected static $defaultName = 'app:exportProducts';
    protected static $defaultDescription = 'Export products records to chosen format';

    /**
     * @var ProductExporter
     */
    private $exporter;

    /**
     * ExportProductsCommand constructor.
     * @param ProductExporter $exporter
     * @param ProductRepositoryInterface $productRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(ProductExporter $exporter)
    {
        $this->exporter = $exporter;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('fileName', InputArgument::REQUIRED, 'name og the output file')
            ->addArgument('ids', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'products ids to export.')
            ->addOption('format', 'f',InputOption::VALUE_OPTIONAL, 'file format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $fileName = $input->getArgument('fileName');
            $ids = $input->getArgument('ids');
            $format = $input->getOption('format');

            $this->exporter->execute($fileName, $ids, $format);

            $io->success('Products exported');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Product export failed');

            return Command::FAILURE;
        }
    }
}