<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryTreeCommand
 * @package App\Command
 */
class AppCategoryTreeCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:tree';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategoryTreeCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Display tree.');
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

        $bikesNode = $repository->findOneBy(['name' => 'Bikes']);

        if (null === $bikesNode) {
            $io->note('Category "Bikes" not found.');

            return 1;
        }

        $io->title('Children array tree:');
        $arrayTree = $repository->childrenHierarchy($bikesNode, false, [], true);
        $io->writeln(print_r($arrayTree, true));

        $io->title('Children html tree:');
        $htmlTree = $repository->childrenHierarchy(
            $bikesNode,
            false,
            [
                'decorate' => true,
            ],
            true
        );

        $io->writeln($htmlTree);

        $io->title('Children html (customized) tree:');
        $htmlTree = $repository->childrenHierarchy(
            $bikesNode,
            false,
            [
                'decorate' => true,
                'nodeDecorator' => function ($node)
                {
                    return "<a href=\"https://www.google.com/search?q=$node[name]\">$node[name]</a>";
                },
                //'rootOpen' => '<ul>', leave it as is
                //'rootClose' => '</ul>',
                'childOpen' => function ($node)
                {
                    return "<li data-node-id=\"$node[id]\">";
                },
                'childClose' => '</li>',
            ],
            true
        );

        $io->writeln($htmlTree);


        $io->title('Array of objects (from the root node):');

        $this->entityManager->getConfiguration()->addCustomHydrationMode(
            'tree',
            'Gedmo\Tree\Hydrator\ORM\TreeObjectHydrator'
        );

        $tree = $repository->createQueryBuilder('node')->getQuery()
            ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getResult('tree')
        ;

        dump($tree);

        $io->title('Array of objects (from the Bikes node):');

        $tree = $this->entityManager->createQueryBuilder()
            ->select('node')
            ->from(Category::class, 'node')
            ->orderBy('node.root, node.lft', 'ASC')
            ->where('node.name = \'Bikes\'')
            ->getQuery()
            ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getResult('tree')
        ;

        dump($tree);

        $io->success("Done");

        return 0;
    }
}
