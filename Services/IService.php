<?php

interface IService
{
    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp);
}