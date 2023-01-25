<?php

namespace App\Supports;
class ImapSocket
{
    private $socket;

    /**
     * @throws \Exception
     */
    public function __construct($options, $mailbox = '')
    {
        $this->socket = $this->connect($options['server'], $options['port'], $options['tls']);
        $this->login($options['login'], $options['password']);

        if ($mailbox !== null) {
            $this->select_mailbox($mailbox);
        }
    }

    /**
     * @throws \Exception
     */
    private function connect(string $server, int $port, bool $tls)
    {
        if ($tls === true) {
            $server = "tls://$server";
        }

        $fd = fsockopen($server, $port, $errno);
        if (!$errno) {
            return $fd;
        }
        else {
            throw new \Exception('Unable to connect');
        }
    }

    /**
     * @throws \Exception
     */
    private function login(string $login, string $password): void
    {
        $result = $this->send("LOGIN $login $password");
        dd($result);

        $result = array_pop($result);
        if (!str_starts_with($result, '. OK ')) {
            throw new \Exception('Unable to login');
        }
    }

    public function __destruct()
    {
        fclose($this->socket);
    }

    /**
     * @throws \Exception
     */
    public function select_mailbox(string $mailbox): void
    {
        $result = $this->send("SELECT $mailbox");
        $result = array_pop($result);

        if (!str_starts_with($result, '. OK ')) {
            throw new \Exception("Unable to select mailbox '$mailbox'");
        }
    }

    public function get_flags(int $uid): array
    {
        $result = $this->send("FETCH $uid (FLAGS)");
        preg_match_all("|\\* \\d+ FETCH \\(FLAGS \\((.*)\\)\\)|", $result[0], $matches);
        if (isset($matches[1][0])) {
            return explode(' ', $matches[1][0]);
        }
        else {
            return [];
        }
    }

    /**
     * @throws \Exception
     */
    private function send(string $cmd, string $uid = '.'): array
    {
        $query = "$uid $cmd\r\n";
        $count = fwrite($this->socket, $query);
        if ($count === strlen($query)) {
            return $this->gets();
        }
        else {
            throw new \Exception("Unable to execute '$cmd' command");
        }
    }

    private function gets(): array
    {
        $result = [];

        while (str_starts_with($str = fgets($this->socket), '*')) {
            $result[] = substr($str, 0, -2);
        }
        $result[] = substr($str, 0, -2);

        return $result;
    }
}
