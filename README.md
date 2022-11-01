# WordPress plugin for administration

## Description
This plugin is a simple administration plugin for WordPress. It just sends logs to some webhooks.
It currently supports sending notifications to :
- Discord
- Slack
- Telegram

### Notifications
The plugin sends notifications for multiple events.
#### Posts
The plugin sends notifications when :
- A post is published
- A post is updated
- A post is deleted
- A post is drafted

Discord example :

![Published post discord](http://image.noelshack.com/fichiers/2022/44/2/1667327231-published-post-discord.png)

Slack example :

![Published post slack](http://image.noelshack.com/fichiers/2022/44/2/1667327295-published-post-slack.png)

Telegram example :

![Published post telegram](http://image.noelshack.com/fichiers/2022/44/2/1667327404-published-post-telegram.png)

#### Comments
The plugin sends notifications when :
- A new comment is posted
- A comment is approved
- A comment is unapproved
- A comment is deleted
- A comment is spammed
- A comment is permanently deleted


Discord example :

![New comment discord](http://image.noelshack.com/fichiers/2022/44/2/1667327626-comment-discord.png)

Slack example :

![New comment slack](http://image.noelshack.com/fichiers/2022/44/2/1667327668-comment-slack.png)

Telegram example :

![New comment telegram](http://image.noelshack.com/fichiers/2022/44/2/1667327707-comment-telegram.png)

## Installation
1. Download the plugin as a zip file
2. Upload the plugin to your WordPress website (Plugins > Add new > Upload plugin)
3. Activate the plugin
4. Go to the plugin settings page (Settings > Admin Notifications Plugin)
5. Fill the settings and save them

![Settings page](http://image.noelshack.com/fichiers/2022/44/2/1667328061-settings.png)

### Webhooks configuration
If you don't know how to configure webhooks, you can follow the following tutorials :
- [Discord](https://www.svix.com/resources/guides/how-to-make-webhook-discord/)
- [Slack](https://api.slack.com/incoming-webhooks)

For telegram, you need to create a bot and get the bot token. You can follow this tutorial : [Create a bot for Telegram](https://www.siteguarding.com/en/how-to-get-telegram-bot-api-token).

Once you have the bot token, you need to start a conversation with the bot by clicking "Start Bot"

![Start conversation](http://image.noelshack.com/fichiers/2022/44/2/1667328453-start-bot-telegram.png)

Then, you need to get your chat id. You can follow this tutorial : [Get chat id for Telegram](https://www.alphr.com/find-chat-id-telegram/)