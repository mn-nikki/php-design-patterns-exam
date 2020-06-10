<?php declare(strict_types=1);


namespace App\Controller;

use App\Adapter\{CsvAdapter, JsonAdapter};
use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Container\Container;
use App\Entity\Car;

class CarController extends AbstractController
{
    /**
     * @var CsvAdapter|mixed
     */
    private CsvAdapter $csvAdapter;
    /**
     * @var JsonAdapter|mixed
     */
    private JsonAdapter $jsonAdapter;

    /**
     * CarController constructor.
     * @param Container $container
     * @throws \Exception
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->csvAdapter = $container->get(CsvAdapter::class);
        $this->jsonAdapter = $container->get(JsonAdapter::class);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function index() :Response
    {
        $csvData = (new CarRepository($this->csvAdapter, Car::class))->getSlice();
        $jsonData = (new CarRepository($this->jsonAdapter, Car::class))->getSlice();

        return $this->render('cars.html.twig', [
            'csv' => $csvData,
            'json' => $jsonData,
        ]);
    }
}