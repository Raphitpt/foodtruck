<?php
require('config.local.php');

class Auth
{
    public function create($userData)
    {
        // Code pour insérer un nouvel utilisateur dans la base de données
        $hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $query = $pdo->prepare("INSERT INTO users SET
        nom = :nom,
        prenom = :prenom,
        email = :email,
        passwd = :passwd,
        pts_fidelite = :pts_fidelite");
        $query->execute([ //Execute la requête
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'passwd' => $hash,
            'pts_fidelite' => $userData['pts_fidelite']
        ]);
        return $pdo->lastInsertId(); //Retourne l'ID du dernier utilisateur inséré
    }

    public function find($userId)
    {
        // Code pour récupérer un utilisateur dans la base de données
        $query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $query->execute([
            'id' => $userId
        ]);
        return $query->fetch();
    }

    public function delete($userId)
    {
        // Code pour supprimer un utilisateur dans la base de données
        $query = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $query->execute([
            'id' => $userId
        ]);
    }

    public function update($userData)
    {
        // Code pour mettre à jour un utilisateur dans la base de données
        $query = $pdo->prepare("UPDATE users SET
        nom = :nom,
        prenom = :prenom,
        email = :email,
        passwd = :passwd,
        pts_fidelite = :pts_fidelite
        WHERE id = :id");
        $query->execute([
            'id' => $userData['id'],
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'passwd' => $userData['passwd'],
            'pts_fidelite' => $userData['pts_fidelite']
        ]);
    }



}
?>





<?php

class PostController {
    
        public function index(){
            $post = new Post();
            $posts = $post->findAllPublished();
            require 'view/posts.php';
        }
    
        public function show($postId){
            $post = new Post();
            $post = $post->find($postId);
            require 'view/post.php';
        }
    
        public function create(){
            $post = new Post();
            $post->create($_POST);
            header('Location: /posts');
        }
    
        public function edit($postId){
            $post = new Post();
            $post = $post->find($postId);
            require 'view/editPost.php';
        }
    
        public function update($postId){
            $post = new Post();
            $post->update($_POST);
            header('Location: /posts');
        }
    
        public function delete($postId){
            $post = new Post();
            $post->delete($postId);
            header('Location: /posts');
        }
}
