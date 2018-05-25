  <?php $classname = (count($errors)) ? "form--invalid" : ""; ?>

  <form class="form container <?=$classname; ?>" action="register.php" method="POST" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>

    <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
    $value = isset($signup['email']) ? $signup['email'] : ""; ?>

    <div class="form__item <?=$classname; ?>">
      <label for="email">E-mail*</label>
      <input id="email" type="text" name="signup[email]" placeholder="Введите e-mail" value="<?=$value;?>">
      <span class="form__error"><?=$dict['email']; ?> : <?=$errors['email']; ?></span>
    </div>

    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>

    <div class="form__item <?=$classname; ?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="signup[password]" placeholder="Введите пароль" >
      <span class="form__error"><?=$dict['password']; ?> : <?=$errors['password']; ?></span>
    </div>

    <?php $classname = isset($errors['user_name']) ? "form__item--invalid" : "";
    $value = isset($signup['user_name']) ? $signup['user_name'] : ""; ?>

    <div class="form__item <?=$classname; ?>">
      <label for="user_name">Имя*</label>
      <input id="user_name" type="text" name="signup[user_name]" placeholder="Введите имя" value="<?=$value;?>">
      <span class="form__error"><?=$dict['user_name']; ?> : <?=$errors['user_name']; ?></span>
    </div>

    <?php $classname = isset($errors['contact']) ? "form__item--invalid" : "";
    $value = isset($signup['contact']) ? $signup['contact'] : ""; ?>

    <div class="form__item <?=$classname; ?>">
      <label for="message">Контактные данные*</label>
      <textarea id="message" name="signup[contact]" placeholder="Напишите как с вами связаться" ><?=$value;?></textarea>
      <span class="form__error"><?=$dict['contact']; ?> : <?=$errors['contact']; ?></span>
    </div>

    <?php $classname = isset($errors['file']) ? "" : "form__item--uploaded"; ?>

    <div class="form__item form__item--file form__item--last <?=$classname; ?>">
      <label>Аватар</label>

      <div class="preview">
        <?php if (isset($errors['lot_image'])): ?>
        <button class="preview__remove" type="button">x</button>
        
        <div class="preview__img">
          <img src="<?=$signup['user_foto']; ?>" width="113" height="113" alt="">
        </div>
        <?php endif; ?>
      </div>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="user_foto" id="photo2">
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
  </form>