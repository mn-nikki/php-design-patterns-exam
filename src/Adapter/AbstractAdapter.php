<?php declare(strict_types=1);


namespace App\Adapter;


abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    protected static array $dateField = ['date', 'birthday', 'issueDate'];
    /**
     * @var string
     */
    protected string $path;

    /**
     * AbstractAdapter constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        return $this->path = $path;
    }

    /**
     * @return \SplFileObject
     * @throws \Exception
     */
    public function connect() : \SplFileObject
    {
        if(!\file_exists($this->path))
        {
            throw new \Exception(sprintf('This file not found "%s"', $this->path));
        }

        return new \SplFileObject($this->path, 'rb');
    }

    /**
     * Open file and get data from it
     * @return iterable
     * @throws \Exception
     */
    public function getData(): iterable
    {
        $file = $this->connect();

        return $this->processData($file);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function dateFieldConvert(array $data) : array
    {
        foreach ($data as $name => $value)
        {
            if(!empty($value))
            {
                if(\in_array($name, static::$dateField))
                {
                    $value = \date_create_from_format('d.m.Y', $value);
                }
            }
            else {
                $value = null;
            }

            $data[$name] = $value;
        }

        return $data;
    }
}