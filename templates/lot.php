<section class="lot-item container">

  <?php foreach ($lot_info as $lot): ?>
      <h2><?=htmlspecialchars($lot['lot_name']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=htmlspecialchars($lot['lot_image']); ?>" width="730" height="548" alt="">
          </div>
          <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['cat_name']); ?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
        </div>
      <?php endforeach; ?>

      <div class="lot-item__right">

        <?php if($rate_div_visible): ?>

        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=timeToFinish(htmlspecialchars($lot_info['0']['finish_lot'])); ?>
          </div>

          <?php foreach ($price_info as $key => $price): ?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=htmlspecialchars(max($price['start_price'], $price['max_bet'])); ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=htmlspecialchars(max($price['start_price'], $price['max_bet']) + $price['step_price']); ?></span>
              </div>
            </div>

            <?php $classname = (count($errors)) ? "form--invalid" : ""; ?>

            <form class="lot-item__form <?=$classname; ?>" action="" method="POST">
            <?php $classname = isset($errors['rate_price']) ? "form__item--invalid" : "";
            $value = isset($rate['rate_price']) ? htmlspecialchars($rate['rate_price']) : ""; ?>
              
              <p class="lot-item__form-item <?=$classname; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="rate['rate_price']"
                placeholder= "<?=htmlspecialchars(max($lot['start_price'], $lot['max_rate']) + $lot['step_price']); ?>" value="<?=$value; ?>">
                <span class="form__error"><?=$dict['rate_price']; ?>: <?=$errors['rate_price']; ?>
                </span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

        </div>
        <div class="history">
          <h3>История ставок (<span><?=count($rate_info) ?></span>)</h3>
          <table class="history__list">
            <?php foreach ($rate_info as $key => $val): ?>
              <tr class="history__item">
                <td class="history__name"><?=htmlspecialchars($val['user_name']); ?></td>
                <td class="history__price"><?=htmlspecialchars($val['rate_price']); ?></td>
                <td class="history__time"><?=htmlspecialchars($val['rate_date']); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </section>