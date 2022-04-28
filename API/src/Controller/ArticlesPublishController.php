<?php
namespace App\Controller;

use App\Entity\Articles;

class ArticlesPublishController{


    public function __invoke(Articles $data): Articles
    {
        $data->setOnline(true);
        return $data;
        
    }


}