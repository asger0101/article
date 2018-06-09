<?php 

include_once 'C:\xampp\htdocs\article\models\article.php';

class Controller_Index extends article{
    
    protected $article;
    
    public function __construct() {
        $this->article = new article();
    }
    
    public function index($condition = null) {
        
        $this->article->showAllArticles($condition);
        // After above has run, we then render the view
    }
    
    public function singleArticleById() {
        $this->article->showSingleArticle($_GET['id']);
    }
    
    public function createArticle() {
        
        $this->article->makeArticle($_POST);
        
        // After above has run, we then render the view
    }
    
    public function deleteArticle() {
        
        $this->article->deleteArticle($_POST);
        
        // After above has run, we then render the view
    }
    
    public function updateArticle() {
        
        $this->article->updateArticle($_POST);
        
        // After above has run, we then render the view
    }
    
    

}