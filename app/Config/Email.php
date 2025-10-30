<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail;
    public string $fromName;
    public string $recipients;

    public string $protocol;
    public string $mailPath;
    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass;
    public int $SMTPPort;
    public int $SMTPTimeout;
    public bool $SMTPKeepAlive;
    public string $SMTPCrypto;
    public bool $wordWrap;
    public int $wrapChars;
    public string $mailType;
    public string $charset;
    public bool $validate;
    public int $priority;
    public string $CRLF;
    public string $newline;
    public bool $BCCBatchMode;
    public int $BCCBatchSize;
    public bool $DSN;

    public function __construct()
    {
        parent::__construct();

        // Configuración cargada desde .env
        $this->protocol   = env('EMAIL_PROTOCOL', 'smtp');
        $this->SMTPHost   = env('SMTP_HOST', 'mail.pelucasperu.com');
        $this->SMTPUser   = env('SMTP_USER', 'milislens@pelucasperu.com');
        $this->SMTPPass   = env('SMTP_PASS', '');
        $this->SMTPPort   = (int) env('SMTP_PORT', 587);
        $this->SMTPCrypto = env('SMTP_CRYPTO', 'tls');

        $this->fromEmail  = env('MAIL_FROM_EMAIL', 'milislens@pelucasperu.com');
        $this->fromName   = env('MAIL_FROM_NAME', 'milislens');

        $this->mailType   = env('MAIL_TYPE', 'html');
        $this->charset    = 'utf-8';
        $this->newline    = "\r\n";
        $this->CRLF       = "\r\n";
        $this->wordWrap   = true;
        $this->validate   = true;
        $this->priority   = 3;
    }
}
