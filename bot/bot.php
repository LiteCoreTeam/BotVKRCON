<?php
$domen = ''; // ДОМЕН ДЛЯ СООБЩЕНИЯ/РЕДИРЕКТА
$token = ''; // ТОКЕН ГРУППЫ
$anofs = ''; // ОТВЕТ КОТОРЫЙ ДОЛЖЕН ВЕРНУТЬ СЕРВЕР
$adm = 'ddosenka'; // Если цифровой, то idцифры, если буквенный - просто вставить его, например ddosenka

if (!isPost()) {
    header('Location: http://'.$domen);
    exit;
}

include 'vk_api.php';
$vk = new vk_api($token, '5.82');
$data = json_decode(file_get_contents('php://input'));
if ($data->type === 'confirmation') {
	exit($anofs);
}
$vk->sendOK();
$peer_id = $data->object->peer_id;
$id = $data->object->from_id;
$message = $data->object->text;
$messagei = strtolower($message);

if ($data->type === 'message_new') {
    if(substr($messagei, 0, 3) === '/id'){
    	if ($id === $peer_id) {
    		$vk->sendMessage($peer_id, 'Ваш айди: '.$id);
            $vk->sendOK();
    	}else{
    		$vk->sendMessage($peer_id, 'Ваш айди: '.$id.PHP_EOL.'Айди беседы: '. $peer_id);
            $vk->sendOK();
    	}
    	
    	exit();
    }

    $red = substr($messagei, 0, 1);
    if(strpos($red, '/') !== false){
    	if ($id === 421256157 or $id === 197423091 or $id === 387336196 or $id === 301466781 or $id === 209157736 or $id === 455844524 or $id === 384016589) { // Участники тестов.
    	    if (strlen($messagei) === 1 or strlen($messagei) > 50) {
    		    $vk->sendMessage($peer_id, "[❌] Вы некорректно указали команду или её аргумент(ы)!");
    	    }else{
    	        require './SourceQuery/SourceQuery.class.php';
                $Query = new SourceQuery();
                $Query->Connect('127.0.0.1', 19132, 1, 1);
                $Query->setRconPassword('221135');
                $msg = substr($message, 1);
                $msg1 = strtolower($msg);

                if (strpos($msg1, 'dumpmemory') !== false or strpos($msg1, 'stop') !== false or strpos($msg1, 'ms') !== false or strpos($msg1, 'mp') !== false or strpos($msg1, 'reload') !== false or strpos($msg1, 'pocketmine:') !== false or strpos($msg1, 'ep') !== false or strpos($msg1, 'op') !== false or strpos($msg1, 'deop') !== false) {
    		        $vk->sendMessage($peer_id, '[❌] Данная команда запрещена администрацией сервера!');
                    $vk->sendOK();
    	        }else{
    		        $res = $Query->Rcon($msg);
    		        $res1 = ReplaceColorTags($res);
    		        if (strlen($res) > 2) {
                        if (strpos($messagei, "ban") or strpos($messagei, "kick") or strpos($messagei, "mute")) {
                            $vk->sendMessage($peer_id, '@'.$adm.PHP_EOL.PHP_EOL.'[❌] Пользователь @id'.$id.' выполнил команду, которая требует Вашей проверки!');
                        }
    		            $vk->sendMessage($peer_id, '[✔] Команда `'.$msg.'` успешно отправлена');
                        $vk->sendMessage($peer_id, 'Ответ сервера: '.PHP_EOL.$res1);
                        $vk->sendOK();
    	            }else{
                        if (strpos($messagei, "ban") or strpos($messagei, "kick") or strpos($messagei, "mute")) {
                            $vk->sendMessage($peer_id, '@'.$adm.PHP_EOL.PHP_EOL.'[❌] Пользователь @id'.$id.' выполнил команду, которая требует Вашей проверки!');
                        }
    		            $vk->sendMessage($peer_id, '[✔] Команда `'.$msg.'` успешно отправлена');
                        $vk->sendMessage($peer_id, 'Ответ сервера: пусто');
                        $vk->sendOK();
    	            }
                }
            }
        }else{
        	$vk->sendMessage($peer_id, '[❌] У Вас нет прав!'.PHP_EOL.'[❗] Купить доступ к консоли или привилегию можно на сайте: '.$domen);
        }
    }
}

function ReplaceColorTags($str) {
    $find = ["§1","§2","§3","§4","§5","§6","§7","§8","§9","§0","§c","§e","§a","§b","§d","§f","§l","§m","§n","§o","§r"];
    $str = str_replace($find, "", $str);

    return $str;
}

function isPost(){
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}