<?php

class AuthController
{

    public function create()
    {
        $post = new Auth();
        $post->create($_POST);
        header('location: /AuthVue.php');
    }

    public function find($postId)
    {
        $post = new Auth();
        $post = $post->find($postId);
        require 'Vue/AuthVue.php';
    }

    public function update($postId)
    {
        $post = new Auth();
        $post->update($_POST);
        header('Location: /AuthVue.php');
    }

    public function delete($postId)
    {
        $post = new Auth();
        $post->delete($postId);
        header('Location: /AuthVue.php');
    }
}