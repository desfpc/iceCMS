<?php

function changeURL($url, $page, $oldpage){

    if(mb_strpos($url,'page='.$oldpage,0,'UTF8') !== false){
        $url = str_replace('page='.$oldpage, 'page='.$page, $url);
    }
    else{
        if(mb_strpos($url, '?', 0, 'UTF8') !== false){
            $url.='&page='.$page;
        }
        else{
            $url.='?page='.$page;
        }
    }

    return $url;
}

function printPage($url, $page, $apage){
    if($page == $apage){
        return '<li class="page-item active" aria-current="page"><span class="page-link">'.$page.'<span class="sr-only">(current)</span></span></li>';
    }
    return '<li class="page-item"><a class="page-link" href="'.$url.'">'.$page.'</a></li>';
}

/*$test = [
    'count' => 531,
    'perpage' => 20,
    'page' => 25,
    'url' => '/?menu=materials_admin&mtype=all&page=25'
];

$this->params = $test;*/

$page = (int)$this->params['page'];
if($page == 0){
    $page = 1;
}
$url = $this->params['url'];

$dr = $this->params['count'] / $this->params['perpage'];
$pageCnt = (int)$dr;
if($pageCnt < $dr){
    ++$pageCnt;
}

$text = '';
//предыдущаа запись
if($page > 1){
    $newPage = $this->params['page'] - 1;
    $text .= '<li class="page-item"><a class="page-link" href="'.changeURL($url, $newPage, $page).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
}
//первая страница
$text .= printPage(changeURL($url, 1, $page), 1, $page);

//страницы в теле
if($pageCnt < 10){
    $start = 2;
    $end = ($pageCnt-1);
}
elseif(($pageCnt - $page) < 7) {
    $start = $pageCnt - 7;
    $end = $pageCnt - 1;
}
elseif($page < 8) {
    $start = 2;
    $end = 8;
}
else {
    $start = $page - 3;
    $end = $page + 3;
}
if($start > 2){
    $text .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
}
for($i = $start; $i <= $end; ++$i){
    $text .= printPage(changeURL($url, $i, $page), $i, $page);
}
if($end < ($pageCnt - 1)){
    $text .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
}

//последняя страница
if($pageCnt > 1){
    $text .= printPage(changeURL($url, $pageCnt, $page), $pageCnt, $page);
//следующая запись
    if($page < $pageCnt){
        $newPage = $this->params['page'] + 1;
        $text .= '<li class="page-item"><a class="page-link" href="'.changeURL($url, $newPage, $page).'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
    }
}

//выводим
if($text != ''){
    echo '<nav aria-label="Page navigation"><ul class="pagination">';
    echo $text;
    echo '</ul></nav>';
}