<?php
    include('methods.php');
    include('database.php');
    $db = new database();
    $request = new methods();
    $content = file_get_contents("php://input");
    $content = json_decode($content, true);
    $text = $content['message']['text'];
    $userid  = $content['message']['chat']['id'];
    $username = $content['message']['chat']['username'];

    $curmess = $db->getcurmes($userid);
        

        switch ($text){
            case '/start':
                if(empty($db->getuser($userid))) $db->setuser($userid,$username);
                $keyboard = [['Игра', 'Профиль']];
                $request->sendmessage($userid, 'Добро пожаловать', $keyboard);
                break;
            case 'Профиль':
                    $keyboard = [['Назад']];
                    $user = $db->getuser($userid);
                    $request->sendmessage($userid, 'Профиль');
                break;
            case 'Игра':
                    $keyboard = [['Начать', 'Назад']];
                    $request->sendmessage($userid, 'Игра', $keyboard);
                break;
                # curmess = 3
            case 'Начать':
                    $db->setcurmes($userid, 3);
                    $words = $db->getword(20);
                    $db->setstep($userid, 1);
                    $db->setword($userid, $words);
                    $keyboard = [$db->getvariant($userid, $db->getstep($userid))];
                    $request->sendmessage($userid, $words[0], $keyboard);
                break;


        }
        switch ($curmess){
            case '1':
                    $db->addwords($text);
                break;


            case '':

                break;
            case '3':
                if($db->getvariants($userid, $text) == true){
                    $answer = $db->getanswer($userid, $db->getstep($userid));
                    if(in_array($text, $answer)) $request->sendmessage($userid, 'Верно');
                    else $request->sendmessage($userid, 'Неверно');
                    if($db->getstep($userid) == 20){ $keyboard = [['Игра', 'Профиль']]; $request->sendmessage($userid, 'Вы прошли игру', $keyboard); $db->setstep($userid,null); $db->setcurmes($userid, null); $db->setword($userid, null); }
                    else {$db->setstep($userid, $db->getstep($userid)+1); $words = $db->getcurword($userid); $request->sendmessage($userid, $words[$db->getstep($userid)-1], [$db->getvariant($userid, $db->getstep($userid))]);}
                }

                else $request->sendmessage($userid, 'Вы ввели неверное значение');

                break;






        }
