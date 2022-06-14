<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">

                    <?php foreach ($posts as $post): ?>
                    <article class="feed__post post post-<?php echo $post['classname']; ?>">
                        <header class="post__header post__author">
                            <a class="post__author-link" href="<?php echo('/profile.php' . '?user_id=' . $post['user_id']) . '&tab=posts';?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <?php if (!empty($post['avatar'])): ?>
                                    <img class="post__author-avatar" src="../uploads/avatars/<?php echo $post['avatar']; ?>" alt="Аватар пользователя" width="60" height="60">
                                    <?php endif; ?>
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?=$post['user_login']; ?></b>
                                    <span class="post__time"><?=get_date_interval_format(date_create($post['published_at']), 'назад');?></span>
                                </div>
                            </a>
                        </header>
                        <div class="post__main">

                        <?php if ($post['classname'] === 'photo'): ?>
                            <h2><a href="#"><?=$post['title'];?></a></h2>
                            <div class="post-photo__image-wrapper">
                                <img src="uploads/<?php echo $post['picture'];?>" alt="Фото от пользователя" width="760" height="396">
                            </div>

                        <?php elseif ($post['classname'] === 'text'): ?>
                            <h2><a href="#"><?=$post['title'];?></a></h2>
                            <p><?=get_cut_text($post['text_content']);?></p>

                        <?php elseif ($post['classname'] === 'video'): ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <img src="<?php echo embed_youtube_cover($post['video']);?>" alt="Превью к видео" width="760" height="396">
                                </div>
                                <div class="post-video__control">
                                    <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                                    <div class="post-video__scale-wrapper">
                                        <div class="post-video__scale">
                                            <div class="post-video__bar">
                                                <div class="post-video__toggle"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button">
                                        <span class="visually-hidden">Полноэкранный режим</span>
                                    </button>
                                </div>
                                <button class="post-video__play-big button" type="button">
                                    <svg class="post-video__play-big-icon" width="27" height="28">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </button>
                            </div>

                        <?php elseif ($post['classname'] === 'quote'): ?>
                            <blockquote>
                                <p>
                                    <?=$post['text_content'];?>
                                </p>
                                <cite><?=$post['quote_author'];?></cite>
                            </blockquote>

                        <?php elseif ($post['classname'] === 'link'): ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="<?php echo $post['link']; ?>" title="Перейти по ссылке">
                                    <div class="post-link__icon-wrapper">
                                        <img src="img/logo-vita.jpg" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?=$post['title'];?></h3>
                                        <span><?=$post['link'];?></span>
                                    </div>
                                    <svg class="post-link__arrow" width="11" height="16">
                                        <use xlink:href="#icon-arrow-right-ad"></use>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                        </div>
                        <footer class="post__footer post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="<?php echo('/likes.php' . '?post_id=' . $post['id'])?>" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?=$post['total_likes'];?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span><?=$post['total_comm'];?></span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                                <a class="post__indicator post__indicator--repost button" href="<?php echo('/repost.php' . '?post_id=' . $post['id']);?>" title="Репост">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-repost"></use>
                                    </svg>
                                    <span><?=$post['repost_count'];?></span>
                                    <span class="visually-hidden">количество репостов</span>
                                </a>
                            </div>
                            <ul class="post__tags">
                                <?php if (key_exists($post['id'], $hashtags)):?><?php foreach ($hashtags[$post['id']] as $hashtag): ?>
                                    <li><a href="<?php echo('search.php' . '?q=%23' . $hashtag);?>">#<?=$hashtag;?>
                                    <?php endforeach;?></a></li>
                                <?php endif;?>
                            </ul>
                        </footer>
                    </article>
                    <?php endforeach; ?>

                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?php if ($type_classname == ''): echo('filters__button--active');?>
                        <?php endif; ?>" href="<?php echo $url; ?>">
                        <span>Все</span>
                    </a>
                </li>

                <?php foreach ($types as $type):?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--<?php echo $type['classname']; ?> button <?php if ($type['classname'] === $type_classname):?><?php echo('filters__button--active');?><?php endif;?>" href="<?php echo($url . '?type=' . $type['classname']);?>">
                        <span class="visually-hidden"><?php echo $type['title']; ?></span>
                        <svg class="filters__icon"
                        <?php if ($type['classname'] === 'photo'): ?>
                            <?php echo 'width="22" height="18"'; ?>
                        <?php elseif ($type['classname'] === 'video'): ?>
                            <?php echo 'width="24" height="16"'; ?>
                        <?php elseif ($type['classname'] === 'text'): ?>
                            <?php echo 'width="20" height="21"'; ?>
                        <?php elseif ($type['classname'] === 'quote'): ?>
                            <?php echo 'width="21" height="20"'; ?>
                        <?php elseif ($type['classname'] === 'link'): ?>
                            <?php echo 'width="21" height="18"'; ?>
                        <?php endif; ?>>
                            <use xlink:href="#icon-filter-<?php echo $type['classname']; ?>"></use>
                        </svg>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
