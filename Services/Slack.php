<?php

class Slack implements IService
{

    private $webhookUrl;
    private $site;

    public function __construct()
    {
        $this->webhookUrl = get_option('slack_url');
        $this->site = get_site_url();
    }

    public function NewComment($comment, $postUrl, $postTitle, $author, $timestamp)
    {
        $json_data = json_encode([
            "blocks" => [
                [
                    "type" => "header",
                    "text" => [
                        "type" => "plain_text",
                        "text" => ":rotating_light: New comment detected :rotating_light:"
                    ]
                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Original post :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "<$postUrl|$postTitle>"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Author :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "$author"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Comment :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "$comment->comment_content"
                    ]
                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "actions",
                    "elements" => [
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Approve"
                            ],
                            "style" => "primary",
                            "url" => $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID
                        ],
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Delete"
                            ],
                            "style" => "danger",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc"
                        ],
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Spam"
                            ],
                            "style" => "danger",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam"
                        ]
                    ]

                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "context",
                    "elements" => [
                        [
                            "type" => "image",
                            "image_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png",
                            "alt_text" => "Wordpress logo"
                        ],
                        [
                            "type" => "plain_text",
                            "text" => "Wordpress Plugin • " . date("F j, Y, G:i T", $timestamp)
                        ]
                    ]
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        Sender::Send($json_data, $this->webhookUrl);
    }

    public function CommentStatusUpdate($comment, $postUrl, $postTitle, $author, $oldStatus, $newStatus, $timestamp)
    {
        $title = ":tools: Update detected on a comment :tools:";
        switch ($newStatus) {
            case "approved":
                $title = ":white_check_mark: Approved comment :white_check_mark:";
                break;
            case "delete":
                $title = ":x::wastebasket: Comment permanently deleted :wastebasket::x:";
                break;
            case "trash":
                $title = ":wastebasket: Comment trashed :wastebasket:";
                break;
            case "spam":
                $title = ":x: Comment put in the spam section :x:";
                break;
            case "unapproved":
                $title = ":grey_question: Unapproved comment :grey_question:";
                break;
        }

        $json_data = json_encode([
            "blocks" => [
                [
                    "type" => "header",
                    "text" => [
                        "type" => "plain_text",
                        "text" => $title
                    ]
                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Original post :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "<$postUrl|$postTitle>"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Author :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "$author"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Comment :*"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "$comment->comment_content"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*Old status :* $oldStatus"
                    ]
                ],
                [
                    "type" => "section",
                    "text" => [
                        "type" => "mrkdwn",
                        "text" => "*New status :* $newStatus"
                    ]
                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "actions",
                    "elements" => [
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Approve"
                            ],
                            "style" => "primary",
                            "url" => $this->site . "/wp-admin/comment.php?action=editcomment&c=" . $comment->comment_ID
                        ],
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Delete"
                            ],
                            "style" => "danger",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc"
                        ],
                        [
                            "type" => "button",
                            "text" => [
                                "type" => "plain_text",
                                "text" => "Spam"
                            ],
                            "style" => "danger",
                            "url" => $this->site . "/wp-admin/comment.php?c=" . $comment->comment_ID . "&action=cdc&dt=spam"
                        ]
                    ]

                ],
                [
                    "type" => "divider"
                ],
                [
                    "type" => "context",
                    "elements" => [
                        [
                            "type" => "image",
                            "image_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png",
                            "alt_text" => "Wordpress logo"
                        ],
                        [
                            "type" => "plain_text",
                            "text" => "Wordpress Plugin • " . date("F j, Y, G:i T", $timestamp)
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