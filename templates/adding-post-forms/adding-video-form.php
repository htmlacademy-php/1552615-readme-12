<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <?=$form_header;?>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="video-url">Ссылка youtube
                <span class="form__input-required">*</span>
            </label>
            <div class="form__input-section">
                <input class="adding-post__input form__input" id="video-url" type="text" name="video-heading" placeholder="Введите ссылку" value="<?php echo(getPostVal('video-heading'));?>">
                <button class="form__error-button button" type="button">!
                    <span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Заголовок сообщения</h3>
                    <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                </div>
            </div>
        </div>
        <?=$tag_form;?>
    </div>
    <div class="form__invalid-block">
        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
        <ul class="form__invalid-list">
            <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
        </ul>
    </div>
</div>

