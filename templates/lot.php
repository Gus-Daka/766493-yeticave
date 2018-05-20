<main>
  <nav class="nav">
    <ul class="nav__list container">

      <?php foreach ($categories as $cat): ?>
        <li class="nav__item">
          <a href="all-lots.html"><?=$cat; ?></a>
        </li>
      <?php endforeach; ?>
      
    </ul>
  </nav>
  <section class="lot-item container">

  <?php foreach ($lot_info as $lot): ?>
      <h2><?=htmlspecialchars($lot['lot_name']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$lot['lot_image']; ?>" width="730" height="548" alt="">
          </div>
          <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lots['category_id']); ?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
        </div>
      <?php endforeach; ?>

      <div class="lot-item__right">
        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=timeToFinish(); ?>
          </div>

          <?php foreach ($price_info as $lot): ?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=htmlspecialchars(max($lot['start_price'], $lot['max_bet'])); ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=htmlspecialchars(max($lot['start_price'], $lot['max_bet']) + $lot['step_price']); ?></span>
              </div>
            </div>
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
              <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost"
                placeholder= "<?=htmlspecialchars(max($lot['start_price'], $lot['max_bet']) + $lot['step_price']); ?>">
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          <?php endforeach; ?>

        </div>
        <div class="history">
          <h3>История ставок (<span><?=count($rate_info) ?></span>)</h3>
          <table class="history__list">
            <?php foreach ($rate_info as $lot): ?>
              <tr class="history__item">
                <td class="history__name"><?=htmlspecialchars($lot['user_name']); ?></td>
                <td class="history__price"><?=htmlspecialchars($lot['rate_price']); ?></td>
                <td class="history__time"><?=htmlspecialchars($lot['rate_date']); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>