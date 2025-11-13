<?php

namespace App\Entity;



use App\Repository\ProfesorRepository;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfesorRepository::class)]

class Profesor

{

    #[ORM\Id]

    #[ORM\GeneratedValue]

    #[ORM\Column]

    private ?int $id = null;



    #[ORM\Column(length: 255)]

    #[Assert\NotBlank]

    private ?string $nombre = null;



    #[ORM\Column(length: 15)]

    #[Assert\NotBlank]

    private ?string $telefono = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email(message:"El email {{ value }} no es válido")]

    private ?string $email = null;



    #[ORM\ManyToOne(inversedBy: 'profesor')]

    #[Assert\NotBlank]

    private ?Instituto $instituto = null;



    public function getId(): ?int

    {

        return $this->id;

    }



    public function getNombre(): ?string

    {

        return $this->nombre;

    }



    public function setNombre(?string $nombre): self

    {

        $this->nombre = $nombre;



        return $this;

    }



    public function getTelefono(): ?string

    {

        return $this->telefono;

    }



    public function setTelefono(?string $telefono): self

    {

        $this->telefono = $telefono;



        return $this;

    }



    public function getEmail(): ?string

    {

        return $this->email;

    }



    public function setEmail(?string $email): self

    {

        $this->email = $email;



        return $this;

    }



    public function getInstituto(): ?Instituto

    {

        return $this->instituto;

    }



    public function setInstituto(?Instituto $instituto): self

    {

        $this->instituto = $instituto;



        return $this;

    }

}
