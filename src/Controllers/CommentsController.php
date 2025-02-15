<?php
    namespace App\Controllers;

    use App\Models\Comment;
    use function App\Middlewares\isAuthenticated;
    require_once __DIR__ . '../../Middlewares/AuthMiddleware.php';

    class CommentsController {
        private $commentModel;

        public function __construct($database) {
            $this->commentModel = new Comment($database);
        }      

        public function getPostById($postId){
            isAuthenticated();
            return $this->commentModel->getCommentById($postId);
        }

        public function getcommentsByPost($userId){
            isAuthenticated();
            return $this->commentModel->getCommentsByPost($userId);
        }

        public function createComment() {
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));

            return $this->commentModel->createComment($data);
        }

        public function deleteComment($commentId){
            isAuthenticated();
            return $this->commentModel->deleteComment($commentId);
        }

        public function updateComment(){
            isAuthenticated();
            $data = json_decode(file_get_contents("php://input"));
            return $this->commentModel->updateComment($data->comment, $data->id);
        }
    }
