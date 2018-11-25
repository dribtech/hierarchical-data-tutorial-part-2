<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryDeleteCommand
 * @package App\Command
 */
class AppCategoryDeleteCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:delete';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Delete test \"Mountain\" category');
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

        $mountain = $repository->findOneBy(['name' => "Mountain"]);

        if (null === $mountain) {
            $io->note("Category \"Mountain\" not found!");

            return 0;
        }

        $this->entityManager->remove($mountain);
        $this->entityManager->flush();

        $io->success("Done");

        return 0;
    }
}
