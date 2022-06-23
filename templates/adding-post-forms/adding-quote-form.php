<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <?=$form_header;?>
        <div class="adding-post__input-wrapper form__textarea-wrapper">
            <label class="adding-post__label form__label" for="cite-text">Текст цитаты
                <span class="form__input-required">*</span>
            </label>
            <?php $input_err = isset($errors['cite-text']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" placeholder="Текст цитаты" name="cite-text"><?=getPostVal($oldData, 'cite-text');?></textarea>
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка!</h3>
                    <p class="form__error-desc"><?php if (isset($errors['cite-text'])):?><?=$errors['cite-text'];?><?php endif;?></p>
                </div>
            </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="quote-author">Автор
                <span class="form__input-required">*</span>
            </label>
            <?php $input_err = isset($errors['quote-author']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" value="<?=getPostVal($oldData, 'quote-author');?>">
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка!</h3>
                    <p class="form__error-desc"><?php if (isset($errors['quote-author'])):?><?=$errors['quote-author'];?><?php endif;?></p>
                </div>
            </div>
        </div>
        <?=$tag_form;?>
    </div>
    <?=$form_errors;?>
</div>

