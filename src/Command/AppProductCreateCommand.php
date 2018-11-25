<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppProductCreateCommand
 * @package App\Command
 */
class AppProductCreateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:product:create';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppProductCreateCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Create test products.');
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

        $category = $repository->findOneBy(['name' => 'Enduro']);

        $product = new Product();
        $product->setName('Enduro Comp 29/6 Fattie Enduro Mountain Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Kona Process 167 Mountain Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);


        $category = $repository->findOneBy(['name' => 'XC']);

        $product = new Product();
        $product->setName('Lapierre XR 729 Suspension Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Kona Hei Hei Race Mountain Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);


        $category = $repository->findOneBy(['name' => 'Fat bike']);

        $product = new Product();
        $product->setName('Kona Wo Fat Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Charge Cooker Midi 5 Mountain Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);


        $category = $repository->findOneBy(['name' => 'Road & Time Trail']);

        $product = new Product();
        $product->setName('Eddy Merckx Milano 72 Womens Road Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('GT Grade Carbon Adventure Road Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Lapierre Aircode SL 500 MC Road Bike');
        $product->setCategory($category);
        $this->entityManager->persist($product);


        $category = $repository->findOneBy(['name' => 'Components']);

        $product = new Product();
        $product->setName('e.thirteen Cassette Range Expander Cog');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Shimano XTR Trail M980 10 Speed Double Chainset');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('SRAM PowerLink PowerLock Chain Connector');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Easton EC90 SLX3 Pro Ergo Road Bar');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $category = $repository->findOneBy(['name' => 'Rims']);

        $product = new Product();
        $product->setName('WTB ST i19 MTB Rim');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('DT Swiss RR 521 Disc Brake 20mm Road Rim');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Hope Tech XC MTB Rim');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $category = $repository->findOneBy(['name' => 'Hubs']);

        $product = new Product();
        $product->setName('Hope RS4 Centre Lock Rear Hub');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Shimano Saint Hub Rear M820 Black 32H - 135 - 10mm');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $category = $repository->findOneBy(['name' => 'Tyres']);

        $product = new Product();
        $product->setName('Schwalbe Racing Ralph Performance CX Tyre');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('Maxxis Minion DHR II Folding MTB Tyre');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $product = new Product();
        $product->setName('WTB Breakout 27.5\" x 2.5 TCS Tough High Grip Tyre');
        $product->setCategory($category);
        $this->entityManager->persist($product);

        $this->entityManager->flush();

        $io->success("Done");
    }
}
