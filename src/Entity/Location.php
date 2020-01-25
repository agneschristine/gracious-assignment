<?php
namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;

class Location
{
    /**
     * @var int
     * @Serializer\Type("int")
     */
    private $id;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $type;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $dimension;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    private $residents;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $url;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDimension(): string
    {
        return $this->dimension;
    }

    /**
     * @param string $dimension
     */
    public function setDimension(string $dimension)
    {
        $this->dimension = $dimension;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created)
    {
        $this->created = $created;
    }

    /**
     * @return array
     */
    public function getResidents(): array
    {
        return $this->residents;
    }

    /**
     * @param array $residents
     */
    public function setResidents(array $residents)
    {
        $this->residents = $residents;
    }
}
