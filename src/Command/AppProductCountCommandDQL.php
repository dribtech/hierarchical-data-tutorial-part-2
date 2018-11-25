<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppProductCountCommandDQL
 * @package App\Command
 */
class AppProductCountCommandDQL extends ContainerAwareCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:product:dql-count';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppProductCountCommandDQL constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Count products in categories using DQL. Parents have total count of its children.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->entityManager->getRepository(Category::class);

        $dql = "
                SELECT 
                    parent.id, 
                    parent.name, 
                    parent.lft, 
                    parent.rgt,
                    parent.lvl, 
                    count(item.id) as nm
                FROM
                    App:Category node
                    JOIN App:Category parent WITH (parent.lft <= node.lft AND parent.rgt >= node.lft)
                    LEFT JOIN App:Product item WITH (node = item.category)
                GROUP BY
                    parent.id
                ORDER BY
                    parent.lft 
            ";

        $query = $this->entityManager->createQuery($dql);

        $rows = $query->getArrayResult();

        $tree = $repository->buildTree(
            $rows,
            [
                'decorate' => true,
                'nodeDecorator' => function ($node)
                {
                    return "$node[name] ($node[nm])";
                },
            ]
        );

        $io->writeln($tree);

        $io->success("Done");
    }
}
