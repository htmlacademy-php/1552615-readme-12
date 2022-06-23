<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
    <?=$form_header;?>
    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
        <label class="adding-post__label form__label" for="post-text">Текст поста
            <span class="form__input-required">*</span>
        </label>
        <?php $input_err = isset($errors['post-text']) ? "form__input-section--error" : ""; ?>
        <div class="form__input-section <?php echo $input_err;?>">
            <textarea class="adding-post__textarea form__textarea form__input" id="post-text" placeholder="Введите текст публикации" name="post-text"><?=getPostVal($oldData, 'post-text');?></textarea>
            <button class="form__error-button button" type="button">!
                <span class="visually-hidden">Информация об ошибке</span>
            </button>
            <div class="form__error-text">
                <h3 class="form__error-title">Ошибка!</h3>
                <p class="form__error-desc"><?php if(isset($errors['cite-text'])):?><?=$errors['post-text'];?><?php endif;?></p>
            </div>
        </div>
    </div>
    <?=$tag_form;?>
    </div>
    <?=$form_errors;?>
</div>
