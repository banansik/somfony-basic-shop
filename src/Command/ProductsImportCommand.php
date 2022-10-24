<?php

namespace App\Command;

use App\Service\DataTransformer\DataTransformerInterface;
use App\Service\Product\ProductImporterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProductsImportCommand extends Command
{
    protected static $defaultName = 'app:ImportProducts';
    protected static $defaultDescription = 'Import products from CSV file';

    /**
     * @var ProductImporterInterface
     */
    private $productImporter;

    /**
     * @var DataTransformerInterface
     */
    private $dataTransformer;

    /**
     * ProductsImportCommand constructor.
     * @param ProductImporterInterface $productImporter
     * @param DataTransformerInterface $dataTransformer
     */
    public function __construct(ProductImporterInterface $productImporter, DataTransformerInterface $dataTransformer)
    {
        $this->productImporter = $productImporter;
        $this->dataTransformer = $dataTransformer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('filename', InputArgument::OPTIONAL, 'Filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('filename');

        $data = $this->dataTransformer->execute('csv', file_get_contents($fileName));

        $this->productImporter->execute($data);

        return Command::SUCCESS;
    }
}
