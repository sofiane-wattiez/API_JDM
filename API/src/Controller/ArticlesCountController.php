<?php
namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Request;

class ArticlesCountController{


public function __construct(public ArticlesRepository $articlesRepository)
    {
    
    }
public function __invoke(Request $request): int
    {
    $onlineQuery = $request->get('online');
    $conditions = [];
    // dd($request->get('online'));
    if ($onlineQuery != null){
        $conditions = ['online' => $onlineQuery === '1' ? true : false ];
    }
    return $this->articlesRepository->count($conditions);
    }

}