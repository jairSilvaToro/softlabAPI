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

        public function getAllPosts($page,$limit) {
            isAuthenticated();
            $offset = ($page - 1) * $limit;
            $posts = $this->postModel->getAllPosts($limit,$offset);
            $totalPosts = $this->postModel->getTotalPosts();

            return [
                "data" => $posts,
                "pagination" => [
                    "current_page" => $page,
                    "per_page" => $limit,
                    "total" => $totalPosts,
                    "total_pages" => ceil($totalPosts / $limit)
                ]
            ];
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
