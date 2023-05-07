<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GroupeCountController;
use App\Controller\GroupePublishController;
use App\Repository\GroupeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[
    ApiResource(
        paginationItemsPerPage: 2,
        paginationMaximumItemsPerPage: 2,
        paginationClientItemsPerPage: true,
        normalizationContext: [
            'groups' => ['read:collection'],
            'openapi_definition_name' => 'Collection'
        ],
        denormalizationContext: ['groups' => ['write:Groupe']],
        operations: [
            new GetCollection(
                name: 'count',
                uriTemplate: '/groupes/count',
                controller: GroupeCountController::class,
                // filters: [],
                paginationEnabled: false,
                openapiContext: [
                    'summary' => 'Récupére le nombre total de groupes',
                    'parameters' => [
                        [
                            'in' => 'query',
                            'name' => 'online',
                            'schema' => [
                                'type' => 'integer',
                                'maximum' => 1,
                                'minimum' => 0
                            ],
                            'description' => 'Filtre les groupes en ligne'
                        ]
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'OK',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'integer',
                                        'example' => 4
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ),
            new Get(
                normalizationContext: [
                    'groups' => ['read:item', 'read:Groupe'],
                    'openapi_definition_name' => 'Detail'
                ]
            ),
            new Post(validationContext: ['groups' => ['create:Groupe']]),
            new Post(
                name: 'publish',
                uriTemplate: '/groupes/{id}/publish',
                controller: GroupePublishController::class,
                openapiContext: [
                    'summary' => 'Permet de publier un groupe',
                    // 'requestBody' => [
                    //     'content' => [
                    //         'application/json' => [
                    //             'schema' => []
                    //         ]
                    //     ]
                    // ]
                ]
            ),
            new Put(),
            new Delete()
        ]
    ),
    ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial'])
]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Groups(['read:collection', 'write:Groupe']),
        Length(min: 5, groups: ['create:Groupe'])
    ]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:collection', 'write:Groupe'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:item', 'write:Groupe'])]
    private ?string $contact = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:item', 'write:Groupe'])]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'groupes', cascade: ['persist'])]
    #[Groups(['read:item', 'write:Groupe'])]
    private ?Region $region = null;

    #[ORM\Column(options: ["default" => 0])]
    #[
        Groups('read:collection'),
        ApiProperty(openapiContext: ['type' => 'boolean', 'description' => 'En ligne ou pas'])
    ]
    private ?bool $online = false;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
