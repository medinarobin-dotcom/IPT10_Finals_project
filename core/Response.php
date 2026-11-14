<?php

class Response
{
    private $content;
    private $status;

    public function __construct($content = '', $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        echo $this->content;
    }

    public function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }
}