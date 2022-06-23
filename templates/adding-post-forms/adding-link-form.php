<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <?=$form_header;?>
        <div class="adding-post__textarea-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="post-link">Ссылка
                <span class="form__input-required">*</span>
            </label>
            <?php $input_err = isset($errors['post-link']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <input class="adding-post__input form__input" id="post-link" type="text" name="post-link" value="<?php echo(getPostVal($oldData, 'post-link'));?>">
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка!</h3>
                    <p class="form__error-desc"><?php if (isset($errors['post-link'])):?><?=$errors['post-link'];?><?php endif;?></p>
                </div>
            </div>
        </div>
        <?=$tag_form;?>
    </div>
    <?=$form_errors;?>
</div>
