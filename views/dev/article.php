<?php

/* @var $this yii\web\View */

/* @var $article \app\models\Article */

use yii\helpers\Html;

$this->title = 'getbike.io -' . $article->title;
if($article->page_title)
    $this->title = $article->page_title;
?>
<style>
    body {
        max-height: 100%;
        height: auto;
        overflow: auto;
    }
    .main {
        max-height: 100%;
        height: auto;
        padding-bottom: 50px;
    }
    [id^="article-"] .article_content p{
        max-width: 100%;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    [id^="article-"] .article_content img{
        max-width: 100%;
    }
    @media only screen and (max-width: 1019px) and (min-width: 320px){
        .content__second__list__item__title {
            font-size: 25px;
            line-height: 46px;
            margin-top: 30.53vh;
        }
        .content{
            margin-top: 30px;
        }
    }
</style>
<noindex>
<div class="content" id="article-<?= $article->en_title ?>">
    <div class="content__first__block__title">
        <p><?= $article->title ?></p>
    </div>
    <div class="article_content">
        <?= $article->text ?>
    </div>
</div>
<?=\app\widgets\Bikesform::widget()?>
</noindex>
