<?php

include_once 'C:\xampp\htdocs\article\libs\crud.php';

class article extends crud{

    protected $crud;
    
    public function __construct() {
        $this->crud = new crud();
    }
    
    public function makeArticle($postData) {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        $introductory_paragraph = filter_input(INPUT_POST, 'introductory_paragraph', FILTER_SANITIZE_STRING);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        
        $this->crud->insert('article', 
                array(
                    "title" => $title,
                    "text" => $text,
                    "intro" => $introductory_paragraph,
                    "author" => $author,
                    "created_at" => date("Y-m-d H:i:s")
                ));
    }
    
    public function showAllArticles($condition) {
        $this->crud->findAll($condition);
    }
    
    public function showSingleArticle($id) {
        $this->crud->findById($id[0]);
    }
    
    public function updateArticle($postData) {
        $id = FILTER_INPUT(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        $introductory_paragraph = filter_input(INPUT_POST, 'introductory_paragraph', FILTER_SANITIZE_STRING);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        
        $this->crud->update('article', 
                array(
                    "title" => $title,
                    "text" => $text,
                    "intro" => $introductory_paragraph,
                    "author" => $author,
                    "created_at" => date("Y-m-d H:i:s")
                ),$id);
    }
    
    public function deleteArticle($id) {
        $id = FILTER_INPUT(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        
        $this->crud->delete('article',$id);
    }

}
