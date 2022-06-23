<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <?=$form_header;?>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="video-url">Ссылка youtube
                <span class="form__input-required">*</span>
            </label>
            <?php $input_err = isset($errors['video-url']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <input class="adding-post__input form__input" id="video-url" type="text" name="video-url" placeholder="Введите ссылку" value="<?php echo(getPostVal($oldData, 'video-url'));?>">
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка!</h3>
                    <p class="form__error-desc"><?php if(isset($errors['cite-text'])):?><?=$errors['video-url'];?><?php endif;?></p>
                </div>
            </div>
        </div>
        <?=$tag_form;?>
    </div>
    <?=$form_errors;?>
</div>

