<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                    <?php foreach ($types as $type):?>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link filters__button filters__button--<?php echo $type['classname'];?>
                            tabs__item button
                            <?php if ($type['classname'] == $classname):?>
                                <?php echo ('filters__button--active tabs__item--active');?>
                            <?php endif;?>"
                            href="<?php echo ($url . '?id=' . $type['id']);?>">
                                <svg class="filters__icon"
                                <?php if ($type['classname'] == 'photo'): ?>
                                    <?php echo ('width="22" height="18"');?>
                                <?php elseif ($type['classname'] == 'video'):?>
                                    <?php echo ('width="24" height="16"');?>
                                <?php elseif ($type['classname'] == 'text'):?>
                                    <?php echo ('width="20" height="21"');?>
                                <?php elseif ($type['classname'] == 'quote'):?>
                                    <?php echo ('width="21" height="20"');?>
                                <?php elseif ($type['classname'] == 'link'):?>
                                    <?php echo ('width="21" height="18"');?>
                                <?php endif;?>>
                                    <use xlink:href="#icon-filter-<?php echo $type['classname'];?>"></use>
                                </svg>
                                <span><?=$type['title'];?></span>
                            </a>
                        </li>
                    <?php endforeach;?>
                    </ul>
                </div>
                <?php foreach($types as $type): ?>
                    <div class="adding-post__tab-content">
                        <?php if ($type['classname'] == $classname): ?>
                        <section class="adding-post__<?php echo($type['classname']);?> tabs__content tabs__content--active">
                            <h2 class="visually-hidden">
                                Форма добавления <?=$type['title'];?>
                            </h2>
                            <form class="adding-post__form form" action="add.php" method="post"
                                <?php if($type['classname'] == 'photo'):?>
                                    <?php echo'enctype="multipart/form-data"';?>
                                <?php else: ?>
                                    <?php echo '';?>
                                <?php endif; ?>
                                name="<?php echo($type['classname']);?>">
                                <?=$active_form;?>
                                <input type="hidden" name="classname" value="<?php echo($type['classname']);?>">
                                <div class="adding-post__buttons">
                                    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                    <a class="adding-post__close" href="#">Закрыть</a>
                                </div>
                            </form>
                        </section>
                        <?php endif;?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</main>
