<?php

namespace core\base\settings;
use core\base\controllers\Singleton;
class Settings
{
    use Singleton;
    private $routes=[
        'admin'=>[
            'alias'=>'admin',
            'path'=>'core/admin/controllers/',
            'hrURL'=>false,
            'routes'=>[

            ],
        ],
        'settings'=>[
            'path'=>'core/base/settings/'
        ],
        'plugins'=>[
            'path'=>'core/plugins/',
            'hrURL'=>false,
            'dir'=>false,
            'routes'=>[
            ],
        ],
        'user'=>[
            'path'=>'core/user/controllers/',
            'hrURL'=>true,
            'routes'=>[

            ],
        ],
        'default'=>[
            'controller'=>'IndexController',
            'inputMethod'=>'inputData',
            'outputMethod'=>'outputData'
        ]
    ];
    private $formTemplate=PATH.'core/admin/views/include/form_templates/';
    private $templateArr=[
        'text'=>['name', 'phone', 'email', 'alias', 'external_alias', 'sub_title', 'number_of_years', 'price', 'discount'],
        'textarea'=>['keyword','content', 'address', 'description', 'shot_content'],
        'radio' => ['visible', 'show_top_menu', 'hit', 'sale', 'new', 'hot'],
        'checkboxlist' => ['filters'],
        'select' => ['menu_position', 'parent_id'],
        'img' => ['img', 'main_img', 'img_years', 'promo_img'],
        'gallery_img' => ['gallery_img', 'new_gallery_img']
        /*'text'=>['name', 'phone', 'email','alias','external_alias'],*/
        /*'textarea'=>['content', 'keywords'],
        'radio'=>['visible','top_menu'],
        'checkboxlist'=>['filters', 'goods'],
        'select'=>['menu_position', 'parent_id'],
        'img'=>['img'],
        'gallery_img'=>['gallery_img']*/
    ];
    private $fileTemplates=[
        'img','gallery_img'
    ];
    private $projectTables=[
        'category' => ['name' => 'Каталог'],
        'goods'=>['name'=>'Товары', 'img' => 'pages.png'],
        'filters'=>['name'=>'Фильтры'],
        'articles' => ['name' => 'Статьи'],
        'sales' => ['name' => 'Акции'],
        'news' => ['name' => 'Новости'],
        'information' => ['name'=>'Информация'],
        'advantages' => ['name'=>'Преимущества'],
        'socials' => ['name' => 'Социальные сети'],
        'settings'=>['name'=>'Настройки системы'],
        /*'category'=>[ 'name'=>'Категории', 'img'=>''],
        'products'=>['name'=>'Тавари', 'img'=>''],
        'pages' => ['name' => 'Страницы'],
        'socials'=>[]*/
    ];
	private $translate=[
		'name' => ['Название', 'Не более 255 символов'],
        'keywords'=>['Ключевые слова', 'Не более 400 символов'],
        'content'=>['Описание', 'Не более 400 символов'],
        'description'=>['SEO Описание', 'Не более 400 символов'],
        'phone' => ['Телефон'],
        'email' => ['E-mail'],
        'address' => ['Адрес'],
        'alias' => ['Ссылка ЧПУ'],
        'show_top_menu' => ['Показывать в верхнем меню'],
        'external_alias' => ['Внешняя ссылка'],
        'sub_title' => ['Подзаголовок'],
        'shot_content' => ['Краткое описание'],
        'img_years' => ['Изображение количества лет на рынке'],
        'number_of_years' => ['Количество лет на рынке'],
        'hit'   =>  ['хит продаж'],
        'sale'  =>  ['Акция'],
        'new'   =>  ['Новинка'],
        'hot'   =>  ['Горячее предложение'],
        'discount' => ['Скидка'],
        'price' => ['Цена'],
        'promo_img' => ['Изображение для главной страницы'],
        'img'=>['Картинка', 'jpg, png'],
        'gallery_img'=>['Галерея'],
        'visible'=>[],
        'menu_position'=>['Номер в меню', ''],
        'parent_id'=>['Родитель', ''],
        'top_menu'=>[],
	];
    private $manyToMany=[
        'goods_filters'=>['goods','filters'],// 'type'=>'root'||'child'||'all'

    ];
	private $blockNeedle=[
		'vg-rows'=>[],
		'vg-img'=>['img', 'main_img', 'img_years', 'number_of_years', 'promo_img'],
		'vg-content'=>['content']
	];
	private $rootItems=[
		'name'=>'Корневая',
        'tables' => ['goods', 'filters', 'articles', 'pages', 'category']
		//'tables'=>['category', 'articles', 'products', 'page', 'goods', 'filters','socials']
	];
    private $defaultTable='articles';
    private $expansion='core/admin/expansion/';
	private $radio=[
		'visible'=>['Нет','Да', 'default'=>'Да'],
        'show_top_menu'=>['Нет','Да', 'default'=>'Да'],
        'hit'   =>  ['Нет','Да', 'default'=>'Нет'],
        'sale'  =>  ['Нет','Да', 'default'=>'Нет'],
        'new'   =>  ['Нет','Да', 'default'=>'Нет'],
        'hot'   =>  ['Нет','Да', 'default'=>'Нет'],
	];
    private $validation=[
        'name'=>['empty'=>true, 'trim'=>true],
        'price'=>['int'=>true],
        'discount'=>['int'=>true],
        'login' => ['empty'=>true, 'trim'=>true],
        'email'=>['empty'=>true, 'trim'=>true],
        'password'=>['crypt'=>true, 'empty'=>true, 'trim'=>true, 'len'=>8],
        'keywords'=>['count' => 70, 'trim'=>true],
        'description'=>['count'=>400, 'trim'=>true],
    ];
    private $messages='core/base/messages/';
    static public function get($property){
        return self::instance()->$property;
    }
    public function clueProperties($class): array
    {
        $bestProperties =[];
        foreach($this as $name=>$value){
            $property = $class::get($name);
            if(is_array($property) && is_array($value)){
                $bestProperties[$name]=$this->arrayMergenRecusive($this->$name, $property);
                continue;
            }
            if(!$property){
                $bestProperties[$name]=$this->$name;
            }
        }
        return $bestProperties;
    }
    public function arrayMergenRecusive(){
        $arrays=func_get_args();
        $best=array_shift($arrays);
        foreach($arrays as $array){
            foreach($array as $key=>$value){
                if(is_array($value) && is_array($best[$key])){
                    $best[$key]=$this->arrayMergenRecusive($best[$key], $value);
                }elseif(is_int($key)) {
                        if (!in_array($value, $best)) {
                            $best[] = $value;
                            continue;
                        }
                }else{
                    $best[$key] = $value;
                }
            }
            return $best;
        }
    }
}