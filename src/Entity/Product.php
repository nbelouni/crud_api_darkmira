<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Assert\Positive
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @Assert\Positive
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

	static function validate($obj, ?string $method): bool 
	{
		$values = ["name" => false, "description" => true, "price"=> true, "quantity"=> true]; // key : property => val : return value (see mandatory fields)
		if ($method === "PUT")
			$values["id"] =  false;

		foreach ($values as $key => $val)
		{
				if (!property_exists($obj, strtolower($key)))
						return $val;
		}
		return true;
	}
}
