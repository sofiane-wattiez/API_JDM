<?php

namespace App\Entity;

use App\Controller\ArticlesPublishController;
use App\Controller\ArticlesCountController;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
#[ApiResource(
    // attributes:[
    //     'validation_groups' => []
    // ],
    normalizationContext: [
        'groups' => ['read:collection'],
        'openapi_definition_name' => 'Collection'
    ] ,
    denormalizationContext: [
        'groups' => ['write:Articles']
    ] ,
    paginationItemsPerPage:5,
    paginationMaximumItemsPerPage:5,
    paginationClientItemsPerPage:true,
    collectionOperations:[
        'get' ,
        'post',
        'count' => [
            'method' => 'GET',
            'path' => '/articles/count',
            'controller' => ArticlesCountController::class ,
            'read' => false,
            'pagination_enabled' => false,
            'filters' => [] ,
            'openapi_context' => [
                'summary' => 'Récurpère le nombre total d\'article',
                'parameters' => [
                    [
                    'in' => 'query',
                    'name' => 'online',
                    'schema' =>
                        [
                            'type' => 'query',
                            'maximum' => 1,
                            'minimum' => 0
                        ],
                    'description' => 'Filtre les articles en ligne , écrire 1 pour demander les articles en ligne actuellement'
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'OK',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'integer',
                                    'example' => 3
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
        // => [
        //     'validation_groups' => [ Articles::class, 'validationGroups' ] 
        // ]
    ],
    itemOperations: [
        'put' ,
        'delete',
        'get'   => [
            'normalization_context' => ['groups' => ['read:collection' ,'read:item' , 'read:Articles']],
            'openapi_definition_name' => 'Detail'
        ],
        // permet de modifier l'interface swagger
        'publish' => [
            'method' => 'POST',
            'path' => '/articles/{id}/publish',
            'controller' => ArticlesPublishController::class ,
            'openapi_context' => [
            'summary'=> 'Permet de publier un article' ,
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => []
                    ]
                ]
                ]
            ]
        ]
    ]
        ),
ApiFilter(SearchFilter::class, properties: ['id' => 'exact' , 'title' => 'partial' ])
]
// Choice class SearchFilter from Doctrine ORM

class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Groups(['read:collection' , 'write:Articles']),
        Length(min:5, max:50 , groups:['create:Articles'])
    ]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection' , 'write:Articles'])]
    private $slug;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:item' , 'write:Articles'])]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $updateAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles', cascade: ['persist'] ) ]
    #[Groups(['read:item' , 'write:Articles']),
        Valid()
    ]
    private $category;

    #[ORM\Column(type: 'boolean' , options: ['default' => '0'] )]
    #[Groups(['read:collection']),
        ApiProperty(openapiContext:['type' => 'boolean' , 'description' => 'Statut de connexion'])
    ]
    private $online = false;

    // public static function validationGroups(self $articles)
    // {
    //     // dd($post);
    //     return ['create:Articles'];
    // }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
