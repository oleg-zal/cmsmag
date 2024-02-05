<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;

class CreatesitemapController extends BaseAdmin
{
    use BaseMethods;

    protected $linkArr = [];
    protected $parsingLogFile = 'parsing_log.txt';
    protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3' ];
    protected $filterArr = [
        'url' => ['order'],
        'get' => ['masha']
    ];

    protected function inputData() {
        if (!function_exists('curl_init')) {
            $this->writeLog('Отсутствует библитотека CURL');
            $_SESSION['res']['answer'] = '<div class="error">Library CURL is Absent</div>';
            $this->redirect();
        }
        set_time_limit(0);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile)) {
            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile);
        }
        $this->parsing(SITE_URL1);
        $this->createSitemap();
        !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';
        $this->redirect();
    }
    protected function parsing($url, $index=0) {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_HEADER, true);
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl,CURLOPT_TIMEOUT, 120);
        curl_setopt($curl,CURLOPT_RANGE, "0 - 4194304");

        $out = curl_exec($curl);
        curl_close($url);
        if (!preg_match('/Content-Type:\s+text\/html/ui', $out)) {
            unset($this->linkArr[$index]);
            $this->linkArr = array_values($this->linkArr);
            return;
        }
        if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $out) ) {
            $this->writeLog('Не корректная ссылка при парсинге - ' . $url, $this->parsingLogFile);
            unset($this->linkArr[$index]);
            $this->linkArr = array_values($this->linkArr);
            $_SESSION['res']['answer'] = '<div class="error">Incorrect link in parsing  - ' . $url .'</div>';
        }
        $links = [];
        $patern = '#<a\s*?[^>]*?href\s*?=(["\'])(.+?)\1[^>]*?>#ui';

        preg_match_all($patern, $out, $links);

        if ($links[2]) {
            foreach ($links[2] as $link) {
                //
                if ($link === '/' || $link === SITE_URL1 . '/') continue;
                foreach ($this->fileArr as $ext) {
                    if ($ext) {
                        $ext = addslashes($ext);
                        $ext = str_replace('.', '\.', $ext);
                        $patern = "#{$ext}\s*?$|\?[^\/]#ui";
                        if (preg_match($patern, $link)) {
                            continue 2;
                        }
                    }
                }
                if (strpos($link, '/') === 0) {
                    $link = rtrim(SITE_URL1, '/') . $link;
                }
                if (!in_array($link, $this->linkArr) && $link !== '#' && strpos($link, SITE_URL1) === 0) {
                    if ($this->filter($link)) {
                        $this->linkArr[] = $link;
                        $this->parsing($link, count($this->linkArr) - 1);
                    }
                }
            }
        }
    }
    protected function filter($link) {
        //
        if ($this->filterArr) {
            foreach ($this->filterArr as $type => $values) {
                if ($values) {
                    foreach ($values as $item) {
                        $item = str_replace('/', '\/', addslashes($item));
                        if ($type === 'url') {
                            if (preg_match('#^[^\?]*' . $item . '#ui', $link)) {
                                return false;
                            }
                        }
                        if ($type === 'get') {
                             $patern = '#(\?|&amp;|=|&)'. $item .'(=|&amp;|&|$)#ui';
                             if (preg_match($patern, $link)) {
                                return false;
                             }
                        }
                    }
                }
            }
        }
        return true;
    }
    protected function createSitemap() {

    }
}