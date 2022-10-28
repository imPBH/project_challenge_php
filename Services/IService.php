<?php

interface IService
{
    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp);
    public function CommentStatusUpdate($comment, $oldStatus, $newStatus, $timestamp);
}