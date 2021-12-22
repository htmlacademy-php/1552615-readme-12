<div class="adding-post__input-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="heading">
    Заголовок
        <span class="form__input-required">*</span>
    </label>
    <?php $input_err = isset($errors['heading']) ? "form__input-section--error" : ""; ?>
    <div class="form__input-section <?php echo $input_err;?>">
        <input class="adding-post__input form__input " id="heading'" type="text" name="heading" placeholder="Введите заголовок" value="<?php echo(getPostVal($oldData, 'heading'));?>">
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['heading'];?></p>
        </div>
    </div>
</div>
