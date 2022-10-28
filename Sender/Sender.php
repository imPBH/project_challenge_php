<?php

class Sender
{
    private $discord;
    private $slack;
    private $telegram;

    public function __construct()
    {
        $this->discord = new Discord();
        $this->slack = new Slack();
        $this->telegram = new Telegram();
    }

    public static function Send($data, $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function NewComment($comment_id)
    {
        $comment = get_comment($comment_id);
        $postID = $comment->comment_post_ID;
        $postUrl = get_post_permalink($postID);
        $post = get_post($postID);
        $postTitle = $post->post_title;
        $timestamp = date("c", strtotime("now"));
        $timestampSlack = time();
        $author = $comment->comment_author;

        if ($author == "") {
            $author = "Anonymous user";
        }

        $this->discord->NewComment($comment, $postUrl, $postTitle, $author, $timestamp);
        $this->slack->NewComment($comment, $postUrl, $postTitle, $author, $timestampSlack);
        $this->telegram->NewComment($comment, $postUrl, $postTitle, $author, $timestamp);
    }

    public function CommentStatusUpdate($new_status, $old_status, $comment)
    {

    }
}