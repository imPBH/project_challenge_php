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

        $this->discord->CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $old_status, $new_status, $timestamp);
        $this->slack->CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $old_status, $new_status, $timestampSlack);
        $this->telegram->CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $old_status, $new_status, $timestamp);
    }

    public function PostUpdate($newStatus, $oldStatus, $post)
    {
        $postID = $post->ID;
        $postUrl = get_post_permalink($postID);
        $postTitle = $post->post_title;
        $postAuthorID = $post->post_author;
        $postAuthor = get_user_by("id", $postAuthorID);
        $postAuthorName = $postAuthor->display_name;
        $timestamp = date("c", strtotime("now"));
        $timestampSlack = time();

        $title = "";
        $color = "";
        switch ($newStatus) {
            case "publish":
                switch ($oldStatus) {
                    case "auto-draft":
                        $title = ":rotating_light: New post detected ! :rotating_light:";
                        $color = hexdec("7CFC00");
                        break;
                    case "publish":
                        $title = ":pencil: Update detected on a post ! :pencil:";
                        $color = hexdec("FF8C00");
                        break;
                }
                break;
            case "trash":
                $title = ":wastebasket: Post trashed ! :wastebasket:";
                $color = hexdec("FF0000");
                break;
            case "draft":
                $title = ":grey_question: New post in draft ! :grey_question:";
                $color = hexdec("FF8C00");
                break;
        }
        $this->discord->PostUpdate($title, $color, $post, $postID, $postUrl, $postTitle, $postAuthorName, $timestamp);
        $this->slack->PostUpdate($title, $color, $post, $postID, $postUrl, $postTitle, $postAuthorName, $timestampSlack);
    }
}