<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <?=$form_header;?>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
            <?php $input_err = isset($errors['photo-url']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" placeholder="Введите ссылку" value="<?php echo(getPostVal($oldData, 'photo-url'));?>">
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка!</h3>
                    <p class="form__error-desc"><?php if (isset($errors['photo-url'])):?><?=$errors['photo-url'];?><?php endif;?></p>
                </div>
            </div>
        </div>
        <?=$tag_form;?>
    </div>
    <?=$form_errors;?>
</div>
<div class="adding-post__input-file-container form__input-container form__input-container--file">
    <div class="adding-post__input-file-wrapper form__input-file-wrapper">
        <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
            <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" " enctype="multipart/form-data">
            <div class="form__file-zone-text">
                <span>Перетащите фото сюда</span>
            </div>
        </div>
        <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
            <span>Выбрать фото</span>
            <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                <use xlink:href="#icon-attach"></use>
            </svg>
        </button>
    </div>
    <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

    </div>
</div>

