<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryAddCommand
 * @package App\Command
 */
class AppCategoryAddCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:add';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategoryAddCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Add test "Clothing & Footwear" category as next sibling of "Components"');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->entityManager->getRepository(Category::class);

        $components = $repository->findOneBy(['name' => 'Components']);

        if(null === $components) {
            $io->note('Category "Components" not found!');
            return 1;
        }

        $clothingAndFootwear = new Category();
        $clothingAndFootwear->setName("Clothing & Footwear");
        $repository->persistAsNextSiblingOf($clothingAndFootwear, $components);

        $this->entityManager->flush();

        $io->success("Done");

        return 0;
    }
}
