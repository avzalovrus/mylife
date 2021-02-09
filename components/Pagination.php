<?php

namespace Component;

/**
 * Class Pagination
 * @package pagination
 *
 * @var object $db
 * @var integer $page
 * @var integer $limit
 * @var string $table
 * @var string $where
 * @var array $variables
 * @var string $url
 *
 * ```?php
 * =============================================================
 * //Create object Pagination
 * =============================================================
 * $var = new Pagination([
 *      'db' => R, // RedBean
 *      'page' => $now_page, //Now page
 *      'limit' => $limit, //Max item
 *      'table' => 'sale', //Tabel DB
 *      'where' => 'status = 1', //SQL
 *      'variables' => [], //array variable
 *      'url' => '/sales/{PAGE}' //template url parination
 * ]) // object
 *
 *
 * =============================================================
 * // Create items
 * =============================================================
 * $items = $var->getPage(); // object R
 *
 *
 * =============================================================
 * // Create pagination
 * =============================================================
 * $var->getPagination([
 *      'prev' => '<li class="page-item">
 *                  <a class="page-link" href="{URL}" tabindex="-1" aria-disabled="true" title="Назад"><svg width="10" height="14" viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.91462 7.72043L7.72817 12.3542L7.45835 12.6134L1.61444 7L7.45835 1.3866L7.72817 1.64579L2.91462 6.27957L2.16624 7L2.91462 7.72043Z" stroke="#6D6D6D" stroke-width="2"/></svg></a>
 *                  </li>',
 *      'template' => '<li class="page-item"><a class="page-link" href="{URL}">{PAGE}</a></li>',
 *      'template-active' => '<li class="page-item active"><a class="page-link" href="{URL}">{PAGE}</a></li>',
 *      'next' => '<li class="page-item">
 *                  <a class="page-link" href="{URL}" title="Вперёд"><svg width="10" height="14" viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.42718 7.72043L1.61362 12.3542L1.88345 12.6134L7.72736 7L1.88345 1.3866L1.61362 1.64579L6.42718 6.27957L7.17556 7L6.42718 7.72043Z" stroke="#6D6D6D" stroke-width="2"/></svg></a>
 *                  </li>',
 * ])
 *
 *
 * =============================================================
 * // Check pagination
 * =============================================================
 * $pagination_news->checkPagination() // bool
 *
 *
 * ?```
 */
class Pagination
{
    private $page,
        $side,
        $limit,
        $table,
        $where,
        $variables,
        $url,
        $offset,
        $db,
        $count,
        $object,
        $pagination;

    public function __construct(Array $array){
        $this->db = $array['db'];
        $this->page = (int)$array['page']??1;
        $this->limit = $array['limit']??12;
        $this->side = $array['side']??2;
        $this->table = $array['table'];
        $this->where = $array['where'];
        $this->variables = $array['variables'];
        $this->url = $array['url'];

        $this->generatePagination();
    }

    /**
     * @return object
     * Get list items
     */
    public function getPage(){
        return $this->object;
    }

    /**
     * @return bool
     * Check pagination
     */
    public function checkPagination(){
        return $this->pagination;
    }

    public function getPagination($templates){
        $max_page = ceil($this->count / $this->limit);
        $template = str_replace("{URL}", $this->url, $templates['template']);
        $template_active = str_replace("{URL}", $this->url, $templates['template-active']);
        $template_prev = str_replace("{URL}", $this->url, $templates['prev']);
        $template_next = str_replace("{URL}", $this->url, $templates['next']);
        if(isset($template_prev)){
            $page = $this->page - 1;
            if($page < 1){
                $page = 1;
            }
            echo str_replace("{PAGE}", $page, $template_prev);
        }
        for($i = 0; $i < $max_page; $i++){
            if($i+1 === $this->page) {
                echo str_replace("{PAGE}", $i + 1, $template_active);
            }
            else if(($i+1 >= $this->page - $this->side) && ($i+1 <= $this->page + $this->side)){
                echo str_replace("{PAGE}", $i + 1, $template);
            }
        }
        if(isset($template_next)){
            $page = $this->page + 1;
            if($page > $max_page){
                $page = $max_page;
            }
            echo str_replace("{PAGE}", $page, $template_next);
        }
    }

    /**
     * @return int
     *
     */
    private function getCount(){
        if($this->count){
            return $this->count;
        }
        $this->count = $this->db::count($this->table, $this->where, $this->variables);
        return $this->count;
    }

    /**
     * @return bool
     * Generation pagination
     */
    private function generatePagination(){
        if($this->getCount() > $this->limit){
            $this->offset = $this->limit * $this->page - $this->limit;
            $this->object = $this->db::findAll($this->table, "$this->where LIMIT $this->limit OFFSET $this->offset", $this->variables);
            $this->pagination = true;
        }else{
            $this->object = $this->db::findAll($this->table, "$this->where", $this->variables);
            $this->pagination = false;
        }
        return true;
    }

    public function test(){

        return str_replace("{PAGE}", $this->page, $this->url);
    }
}

?>