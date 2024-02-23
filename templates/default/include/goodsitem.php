<?php if (!empty($data)) :?>
<?php
    $mailClass = $parameters['mainClass'] ?? 'offers__tabs_card swiper-slide';
    $classPrefix = $parameters['prefix'] ?? 'offers';
?>
<div class="<?=$mailClass?>">
    <div class="<?=$classPrefix?>__tabs_image">
        <img src="<?=$this->img($data['img'])?>" alt="<?=$data['name']?>">
    </div>
    <div class="<?=$classPrefix?>__tabs_description">
        <div class="<?=$classPrefix?>__tabs_name">
            <span><?=$data['name']?></span>
            <?=$data['shot_content']?>
            <?php if (!empty($data['filters'])):?>
                <div class="card-main-info__table">
                    <?php foreach ($data['filters'] as $item):?>
                        <div class="card-main-info__table-row">
                            <div class="card-main-info__table-item">
                                <?=$item['name']?>
                            </div>
                            <div class="card-main-info__table-item">
                                <?=implode(',', array_column($item['values'], 'name'))?>
                            </div>
                        </div>
                    <?php endforeach;?>

                </div>
            <?php endif; ?>
        </div>
        <div class="<?=$classPrefix?>__tabs_price">
            Цена:
            <?php if (!empty($data['old_price'])):?>
                <span class="offers_old-price"><?=$data['old_price']?> руб.</span>
            <?php endif;?>
            <span class="<?=$classPrefix?>_new-price"><?=$data['price']?> руб.</span>
        </div>
    </div>
    <button class="<?=$classPrefix?>__btn" data-addToCart="<?=$data['id']?>">Купить</button>
    <?php if (!empty($parameters['icon'])): ?>
        <div class="icon-offer">
            <?=$parameters['icon']?>
        </div>
    <?php endif;?>
</div>
<?php endif; ?>