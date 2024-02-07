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

    public function inputData($links_counter=1, $redirect=true) {
        $links_counter = $this->clearNum($links_counter);
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
            }
            $this->model->edit('parsing_data', [
                'fields' => [
                    'temp_links' => json_encode($this->temp_links),
                    'all_links'  => json_encode($this->all_links)
                ]
            ]);
        }
        $this->model->edit('parsing_data', [
            'fields' => [
                'temp_links' => '',
                'all_links'  => ''
            ]
        ]);
        if ($this->all_links) {
            foreach ($this->all_links as $key => $link) {
                if (!$this->filter($link)) {
                    unset($this->all_links[$key]);
                }
            }
        }
        $this->createSitemap();
        if ($redirect) {
            !$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';
            $this->redirect();
        } else {
            $this->cancel(1, 'Sitemap is created! ' . count($this->all_links) . ' links', '', true );
        }

    }
    protected function parsing($urls, $index=0) {
        $urls = (array) $urls;
        if (!$urls) return;
        $curlMulty = curl_multi_init();
        $curl = [];
        foreach ($urls as $i => $url) {
            $curl[$i] = curl_init();
            curl_setopt($curl[$i],CURLOPT_URL, $url);
            curl_setopt($curl[$i],CURLOPT_URL, $url);
            curl_setopt($curl[$i],CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl[$i],CURLOPT_HEADER, true);
            curl_setopt($curl[$i],CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl[$i],CURLOPT_TIMEOUT, 120);
            curl_setopt($curl[$i],CURLOPT_ENCODING, 'gzip,deflate');

            curl_multi_add_handle($curlMulty, $curl[$i]);
        }
        do {
            $status = curl_multi_exec($curlMulty, $active);
            $info = curl_multi_info_read($curlMulty);
            if ($info !== false) {
                if ($info['result'] !== 0) {
                    $i = array_search($info['handle'], $curl);
                    $error = curl_errno($curl[$i]);
                    $message = curl_error($curl[$i]);
                    $header = curl_getinfo($curl[$i]);
                    if ($error != 0) {
                        $this->cancel(0,
                            "Error loading {$header['url']} http code {$header['http_code']} error: {$error} message {$message}"
                        );
                    }
                }
            }
            if ($status > 0) {
                $this->cancel(0, curl_multi_strerror($status));
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active );

        $result = [];
        foreach ($urls as $i => $url) {
            $result[$i] = curl_multi_getcontent($curl[$i]);
            curl_multi_remove_handle($curlMulty, $curl[$i]);
            curl_close($curl[$i]);
            //
            if (!preg_match('/Content-Type:\s*text\/html/ui', $result[$i])) {
                $this->cancel(0, "Incorrect content type $url");
                continue;
            }
            //
            if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $result[$i]) ) {
                $this->cancel(0, "Incorrent server code $url");
                continue;
            }
            $this->createLinks($result[$i]);
        }
        curl_multi_close($curlMulty);
    }
    protected function createLinks($content) {
        if ($content) {
            $links = [];
            $patern = '#<a\s*?[^>]*?href\s*?=(["\'])(.+?)\1[^>]*?>#ui';

            preg_match_all($patern, $content, $links);

            if ($links[2]) {
                foreach ($links[2] as $link) {
                    //
                    if ($link === '/' || $link === SITE_URL1 . '/') continue;
                    foreach ($this->fileArr as $ext) {
                        if ($ext) {
                            $ext = addslashes($ext);
                            $ext = str_replace('.', '\.', $ext);
                            $patern = "#{$ext}(\s*?$|\?[^\/]*$)#ui";
                            if (preg_match($patern, $link)) {
                                continue 2;
                            }
                        }
                    }
                    if (strpos($link, '/') === 0) {
                        $link = rtrim(SITE_URL1, '/') . $link;
                    }
                    $siteUrl = mb_str_replace('/', '\/', SITE_URL1);
                    $siteUrl = mb_str_replace('.', '\.', $siteUrl);
                    if (!in_array($link, $this->all_links) &&
                        !preg_match("/^($siteUrl)?\/?#[^\/]*$/ui", $link) &&
                        strpos($link, SITE_URL1) === 0) {
                        if ($this->filter($link)) {
                            $this->temp_links[] = $link;
                            $this->all_links[] = $link;
                        }
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
            $query = "CREATE TABLE parsing_data (all_links longtext, temp_links longtext)";
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

    protected function createSitemap()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('urlset');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $root->setAttribute('xmlns:xls', 'http://w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $dom->appendChild($root);
        $sxe = simplexml_import_dom($dom);

        if ($this->all_links) {
            $data = new \DateTime();
            $lastMod = $data->format('Y-m-d') . 'T' . $data->format('H:i:s+01:00');
            foreach ($this->all_links as $item) {
                $elem = trim(mb_substr($item, mb_strlen(SITE_URL1)), '/');
                $elem = explode('/', $elem);
                $count = '0.' . (count($elem) - 1);
                $priority = 1 - (float)$count;
                if ($priority == 1) $priority = '1.0';

                $urlMain = $sxe->addChild('url');
                $urlMain->addChild('loc', htmlspecialchars($item));
                $urlMain->addChild('lastmod', $lastMod);
                $urlMain->addChild('changefreq', 'weekly');
                $urlMain->addChild('priority', $priority);
            }
        }
        $dom->save($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/sitemap.xml');
    }
}