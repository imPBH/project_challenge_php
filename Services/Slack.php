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
}