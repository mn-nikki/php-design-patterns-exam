<?php declare(strict_types=1);


namespace App\Entity;


class Car
{
    /**
     * @var int|null
     */
    private ?int $id = null;
    /**
     * @var string|null
     */
    private ?string $manufacturer = null;
    /**
     * @var string|null
     */
    private ?string $model = null;
    /**
     * @var \DateTime|null
     */
    private ?\DateTime $issueDate = null;
    /**
     * @var int|null
     */
    private ?int $modelYear = null;
    /**
     * @var string|null
     */
    private ?string $vin = null;
    /**
     * @var int|null
     */
    private ?int $mileage = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    /**
     * @param string|null $manufacturer
     */
    public function setManufacturer(?string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string|null $model
     */
    public function setModel(?string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return \DateTime|null
     */
    public function getIssueDate(): ?\DateTime
    {
        return $this->issueDate;
    }

    /**
     * @param \DateTime|null $issueDate
     */
    public function setIssueDate(?\DateTime $issueDate): void
    {
        $this->issueDate = $issueDate;
    }

    /**
     * @return int|null
     */
    public function getModelYear(): ?int
    {
        return $this->modelYear;
    }

    /**
     * @param int|null $modelYear
     */
    public function setModelYear(?int $modelYear): void
    {
        $this->modelYear = $modelYear;
    }

    /**
     * @return string|null
     */
    public function getVin(): ?string
    {
        return $this->vin;
    }

    /**
     * @param string|null $vin
     */
    public function setVin(?string $vin): void
    {
        $this->vin = $vin;
    }

    /**
     * @return int|null
     */
    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    /**
     * @param int|null $mileage
     */
    public function setMileage(?int $mileage): void
    {
        $this->mileage = $mileage;
    }
}