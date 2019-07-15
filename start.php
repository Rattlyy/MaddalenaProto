<?php
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
include "functions.php";
class EventHandler extends \danog\MadelineProto\EventHandler
{
    public function __construct($MadelineProto)
    {
        parent::__construct($MadelineProto);
    }
    public function onAny($update)
    {
        $msg          = null;
        $userID       = null;
        $msgID        = null;
        $info         = null;
        $chatID       = null;
        $type         = null;
        $name         = null;
        $title        = null;
        $username     = null;
        $chatusername = null;
        
        if (isset($update['message']['from_id']))
            $userID = $update['message']['from_id'];
        if (isset($update['message']['id']))
            $msgID = $update['message']['id'];
        if (isset($update['message']['message']))
            $msg = $update['message']['message'];
        if (isset($update['message']['to_id'])) $info['info']['to'] = yield $this->get_info($update['message']['to_id']);
        if (isset($info['info']['to']['bot_api_id']))
            $chatID = $info['info']['to']['bot_api_id'];
        if (isset($info['info']['to']['type']))
            $type = $info['info']['to']['type'];
        if (isset($userID)) $info['info']['from'] = yield $this->get_info($userID);
        if (isset($info['info']['to']['User']['self']) and isset($userID) and $info['info']['to']['User']['self'])
            $chatID = $userID;
        if (isset($type) and $type == 'chat')
            $type = 'group';
        if (isset($info['info']['from']['User']['first_name']))
            $name = $info['info']['from']['User']['first_name'];
        if (isset($info['info']['to']['Chat']['title']))
            $title = $info['info']['to']['Chat']['title'];
        if (isset($info['info']['from']['User']['username']))
            $username = $info['info']['from']['User']['username'];
        if (isset($info['info']['to']['Chat']['username']))
            $chatusername = $info['info']['to']['Chat']['username'];
        $MadelineProto = $this;
        
        include "bot.php";
    }
}
$MadelineProto = new \danog\MadelineProto\API('bot.madeline');
$MadelineProto->async(true);
$MadelineProto->loop(function() use ($MadelineProto)
{
    yield $MadelineProto->start();
    yield $MadelineProto->setEventHandler('\EventHandler');
});
$MadelineProto->loop();
