<?php
namespace app\Http;

class Response
{
    protected $content = "";

    public function send()
    {
        if (headers_sent()) {
            return $this;
        }

        header('HTTP/1.1 200 OK');

        echo $this->content;

        return $this;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }
}