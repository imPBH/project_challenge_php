<?php

class Telegram implements IService
{
    private $botKey;
    private $channelId;
    private $site;
    private $webhookUrl;

    public function __construct()
    {
        $this->botKey = get_option('telegram_bot_key');
        $this->channelId = get_option('telegram_channel_id');
        $this->site = get_site_url();
        $this->webhookUrl = "https://api.telegram.org/bot" . $this->botKey . "/sendMessage";

    }

    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp)
    {
        $json_data = json_encode([
            "chat_id" => $this->channelId,
            "text" => str_replace("!", "\!", "__*\xF0\x9F\x9A\xA8 New comment detected \xF0\x9F\x9A\xA8*__\n\n*Original Post:*\n[" . $postTitle . "](" . $postUrl . ")\n\n*Author :*\n" . $author . "\n\n*Comment :*\n" . $comment->comment_content),
            "parse_mode" => "markdownv2",
            "reply_markup" => [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "Approve",
                            "url" => $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID
                        ],
                        [
                            "text" => "Delete",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc"
                        ],
                        [
                            "text" => "Spam",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam"
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }

    public function CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $oldStatus, $newStatus, $timestamp)
    {
        $title = "\xF0\x9F\x94\xA8 Update detected on a comment \xF0\x9F\x94\xA8";
        switch ($newStatus) {
            case "approved":
                $title = "\xE2\x9C\x85 Approved comment \xE2\x9C\x85";
                break;
            case "delete":
                $title = "\xE2\x9D\x8C🗑 Comment permanently deleted 🗑\xE2\x9D\x8C";
                break;
            case "trash":
                $title = "🗑 Comment trashed 🗑";
                break;
            case "spam":
                $title = "\xE2\x9D\x8C Comment put in the spam section \xE2\x9D\x8C";
                break;
            case "unapproved":
                $title = "\xE2\x9D\x94 Unapproved comment \xE2\x9D\x94";
                break;
        }

        $json_data = json_encode([
            "chat_id" => $this->channelId,
            "text" => str_replace("!", "\!", "__*$title*__\n\n*Original Post:*\n[" . $postTitle . "](" . $postUrl . ")\n\n*Author :*\n" . $author . "\n\n*Comment :*\n" . $comment->comment_content . "\n\n*Old status :*\n$oldStatus\n\n*New status :*\n$newStatus"),
            "parse_mode" => "markdownv2",
            "reply_markup" => [
                "inline_keyboard" => [
                    [
                        [
                            "text" => "Approve",
                            "url" => $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID
                        ],
                        [
                            "text" => "Delete",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc"
                        ],
                        [
                            "text" => "Spam",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam"
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }

    public function PostUpdate($title, $color, $post, $postID, $postUrl, $postTitle, $author, $timestamp)
    {
        // TODO: Implement PostUpdate() method.
    }
}