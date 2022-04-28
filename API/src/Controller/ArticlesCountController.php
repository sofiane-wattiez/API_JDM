<?php
namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;

class ArticlesCountController{


public function __construct(public ArticlesRepository $articlesRepository)
    {
    
    }
public function __invoke(): int
    {
    return $this->articlesRepository->count([]);
    }

}