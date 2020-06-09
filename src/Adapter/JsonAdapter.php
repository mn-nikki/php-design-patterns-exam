<?php


namespace App\Adapter;


class JsonAdapter extends AbstractAdapter
{
    /**
     * Process the json file and get its values as an object
     * @param \SplFileObject $file
     * @return iterable
     * @throws \Exception
     */
    protected function processData(\SplFileObject $file) : iterable
    {
        $json = $file->fread($file->getSize());
        $data = \json_decode($json, true, 8);

        if(!\is_iterable($data)) {
            throw new \Exception("Data must be in format json");
        }

        foreach ($data as $key => $item) {
            $data[$key] = (object) $this->dateFieldConvert($item);
        }

        return $data;
    }
}