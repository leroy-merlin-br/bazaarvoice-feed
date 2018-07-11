<?php
namespace BazaarVoice;

use Exception;
use BazaarVoice\Elements\FeedElement;
use BazaarVoice\Elements\FeedElementInterface;
use phpseclib\Net\SFTP;
use SimpleXMLElement;

abstract class AbstractFeed implements FeedInterface
{
    /**
     * @var bool
     */
    protected $useStage = false;

    /**
     * @var string
     */
    protected $baseHost = 'sftp';

    public function useStage(): self
    {
        $this->useStage = true;
        return $this;
    }

    public function useProduction(): self
    {
        $this->useStage = false;
        return $this;
    }

    public function newFeed(string $name, bool $incremental = false): FeedElementInterface
    {
        $feedElement = new FeedElement($name, $incremental);
        $feedElement->setNamespace($this->getNamespace());

        return $feedElement;
    }

    public function printFeed(FeedElementInterface $feed): string
    {
        if ($xml = $this->generateFeedXML($feed)) {
            $xmlString = $xml->asXML();

            return str_replace(['<![CDATA[', ']]>'], '', $xmlString);
        }

        return '';
    }

    public function saveFeed(FeedElementInterface $feed, string $directory, string $filename)
    {
        if (!$feedXml = $this->printFeed($feed)) {
            return false;
        }

        if (!$file = gzencode($feedXml)) {
            return false;
        }

        $directory = rtrim($directory, '/\\');
        if (!is_dir($directory) || !is_writable($directory)) {
            return false;
        }

        $filePath = $directory.'/'.$filename.'.xml.gz';

        if (file_put_contents($filePath, $file)) {
            return $filePath;
        }

        return false;
    }

    /**
     * @return bool|string
     */
    public function sendFeed(string $filePath, string $sftpUsername, string $sftpPassword, string $sftpDirectory = 'import-inbox', int $sftpPort = 22)
    {
        $filename = basename($filePath);

        $sftp = new SFTP($this->getHost(), $sftpPort);

        $sftpDirectory = rtrim($sftpDirectory, '/');
        $sftpDirectory = ltrim($sftpDirectory, '/');

        try {
            if ($sftp->login($sftpUsername, $sftpPassword)) {
                $rootDirectory = rtrim('/', $sftp->realpath('.'));
                $fullDirectoryPath = $rootDirectory.'/'.$sftpDirectory;
                $sftp->chdir($fullDirectoryPath);
                $content = file_get_contents($filePath, false);

                if (false === $content) {
                    return false;
                }

                if ($sftp->put($filename, $content)) {
                    return true;
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return false;
    }

    public function getHost()
    {
        $sftpHost = $this->baseHost;

        if ($this->useStage) {
            $sftpHost .= '-stg';
        }

        return $sftpHost.'.bazaarvoice.com';
    }

    public function setBaseHost(string $baseHost)
    {
        $this->baseHost = $baseHost;
        return $this;
    }

    private function generateFeedXML(FeedElementInterface $feed)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Feed></Feed>');
        $feedXml = $feed->generateXMLArray();
        $this->buildXML($xml, $feedXml);
        return $xml;
    }

    private function buildXML(SimpleXMLElement &$xml, array $element = []): void
    {
        if (empty($element)) {
            return;
        }

        $elementXml = $xml;
        if (isset($element['#name']) && !empty($element['#name'])) {
            $elementXml = $elementXml->addChild($element['#name'], ($element['#value'] ?? null));
        }

        if (isset($element['#attributes']) && !empty($element['#attributes'])) {
            foreach ($element['#attributes'] as $attribute => $value) {
                $elementXml->addAttribute($attribute, $value);
            }
        }

        if (isset($element['#children']) && !empty($element['#children'])) {
            foreach ($element['#children'] as $child) {
                $this->buildXML($elementXml, $child);
            }
        }
    }
}
