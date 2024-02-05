<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;

class CreatesitemapController extends BaseAdmin
{
    use BaseMethods;

    protected $all_links = [];
    protected $temp_links = [];

    protected $maxLinks = 5000;

    protected $parsingLogFile = 'parsing_log.txt';
    protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3' ];
    protected $filterArr = [
        'url' => [],
        'get' => []
    ];

    protected function inputData($links_counter=1) {
        if (!function_exists('curl_init')) {
            $this->cancel(0, 'Library CURL is Absent', '', true);
        }
        if (!$this->userId) $this->execBase();
        if (!$this->checkParsingTable()) {
            $this->cancel(0, 'You have problems with database table parsing_data', '', true);
        }
        set_time_limit(0);
        //
        $reserve = $this->model->get('parsing_data')[0];
        foreach ($reserve as $name => $item) {
            if ($item) {
                $this->$name = json_decode($item);
            } else {
                $this->$name = [SITE_URL1];
            }
        }
        $this->maxLinks = (int) $links_counter > 1 ? ceil($this->maxLinks / $links_counter) : $this->maxLinks;
        while ($this->temp_links) {
            $temp_links_counter = count($this->temp_links);
            $links = $this->temp_links;
            $this->temp_links = [];
            if ($temp_links_counter > $this->maxLinks) {
                $links = array_chunk($links, ceil($temp_links_counter / $this->maxLinks));
                $count_chunks = count($links);
                for ($i=0; $i<$count_chunks; $i++) {
                    $this->parsing($links[$i]);
                    unset($links[$i]);
                    if ($links) {
                        $this->model->edit('parsing_data', [
                            'fields' => [
                                'temp_links' => json_encode(array_merge(... $links)),
                                'all_links'  => json_encode($this->all_links)
                            ]
                        ]);
                    }
                }
            } else {
                $this->parsing($links);
                $this->model->edit('parsing_data', [
                    'fields' => [
                        'temp_links' => json_encode($this->temp_links),
                        'all_links'  => json_encode($this->all_links)
                    ]
                ]);
            }
        }

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
            unset($this->all_links[$index]);
            $this->all_links = array_values($this->all_links);
            return;
        }
        if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $out) ) {
            $this->writeLog('Не корректная ссылка при парсинге - ' . $url, $this->parsingLogFile);
            unset($this->all_links[$index]);
            $this->all_links = array_values($this->all_links);
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
                if (!in_array($link, $this->all_links) && $link !== '#' && strpos($link, SITE_URL1) === 0) {
                    if ($this->filter($link)) {
                        $this->all_links[] = $link;
                        $this->parsing($link, count($this->all_links) - 1);
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
    protected function checkParsingTable() {
        $tables = $this->model->showTables();
        if (!in_array('parsing_data', $tables)) {
            $query = "CREATE TABLE parsing_data (all_links text, temp_links text)";
            if (!$this->model->query($query, 'c') ||
                !$this->model->add('parsing_data', ['fields' => ['all_links' => '', 'temp_links' => '']])) {
                return false;
            }
        }
        return true;
    }
    protected function cancel($success = 0, $message = '', $log_message = '', $exit = false) {
        $exitArr = [];
        $exitArr['success'] = $success;
        $exitArr['message'] = $message ? $message : 'ERROR PARSING';
        $log_message = $log_message ? $log_message : $exitArr['message'];

        $class = 'success';
        if (!$exitArr['success']) {
            $class = 'error';
            $this->writeLog($log_message, $this->parsingLogFile);
        }
        if ($exit) {
            $exitArr['message'] = "<div class=\"$class\">{$exitArr['message']}</div>";
            exit(json_encode($exitArr));
        }
    }

    protected function createSitemap() {

    }
}