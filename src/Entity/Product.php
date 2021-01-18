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
     * @ORM\Column(type="decimal", scale=2, nullable=true)
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
		$values = ["name" => [
					"type" => "string",
					"return" => false
				],
				"description" => [
					"type" => "string",
					"return" => true,
				],
				"price"=> [
					"type" => "double",
					"return" => true,
				],
				"quantity"=> [
					"type" => "integer",
					"return" => true,
				]
		]; 
		if ($method === "PUT")
			$values["id"] =  false;

		$tmp = get_object_vars($obj);
		foreach ($values as $key => $val)
		{
			if (!array_key_exists($key, $tmp) || gettype($tmp[$key]) !== $val["type"])
				return $val["return"];
		}
		return true;
	}
}
