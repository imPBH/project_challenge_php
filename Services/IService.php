<?php

interface IService
{
    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp);
    public function CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $oldStatus, $newStatus, $timestamp);
    public function PostUpdate($title, $color, $post, $postID, $postUrl, $postTitle, $author, $timestamp);
}