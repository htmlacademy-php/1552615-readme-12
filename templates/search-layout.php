<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?=$search;?></span>
            </div>
        </div>
        <div class="search__results-wrapper">

            <?php if ($posts): ?>
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $post): ?>
                    <article class="search__post post post-<?php echo $post['classname'];?>">
                        <header class="post__header post__author">
                            <a class="post__author-link" href="<?php echo('/profile.php' . '?user_id=' . $post['user_id']) . '&tab=posts';?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <?php if (!empty($post['avatar'])):?>
                                    <img class="post__author-avatar" src="../uploads/avatars/<?php echo $post['avatar'];?>" alt="Аватар пользователя" width="60" height="60">
                                    <?php endif;?>
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?=$post['login'];?></b>
                                    <span class="post__time"><?=get_date_interval_format(date_create($post['published_at']), 'назад');?></span>
                                </div>
                            </a>
                        </header>
                        <div class="post__main">
                        <?php if ($post['classname'] === 'photo'):?>
                            <h2><a href="#"><?=$post['title'];?></a></h2>
                            <div class="post-photo__image-wrapper">
                                <img src="../uploads/<?php echo $post['picture'];?>" alt="Фото от пользователя" width="760" height="396">
                            </div>

                        <?php elseif ($post['classname'] === 'text'):?>
                            <h2><a href="#"><?=$post['title'];?></a></h2>
                            <p><?=get_cut_text($post['text_content']);?></p>

                        <?php elseif ($post['classname'] === 'video'):?>
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

                        <?php elseif ($post['classname'] === 'quote'):?>
                            <blockquote>
                                <p>
                                    <?=$post['text_content'];?>
                                </p>
                                <cite><?=$post['quote_author'];?></cite>
                            </blockquote>

                        <?php elseif ($post['classname'] === 'link'):?>
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
                        <?php endif;?>
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
                            </div>
                        </footer>
                    </article>
                    <?php endforeach;?>
                </div>
            </div>

            <?php else: ?>
            <div class="search__no-results container">
                <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                <p class="search__no-results-desc">
                Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
                </p>
                <div class="search__links">
                    <a class="search__popular-link button button--main" href="/popular.php">Популярное</a>
                    <a class="search__back-link" href="#">Вернуться назад</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>
