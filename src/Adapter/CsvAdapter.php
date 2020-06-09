<?php


namespace App\Adapter;


class CsvAdapter extends AbstractAdapter
{
    /**
     * Processing a csv file, getting field names and matching them with values as an object
     * @param \SplFileObject $file
     * @return array
     * @throws \Exception
     */
    protected function processData(\SplFileObject $file)
    {
        $file->setFlags(\SplFileObject::READ_CSV);
        $nameFields = $file->current();

        if(!\is_array($nameFields) || $nameFields[0] === null) {
            throw new \Exception("The first line in csv file should contain field names");
        }

        $data = [];

        foreach ($file as $key => $line)
        {
            if($key === 0) continue;

            if(\count($nameFields) === \count($line))
            {
                $tmpArr = array_combine(array_values($nameFields), array_values($line));
                $data[] = (object) $this->dateFieldConvert($tmpArr);
            }
        }

        return $data;
    }
}