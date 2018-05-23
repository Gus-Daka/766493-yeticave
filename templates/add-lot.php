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

  <?php $classname = (count($errors)) ? "form--invalid" : ""; ?>

  <form class="form form--add-lot container <?=$classname; ?>" action="add.php" method="POST" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">

      <?php $classname = isset($errors['lot_name']) ? "form__item--invalid" : "";
      $value = isset($lot['lot_name']) ? $lot['lot_name'] : ""; ?>

      <div class="form__item <?=$classname; ?>">
        <label for="lot_name">Наименование</label>
        <input id="lot_name" type="text" name="lot[lot_name]" placeholder="Введите название лота" value="<?=$value;?>">
        <span class="form__error"><?=$dict['lot_name']; ?>: <?=$errors['lot_name']; ?></span>
      </div>

      <?php $classname = isset($errors['cat_name']) ? "form__item--invalid" : "";?>

      <div class="form__item <?=$classname; ?>">
        <label for="cat_name">Категория</label>
        <select id="cat_name" name="lot[cat_name]">
          <option value="">Выберите категорию</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?=$cat['id']; ?>"
              <?php if($cat['id'] == $lot['category_id']) {
                print('selected'); } ?> >
            <?=$cat['lot_name']; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <span class="form__error"><?=$dict['cat_name']; ?>: <?=$errors['cat_name']; ?></span>
      </div>
    </div>

    <?php $classname = isset($errors['description']) ? "form__item--invalid" : "";
    $value = isset($lot['description']) ? $lot['description'] : ""; ?>

    <div class="form__item form__item--wide <?=$classname; ?>">
      <label for="message">Описание</label>
      <textarea id="message" name="lot[description]" placeholder="Напишите описание лота"><?=$value;?></textarea>
      <span class="form__error"><?=$dict['description']; ?>: <?=$errors['description']; ?></span>
    </div>

    <?php $classname = isset($errors['file']) ? "" : "form__item--uploaded"; ?>

    <div class="form__item form__item--file <?=$classname; ?>"> <!-- form__item--uploaded -->
      <label>Изображение</label>

      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img src="<?=$lot['lot_image']; ?>" width="113" height="113" alt="">
        </div>

      </div>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="lot_image" id="photo2">
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>

    </div>
    <div class="form__container-three">

      <?php $classname = isset($errors['start_price']) ? "form__item--invalid" : "";
      $value = isset($lot['start_price']) ? $lot['start_price'] : ""; ?>

      <div class="form__item form__item--small <?=$classname; ?>">
        <label for="lot-rate">Начальная цена</label>
        <input id="lot-rate" type="number" name="lot[start_price]'" placeholder="0" value="<?=$value;?>">
        <span class="form__error"><?=$dict['start_price']; ?>: <?=$errors['start_price']; ?></span>
      </div>

      <?php $classname = isset($errors['rate_price']) ? "form__item--invalid" : "";
      $value = isset($lot['rate_price']) ? $lot['rate_price'] : ""; ?>

      <div class="form__item form__item--small <?=$classname; ?>">
        <label for="lot-step">Шаг ставки</label>
        <input id="lot-step" type="number" name="lot[rate_price]" placeholder="0" value="<?=$value;?>">
        <span class="form__error"><?=$dict['rate_price']; ?>: <?=$errors['rate_price']; ?></span>
      </div>

      <?php $classname = isset($errors['finish_lot']) ? "form__item--invalid" : "";
      $value = isset($lot['finish_lot']) ? $lot['finish_lot'] : ""; ?>

      <div class="form__item <?=$classname; ?>">
        <label for="lot-date">Дата окончания торгов</label>
        <input class="form__input-date" id="lot-date" type="date" name="lot[finish_lot]" value="<?=$value;?>">
        <span class="form__error"><?=$dict['finish_lot']; ?>: <?=$errors['finish_lot']; ?></span>
      </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
  </form>
</main>