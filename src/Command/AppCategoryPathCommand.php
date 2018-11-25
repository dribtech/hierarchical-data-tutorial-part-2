<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryPathCommand
 * @package App\Command
 */
class AppCategoryPathCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:path';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Display path of given category.')
            ->addArgument("categoryName", InputArgument::REQUIRED, "Display path for this category")
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $categoryName = $input->getArgument("categoryName");

        $repository = $this->entityManager->getRepository(Category::class);

        $category = $repository->findOneBy(['name' => $categoryName]);

        /** @var ArrayCollection|Category[] $path */
        $path = $repository->getPath($category);

        dump($path);

        $io->success("Done");
    }
}
