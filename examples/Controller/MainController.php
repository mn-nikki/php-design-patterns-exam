<?php declare(strict_types=1);

namespace Example\Controller;

use Example\Adapter\{CsvAdapter, JsonAdapter};
use Example\Http\{Request, Response};
use Example\Repository\VehicleRepository;
use Psr\Container\ContainerInterface;

/**
 * Main Controller.
 */
class MainController extends AbstractController
{
    private CsvAdapter $csvAdapter;
    private JsonAdapter $jsonAdapter;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->csvAdapter = $this->container->get(CsvAdapter::class);
        $this->jsonAdapter = $this->container->get(JsonAdapter::class);
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $csvData = (new VehicleRepository($this->csvAdapter))->get();
        $jsonData = (new VehicleRepository($this->jsonAdapter))->get();

        return $this->render('vehicles.html.twig', [
            'csv' => $csvData,
            'json' => $jsonData,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $query = $request->getQuery();
        if (($id = $query->get('id')) === null || ($type = $query->get('type')) === null) {
            return new Response(\sprintf('\'%s\' and \'%s\' must be defined', 'id', 'type'), Response::HTTP_BAD_REQUEST);
        }

        if ($type === 'csv') {
            $adapter = $this->csvAdapter;
        } elseif ($type === 'json') {
            $adapter = $this->jsonAdapter;
        } else {
            return new Response(\sprintf('\'%s\' adapter is not defined', $type));
        }

        $entity = (new VehicleRepository($adapter))->find((int) $id);
        if ($entity === null) {
            return new Response(\sprintf('Entity of type \'%s\' with ID \'%d\' not found', $type, $id));
        }

        return $this->render('vehicle.html.twig', ['item' => $entity]);
    }
}
