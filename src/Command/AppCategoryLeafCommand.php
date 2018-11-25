<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCategoryLeafCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:leafs';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategoryLeafCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Display all leafs.');
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

        $rootNode = $repository->findOneBy(['name' => 'Home']);

        if (null === $rootNode) {
            $io->note('Category "Home" is not found.');

            return 0;
        }

        /** @var ArrayCollection|Category[] $leafs */
        $leafs = $repository->getLeafs($rootNode);

        foreach ($leafs as $node) {
            $io->writeln($node->getName());
        }

        $io->success("Done");

        return 0;
    }
}
