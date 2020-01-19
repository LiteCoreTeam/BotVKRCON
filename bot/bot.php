<?php
$buydom = "a";
function isPost(){
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}
if (!isPost()) {
    header('Location: http://shop.redex-mc.fun/'); // РЕДИРЕКТ ПРИ ЗАПРОСЕ РЕАЛЬНОГО ПОЛЬЗОВАТЕЛЯ, Т.К ЭТО ПАЛИТ ВОЗМОЖНО ВРЕДОНОСНЫЙ ЗАПРОС
    exit;
}
include 'vk_api.php';
$vk = new vk_api('8072af487161239bf6a770b592ef3a347eb58bedeaee0999b6a25a754a7c35b1e1ce82c2c84082c62a832', '5.81');
$data = json_decode(file_get_contents('php://input'));
if ($data->type === 'confirmation') {
	exit('c5fb6d3f'); // ОТВЕТ КОТОРЫЙ ДОЛЖЕН ВЕРНУТЬ СЕРВЕР
}
$vk->sendOK();
$peer_id = $data->object->peer_id;
$id = $data->object->from_id;
$message = $data->object->text;
$messagei = strtolower($message);
require './SourceQuery/SourceQuery.class.php';
$Query = new SourceQuery();
$Query->Connect('127.0.0.1', 19132, 1, 1);
$Query->setRconPassword('EELPKETXT8EZ1EJF9L29');
if ($data->type === 'message_new') {
    if(substr($messagei, 0, 3) === '/id'){
	    $vk->sendMessage($peer_id, 'Ваш айди: '.$id.PHP_EOL.'Айди беседы: '. $peer_id);
        $vk->sendOK();
    }
    if($id === 177393322 or $id === 185200461 or $id === 548528158){
    	if (substr($messagei, 0, 5) === '/rcon' && strlen($messagei) !== 5) {
    		$msg = substr($message, 6);
    		if ($msg === 'stop' or $msg === 'dumpmemory' or $msg === 'save on' or $msg === 'save off' or $msg === 'save off' or $msg === 'op' or $msg === 'deop') {
    			$vk->sendMessage($peer_id, '[❌] Данная команда запрщена администрацией сервера!');
                $vk->sendOK();
    		}else{
    			$res = $Query->Rcon($msg);
    			if (strlen($res) > 2) {
                    if (strpos($messagei, "ban") or strpos($messagei, "kick") or strpos($messagei, "mute")) {
                        $vk->sendMessage($peer_id, '@id185200461');
                    }
    				$vk->sendMessage($peer_id, '[✔] Команда `'.$msg.'` успешно отправлена');
                    $vk->sendMessage($peer_id, 'Ответ сервера: '.PHP_EOL.$res);
                    $vk->sendOK();
    			}else{
                    if (strpos($messagei, "ban") or strpos($messagei, "kick") or strpos($messagei, "mute")) {
                        $vk->sendMessage($peer_id, '@id185200461');
                    }
    				$vk->sendMessage($peer_id, '[✔] Команда `'.$msg.'` успешно отправлена');
                    $vk->sendMessage($peer_id, 'Ответ сервера: пусто');
                    $vk->sendOK();
    			}
            }
        }else{
        	$vk->sendMessage($peer_id, '[❌] Вы не указали команду!'.PHP_EOL.'[✔] Правильное использование: /rcon команда, например /rcon say Hello.');
            $vk->sendOK();
	    }
    }else{
    	$vk->sendMessage($peer_id, '[❌] У Вас нет прав!'.PHP_EOL.'[❗] Купить доступ к консоли или привилегию можно на сайте: '.$buydom);
    	//$vk->sendMessage($peer_id, '[❌] У Вас нет прав!');
        $vk->sendOK();
    }
}