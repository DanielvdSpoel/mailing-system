<?php

namespace App\Supports;

class EmailSupport
{

    static function handlePart($part, $partNumber, $connection, $imapEmailNumber, $emailModel)
    {
        if ($part->subtype == 'PLAIN') {
            $emailModel->text_body = EmailSupport::decodeContent(imap_fetchbody($connection, $imapEmailNumber, $partNumber ?? 1), $part->encoding);
        } else if ($part->subtype == 'HTML') {
            $emailModel->html_body = EmailSupport::decodeContent(imap_fetchbody($connection, $imapEmailNumber, $partNumber ?? 1), $part->encoding);
        } else if (in_array($part->subtype, ['ALTERNATIVE', 'MIXED', 'RELATED'])) {
            for ($i = 1 ; $i < count($part->parts) + 1; $i++)
            {
                $subPart = $part->parts[$i - 1];
                $newPartNumber = $partNumber !== null ? $partNumber . '.' . $i : $i;
                EmailSupport::handlePart($subPart, $newPartNumber, $connection, $imapEmailNumber, $emailModel);
            }
        }
    }

    static function decodeContent($content, $encoding)
    {
        //todo implement more decoders https://www.php.net/manual/en/function.imap-fetchstructure.php
        switch ($encoding) {
            case 0:
            case 1:
                return $content;
            case 3:
                //base64 encryption
                return imap_base64($content);
            case 4:
                //quoted printable
                return imap_qprint($content);

        }
    }
}
