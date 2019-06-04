<?php
class methods{

    private $token = '814683166:AAFx3BUIQCkJ_P9KN8GJSrm62zlQkde-SwM';

    public function main($method, $parametrs){
        $parametrs = http_build_query($parametrs, false, '&');
        file_get_contents('https://api.telegram.org/bot'.$this->token.'/'.$method.'?'.$parametrs);
    }

    public function sendmessage($userid, $text, $keyboard = false, $ikeyboard = false){
        $method = 'sendmessage';
        if($ikeyboard != false){
            $keyboard = ['inline_keyboard' => $keyboard, 'resize_keyboard' => true];
            $keyboard = json_encode($keyboard);
            $parametrs = ['chat_id' => $userid, 'text' => $text, 'reply_markup' => $keyboard];
        }
        if($keyboard != false) {
            $keyboard = ['keyboard' => $keyboard, 'resize_keyboard' => true];
            $keyboard = json_encode($keyboard);
            $parametrs = ['chat_id' => $userid, 'text' => $text, 'reply_markup' => $keyboard];
        }
        else {
            $parametrs = ['chat_id' => $userid, 'text' => $text];
        }
        $this->main($method, $parametrs);

    }
}