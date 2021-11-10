<?php

namespace app\Services;

use vendor\PHPMailer\PHPMailer;
use vendor\PHPMailer\Exception;
use vendor\PHPMailer\SMTP;

class Mail
{
    protected PHPMailer $mail;
    protected string $email;
    protected string $subject;
    protected string $body;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    public function template(string $title, string $btn_label, string $btn_link)
    {
        $content = file_get_contents(ROOT_DIR.'/vendor/PHPMailer/templates.html'); // lấy nội dung template email
        // thay thế ký tự
        $content = str_replace('%app_name%', APP_NAME, $content);
        $content = str_replace('%app_url%',APP_URL, $content);
        $content = str_replace('%title%', $title, $content);
        $content = str_replace('%button_label%', $btn_label, $content);
        $content = str_replace('%button_link%', $btn_link, $content);
        return $content;
    }

    public function email(string $email) : Mail
    {
        $this->email = $email;
        return $this;
    }

    public function subject(string $subject) : Mail
    {
        $this->subject = $subject;
        return $this;
    }

    public function body(string $title, string $btn_label, string $btn_link) : Mail
    {
        $this->body = $this->template($title, $btn_label, $btn_link);
        return $this;
    }

    public function send() : void
    {
        try {
            $this->mail->CharSet = "UTF-8";
            $this->mail->isSMTP();
            $this->mail->Host = MAIL_HOST;
            $this->mail->SMTPAuth = MAIL_SMTP_AUTH;
            $this->mail->SMTPSecure = MAIL_SMTP_SECURE;
            $this->mail->Port = MAIL_PORT;
            $this->mail->Username = MAIL_USERNAME;
            $this->mail->Password = MAIL_PASSWORD;
            $this->mail->setFrom(MAIL_USERNAME);
            $this->mail->Subject = $this->subject;
            $this->mail->isHTML(true);
            $this->mail->Body = $this->body;
            $this->mail->addAddress($this->email);
            $this->mail->send();
        } catch (Exception $e) {
            echo "Gui mail that bai.\nMailer error: ".$e->getMessage();
        }
    }
}