<div class="vg-element vg-full vg-box-shadow">
    <div class="vg-element vg-full vg-box-shadow">
        <div class="vg-wrap vg-element vg-half vg-left vg-no-space-top">
            <div class="vg-element vg-full vg-left">
                <span class="vg-header"><?=$this->translate[$row][0] ? : $row?></span>
            </div>
            <div class="vg-element vg-full vg-left">
                <span class="vg-text vg-firm-color5"></span>
                <span class="vg-text vg-firm-color5 vg_subheader"><?=$this->translate[$row][1]?></span>
            </div>

            <div class="vg-wrap vg-element vg-fourth">
                <?php foreach($this->foreignData[$row] as $key => $item): ?>
                    <?php if (is_int($key)) : ?>
                    <?php
                        $cheked = null;
                        if (isset($this->data[$row]) && $this->data[$row] == $key) {
                            $cheked = 'cheked';
                        } elseif (!isset($this->data[$row]) && $this->foreignData[$row]['default'] == $item) {
                            $cheked = 'cheked';
                        }
                    ?>
                    <label class="vg-element vg-full vg-center vg-left vg-space-between">
                        <span class="vg-text vg-half"><?=$item ?></span>
                        <input type="radio"
                               name="<?=$row?>"
                               class="vg-input vg-half"
                               <?=$cheked ? 'checked=' . $cheked : ''?>
                               value="<?=$key?>">
                    </label>
                    <?php endif;?>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>