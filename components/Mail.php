<?php

namespace Component;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    public static function send(array $email, $title, $body, $file = null)
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->Host = App::$config['mail']['host']; // SMTP сервера вашей почты
            $mail->Username = App::$config['mail']['username']; // Логин на почте
            $mail->Password = App::$config['mail']['password']; // Пароль на почте
            $mail->SMTPSecure = App::$config['mail']['smtpsecure'];
            $mail->Port = App::$config['mail']['port'];
            $mail->CharSet = 'UTF-8';
            //От кого
            $mail->setFrom(App::$config['mail']['username'], App::$config['mail']['from']);
            //Кому
            foreach ($email as $item) {
                $mail->addAddress($item);
            }


            if (!empty($file['name'][0])) {
                if (count($file['tmp_name']) == 1) {
                    $arr = explode('.', $file["name"]);
                    $extension = ltrim($arr[1]);
                    $src = time() . '.' . $extension;
                    $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name']));

                    $filename = $file['name'];
                    if (move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/$src")) {
                        $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . "/upload/$src", $src);
                        $rfile[] = "Файл прикреплён";
                    } else {
                        $rfile[] = "Не удалось прикрепить файл $filename";
                    }

                } else {
                    for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {

                        $arr = explode('.', $file["name"][$ct]);
                        $extension = ltrim($arr[1]);
                        $src = time() . '.' . $extension;
                        $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));

                        $filename = $file['name'][$ct];
                        if (move_uploaded_file($file["tmp_name"][$ct], $_SERVER['DOCUMENT_ROOT'] . "/upload/$src")) {

                            $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . "/upload/$src", $src);
                            $rfile[] = "Файл прикреплён";
                        } else {
                            $rfile[] = "Не удалось прикрепить файл $filename";
                        }

                    }
                }

            }

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $title;//Тема письма
            $mail->Body = $body;

            if ($mail->send()) {
                $result = "success";
            } else {
                $result = "error";
            }
        } catch (Exception $e) {
            $result = "error";
            $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
        }
        return $result;
    }
}
