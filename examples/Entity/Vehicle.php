<?php declare(strict_types=1);

namespace Example\Entity;

/**
 * Vehicle object representation.
 */
class Vehicle
{
    private ?int $id = null;
    private ?string $build = null;
    private ?\DateTimeInterface $issueDate = null;
    private ?int $mileage = null;

    /**
     * @param string|null $build
     *
     * @return Vehicle
     */
    public function setBuild(?string $build): self
    {
        $this->build = $build;

        return $this;
    }

    /**
     * @param \DateTimeInterface|null $issueDate
     *
     * @return Vehicle
     */
    public function setIssueDate(?\DateTimeInterface $issueDate): self
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    /**
     * @param int|null $mileage
     *
     * @return Vehicle
     */
    public function setMileage(?int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    /**
     * @return int|null
     */
    public function getMileage(): ?int
    {
        return $this->mileage;
    }
}
