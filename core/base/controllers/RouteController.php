<?php

namespace core\base\controllers;
use core\base\settings\Settings;
use core\base\exceptions\RouteException;

class RouteController extends BaseController
{
    use Singleton;
    protected $routes;

    /**
     * @throws RouteException
     */
    private function __construct(){
        $adress_str =$_SERVER['REQUEST_URI'];
		//
        $path =substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
        if($path===PATH){
	        $this->routes=Settings::get('routes');
            if(!$this->routes) {throw new RouteException('Отсуствуют маршруты в базовых настройках', 1); }
            $url = preg_split('/(\/)|(\?.*)/', $adress_str, 0, PREG_SPLIT_NO_EMPTY);
            if(!empty($url[0]) && $url[0]===$this->routes['admin']['alias']){
                array_shift($url);
                if(!empty($url[0]) && is_dir($_SERVER['DOCUMENT_ROOT'].PATH.$this->routes['plugins']['path'].$url[0])){
                    $plugin= array_shift($url);
                    $pluginSettings=$this->routes['settings']['path'].ucfirst($plugin.'Settings');
                    if(file_exists($_SERVER['DOCUMENT_ROOT'].PATH.$pluginSettings.'.php')){
                        $pluginSettings=str_replace('/', '\\', $pluginSettings);
                        $this->routes=$pluginSettings::get('routes');
                    }
                    $dir=$this->routes['plugins']['dir'] ? '/'.$this->routes['plugins']['dir'].'/' : '/';
                    $dir=str_replace('\\', '/', $dir);
                    $this->controller=$this->routes['plugins']['path'].$plugin.$dir;
                    $hrUrl=$this->routes['plugins']['hrURL'];
                    $route='plugins';
                }else{
                    $this->controller=$this->routes['admin']['path'];
                    $hrUrl=$this->routes['admin']['hrURL'];
                    $route='admin';
                }
            }else{
                if (!$this->isPost()) {
                    $pattern = '';
                    $replacement = '';
                    if (END_SLASH) {
                        if (!preg_match('/\/(\?|$)/', $adress_str )) {
                            $pattern = '/(^.*?)(\?.*)?$/';
                            $replacement = '$1/';
                        }
                    }
                    else {
                        if (preg_match('/\/(\?|$)/', $adress_str )) {
                            $pattern = '/(^.*?)\/(\?.*)?$/';
                            $replacement = '$1';
                        }
                    }
                    if ($pattern) {
                        $adress_str = preg_replace($pattern, $replacement, $adress_str);
                        if (!empty($_SERVER['QUERY_STRING'])) {
                            $adress_str .= '?' . $_SERVER['QUERY_STRING'];
                        }
                        $this->redirect($adress_str, 301);
                    }
                }
                $hrUrl= $this->routes['user']['hrURL'];
                $this->controller= $this->routes['user']['path'];
                $route="user";
            }
            $this->creteRoute($route, $url);
            if(!empty($url[1])){
                $count=count($url);
                $key="";
                if(!$hrUrl){
                    $i=1;
                }else{
                    $this->parameters['alias']=$url[1];
                    $i=2;
                }
                for(;$i<$count;$i++){
                    if(!$key){
                        $key=$url[$i];
                        $this->parameters[$key]='';
                    }else{
                        $this->parameters[$key]=$url[$i];
                        $key="";
                    }
                }
            }

         }else{
                throw new RouteException("Ни корейская директория сайта", 1);
        }
    }
    public function creteRoute($var, $arr){
        $route=[];
        if(!empty($arr[0])){
            if(isset($this->routes[$var]['routes'][$arr[0]])){
                $route=explode('/', $this->routes[$var]['routes'][$arr[0]]);
                $this->controller.=ucfirst($route[0].'Controller');
            }else{
                $this->controller.=ucfirst($arr[0].'Controller');
            }
        }else{
            $this->controller.=$this->routes['default']['controller'];
        }
        $this->inputMethod = $route[1] ?? $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ?? $this->routes['default']['outputMethod'];
        return;
    }
}