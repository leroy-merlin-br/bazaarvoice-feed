<?php
namespace BazaarVoice\Product;

use BazaarVoice\Elements\BrandElementInterface;
use BazaarVoice\Elements\CategoryElementInterface;
use BazaarVoice\Elements\ProductElementInterface;
use BazaarVoice\FeedInterface;
use Exception;
use BazaarVoice\Elements\BrandElement;
use BazaarVoice\Elements\CategoryElement;
use BazaarVoice\Elements\FeedElement;
use BazaarVoice\Elements\FeedElementInterface;
use BazaarVoice\Elements\ProductElement;
use phpseclib\Net\SFTP;
use SimpleXMLElement;

abstract class AbstractFeed implements FeedInterface
{
    /**
     * @var bool
     */
    protected $useStage = false;

    protected $baseHost = 'sftp';

    public function __construct()
    {
        return $this;
    }

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
        return new FeedElement($name, $incremental);
    }

    public function printFeed(FeedElementInterface $feed): string
    {
        $xmlString = false;

        if ($xml = $this->generateFeedXML($feed)) {
            $xmlString = $xml->asXML();
            $xmlString = str_replace(['<![CDATA[', ']]>'], '', $xmlString);
        }

        return $xmlString;
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

    public function sendFeed(string $filePath, string $sftpUsername, string $sftpPassword, string $sftpDirectory = 'import-inbox', string $sftpPort = '22'): bool
    {
        $fileSent = false;

        $filename = basename($filePath);

        $sftp = new SFTP($this->getHost(), $sftpPort);

        $sftpDirectory = rtrim($sftpDirectory, '/');
        $sftpDirectory = ltrim($sftpDirectory, '/');

        try {
            if ($sftp->login($sftpUsername, $sftpPassword)) {
                $rootDirectory = $sftp->realpath('.');
                $fullDirectoryPath = $rootDirectory.(('/' == substr($rootDirectory, -1)) ? '' : '/').$sftpDirectory;
                $sftp->chdir($fullDirectoryPath);
                if ($sftp->put($filename, file_get_contents($filePath, false))) {
                    $fileSent = true;
                }
            }
        } catch (Exception $e) {
            $fileSent = $e->getMessage();
        }

        return $fileSent;
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
