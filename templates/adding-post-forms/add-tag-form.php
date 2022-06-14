<div class="adding-post__input-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="tags">Теги</label>
    <?php $input_err = isset($errors['tags']) ? "form__input-section--error" : ""; ?>
    <div class="form__input-section <?php echo $input_err;?>">
        <input class="adding-post__input form__input" id="tags" type="text" name="tags" placeholder="Введите теги" value="<?php echo(getPostVal($oldData, 'tags'));?>">
        <button class="form__error-button button" type="button">!
            <span class="visually-hidden">Информация об ошибке</span>
        </button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['tags'];?></p>
        </div>
    </div>
</div>
