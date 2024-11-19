<?php
    namespace App\Controllers;

    use App\Models\Post;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class PostsController {
        private $postModel;

        public function __construct($database) {
            $this->postModel = new Post($database);
        }

        public function getAllPosts() {
            isAuthenticated();
            return $this->postModel->getAllPosts();
        }

        public function getPostById($postId){
            isAuthenticated();
            return $this->postModel->getPostById($postId);
        }

        public function getPostsByUser($userId){
            isAuthenticated();
            return $this->postModel->getPostsByUser($userId);
        }

        public function createPost() {
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->postModel->createPost($data->post,$data->user_id);
        }

        public function deletePost($postId){
            isAuthenticated();
            return $this->postModel->deletePost($postId);
        }

        public function updatePost(){
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->postModel->updatePost($data->post, $data->id);
        }
    }
