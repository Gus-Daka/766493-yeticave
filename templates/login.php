 <?php $classname = (count($errors)) ? "form--invalid" : ""; ?>

  <form class="form container <?=$classname; ?>" action="login.php" method="POST"> <!-- form--invalid -->
    <h2>Вход</h2>

    <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
    $value = isset($login['email']) ? $login['email'] : ""; ?>

    <div class="form__item <?=$classname; ?>"> <!-- form__item--invalid -->
      <label for="email">E-mail*</label>
      <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?=$value;?>">
      <span class="form__error"><?=$dict['email']; ?>: <?=$errors['email']; ?></span>
    </div>

<?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>

    <div class="form__item form__item--last <?=$classname; ?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="login[password]" placeholder="Введите пароль" >
      <span class="form__error"><?=$dict['password']; ?>: <?=$errors['password']; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
  </form>