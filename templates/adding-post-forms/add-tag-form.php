<div class="adding-post__input-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="<?php echo($one_type['classname']);?>-tags">Теги</label>
    <div class="form__input-section">
        <input class="adding-post__input form__input" id="<?php echo($one_type['classname']);?>-tags" type="text" name="<?php echo($one_type['classname']);?>-heading" placeholder="Введите ссылку" value="<?php echo(getPostVal($one_type['classname'] . '-heading'));?>">
        <button class="form__error-button button" type="button">!
            <span class="visually-hidden">Информация об ошибке</span>
        </button>
        <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
        </div>
    </div>
</div>
