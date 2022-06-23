<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($tab_data as $post): ?>
    <article class="profile__post post post-<?php echo($post['classname']);?>">
        <header class="post__header">
            <?php if ($post['is_repost'] === '1'):?>
            <div class="post__author">
                <a class="post__author-link" title="Автор" href="<?php echo('/profile.php' . '?user_id=' . $post['original_author_id'] . '&tab=posts');?>">
                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                        <?php if (!empty($post['author_avatar'])):?>
                        <img class="post__author-avatar" src="uploads/avatars/<?php echo $post['author_avatar'];?>" alt="Аватар пользователя">
                        <?php endif;?>
                    </div>
                    <div class="post__info">
                        <b class="post__author-name">Репост: <?=$post['author_login'];?></b>
                        <time class="post__time" datetime="<?php echo(date_format(date_create($post['published_at']), 'c'));?>"><?=get_date_interval_format(date_create($post['published_at']), 'назад');?></time>
                    </div>
                </a>
            </div>
            <?php else: ?>
            <h2><a href="<?php echo('/post.php?post_id=' . $post['id'])?>"><?=$post['title'];?></a></h2>
            <?php endif;?>
        </header>
        <div class="post__main">
            <?php if ($post['classname'] === 'photo'):?>
            <div class="post-photo__image-wrapper">
                <?php if (isset($post['picture'])):?>
                <img src="uploads/<?php echo $post['picture'];?>" alt="Фото от пользователя" width="760" height="396">
                <?php endif;?>
            </div>

            <?php elseif ($post['classname'] === 'text'):?>
            <p><?=get_cut_text($post['text_content']);?></p>

            <?php elseif ($post['classname'] === 'video'):?>
            <div class="post-video__block">
                <div class="post-video__preview">
                    <?=embed_youtube_cover(htmlspecialchars($post['video']));?>
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

        <footer class="post__footer">
        <div class="post__indicators">
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
                <a class="post__indicator post__indicator--repost button" href="<?php echo('/repost.php' . '?post_id=' . $post['id']);?>" title="Репост">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                    </svg>
                    <span><?=$post['repost_count'];?></span>
                    <span class="visually-hidden">количество репостов</span>
                </a>
            </div>
            <time class="post__time" datetime="<?php echo(date_format(date_create($post['published_at']), 'c'));?>"><?=get_date_interval_format(date_create($post['published_at']), 'назад');?></time>
        </div>
        <ul class="post__tags">
        <?php if ($post['is_repost'] === '1'):?>
            <?php if (key_exists($post['original_post_id'], $hashtags)):?><?php foreach ($hashtags[$post['original_post_id']] as $hashtag): ?>
            <li><a href="<?php echo('search.php' . '?q=%23' . $hashtag);?>">#<?=$hashtag;?>
            <?php endforeach;?></a></li>
            <?php endif;?>
        <?php else:?>
            <?php if (key_exists($post['id'], $hashtags)):?><?php foreach ($hashtags[$post['id']] as $hashtag): ?>
                <li><a href="<?php echo('search.php' . '?q=%23' . $hashtag);?>">#<?=$hashtag;?>
                <?php endforeach;?></a></li>
            <?php endif;?>
        <?php endif;?>
        </ul>
        </footer>
        <div class="comments">
            <div class="comments__list-wrapper">
                <ul class="comments__list">
                    <?php foreach ($comments as $comment): ?>
                    <?php if ($post['id'] === $comment['post_id']):?>
                    <li class="comments__item user">
                        <div class="comments__avatar">
                            <a class="user__avatar-link" href="<?php echo('/profile.php' . '?user_id=' . $comment['user_id'] . '&tab=posts');?>">
                                <?php if (!empty($comment['comment_author_avatar'])):?>
                                <img class="comments__picture" src="/uploads/avatars/<?php echo $comment['comment_author_avatar'];?>" alt="Аватар пользователя">
                                <?php endif;?>
                            </a>
                        </div>
                        <div class="comments__info">
                            <div class="comments__name-wrapper">
                                <a class="comments__user-name" href="<?php echo('/profile.php' . '?user_id=' . $comment['user_id'] . '&tab=posts');?>">
                                <span><?=$comment['comment_author'];?></span>
                                </a>
                                <time class="comments__time" datetime="<?php echo(date_format(date_create($comment['published_at']), 'c'));?>"><?=get_date_interval_format(date_create($comment['published_at']), 'назад');?></time>
                            </div>
                            <p class="comments__text">
                                <?=$comment['comment'];?>
                            </p>
                        </div>
                    </li>
                    <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <form class="comments__form form" action="#" method="post">
            <div class="comments__my-avatar">
                <?php if (!empty($user_avatar)):?>
                <img class="comments__picture" src="/uploads/avatars/<?php echo $user_avatar;?>" alt="Аватар пользователя">
                <?php endif;?>
            </div>
            <?php $input_err = isset($errors['comment']) ? "form__input-section--error" : ""; ?>
            <div class="form__input-section <?php echo $input_err;?>">
                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий" name="comment"></textarea>
                <label class="visually-hidden">Ваш комментарий</label>
                <input type="hidden" name="post_id" value="<?php echo($post['id']);?>">
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?php if(isset($errors['comment'])):?><?=$errors['comment'];?><?php endif;?></p>
                </div>
            </div>
            <button class="comments__submit button button--green" type="submit">Отправить</button>
        </form>
    </article>
    <?php endforeach; ?>

</section>
