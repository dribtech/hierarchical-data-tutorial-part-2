<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryCreateCommand
 * @package App\Command
 */
class AppCategoryCreateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:create';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategoryCreateCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Create test categories');
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

        // level 0: Home
        $home = new Category();
        $home->setName('Home');

        // level 1: Bikes, Components, Wheels & Tyres
        $bikes = new Category();
        $bikes->setName('Bikes');
        $bikes->setParent($home);

        $components = new Category();
        $components->setName('Components');
        $components->setParent($home);

        $wheelsAndTyres = new Category();
        $wheelsAndTyres->setName('Wheels & Tyres');
        $wheelsAndTyres->setParent($home);

        $this->entityManager->persist($home);
        $this->entityManager->persist($bikes);
        $this->entityManager->persist($components);
        $this->entityManager->persist($wheelsAndTyres);

        $this->entityManager->flush();

        // demonstrate using repository functions

        $mountain = new Category();
        $mountain->setName('Mountain');
        $repository->persistAsLastChildOf($mountain, $bikes);

        $roadAndTimeTrail = new Category();
        $roadAndTimeTrail->setName('Road & Time Trail');
        $repository->persistAsNextSiblingOf($roadAndTimeTrail, $mountain);

        // children of Wheels & Tyres

        $rims = new Category();
        $rims->setName('Rims');
        $repository->persistAsLastChildOf($rims, $wheelsAndTyres);

        $hubs = new Category();
        $hubs->setName('Hubs');
        $repository->persistAsNextSiblingOf($hubs, $rims);

        $tyres = new Category();
        $tyres->setName('Tyres');
        $repository->persistAsNextSiblingOf($tyres, $hubs);

        // children of Mountain
        $enduro = new Category();
        $enduro->setName('Enduro');
        $repository->persistAsLastChildOf($enduro, $mountain);

        $xc = new Category();
        $xc->setName('XC');
        $repository->persistAsLastChildOf($xc, $mountain);

        $fatBike = new Category();
        $fatBike->setName('Fat Bike');
        $repository->persistAsLastChildOf($fatBike, $mountain);

        $this->entityManager->flush();

        $io->success("Done");
    }
}
