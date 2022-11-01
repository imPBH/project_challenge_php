<?php

class Discord implements IService
{
    private $webhookUrl;
    private $site;

    public function __construct()
    {
        $this->webhookUrl = get_option('discord_url');
        $this->site = get_site_url();
    }

    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp)
    {
        $json_data = json_encode([
            "username" => "WP Admin Notifications",
            "embeds" => [
                [
                    "title" => ":rotating_light: New comment detected :rotating_light:",
                    "type" => "rich",
                    "url" => $postUrl,
                    "timestamp" => $timestamp,
                    "color" => hexdec("3366ff"),
                    "footer" => [
                        "text" => "WP Admin Notifications",
                        "icon_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png"
                    ],
                    "author" => [
                        "name" => $postTitle,
                        "url" => $postUrl
                    ],
                    "fields" => [
                        [
                            "name" => "Author :",
                            "value" => $author,
                            "inline" => true
                        ],
                        [
                            "name" => "Comment :",
                            "value" => $comment->comment_content,
                            "inline" => true
                        ],
                        [
                            "name" => "‎",
                            "value" => "‎"
                        ],
                        [
                            "name" => "Approve",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID . ")",
                            "inline" => true
                        ],
                        [
                            "name" => "Delete",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc)",
                            "inline" => true
                        ],
                        [
                            "name" => "Spam",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam)",
                            "inline" => true
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }

    public function CommentStatusUpdate($title, $color, $comment, $postUrl, $postTitle, $author, $oldStatus, $newStatus, $timestamp)
    {
        $json_data = json_encode([
            "username" => "WP Admin Notifications",
            "embeds" => [
                [
                    "title" => $title,
                    "type" => "rich",
                    "url" => $postUrl,
                    "timestamp" => $timestamp,
                    "color" => $color,
                    "footer" => [
                        "text" => "WP Admin Notifications",
                        "icon_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png"
                    ],
                    "author" => [
                        "name" => $postTitle,
                        "url" => $postUrl
                    ],
                    "fields" => [
                        [
                            "name" => "Author :",
                            "value" => $author,
                            "inline" => true
                        ],
                        [
                            "name" => "Comment :",
                            "value" => $comment->comment_content,
                            "inline" => true
                        ],
                        [
                            "name" => "‎",
                            "value" => "‎"
                        ],
                        [
                            "name" => "Old status :",
                            "value" => $oldStatus,
                            "inline" => true
                        ],
                        [
                            "name" => "New status :",
                            "value" => $newStatus,
                            "inline" => true
                        ],
                        [
                            "name" => "‎",
                            "value" => "‎"
                        ],
                        [
                            "name" => "Approve",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID . ")",
                            "inline" => true
                        ],
                        [
                            "name" => "Delete",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc)",
                            "inline" => true
                        ],
                        [
                            "name" => "Spam",
                            "value" => "[Click here](" . $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam)",
                            "inline" => true
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }

    public function PostUpdate($title, $color, $post, $postID, $postUrl, $postTitle, $author, $timestamp)
    {
        $json_data = json_encode([
            "username" => "WP Admin Notifications",
            "embeds" => [
                [
                    "title" => $title,
                    "type" => "rich",
                    "url" => $postUrl,
                    "timestamp" => $timestamp,
                    "color" => $color,
                    "footer" => [
                        "text" => "WP Admin Notifications",
                        "icon_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png"
                    ],
                    "author" => [
                        "name" => $postTitle,
                        "url" => $postUrl
                    ],
                    "fields" => [
                        [
                            "name" => "Author :",
                            "value" => $author
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }
}