<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?=$post['title'] ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-<?php echo $post['classname']?>">
                <div class="post-details__main-block post post--details">

                    <?=$active_post; ?>

                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="<?php echo('/likes.php' . '?post_id=' . $post['id'])?>" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?=$likes;?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?=$post['total_comm']?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button" href="<?php echo('/repost.php' . '?post_id=' . $post['id']);?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?=$post['repost_count']?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view"><?=$post['watch_count'] ?> просмотров</span>
                    </div>
                    <ul class="post__tags">
                    <?php if (key_exists($post['id'], $hashtags)):?><?php foreach ($hashtags[$post['id']] as $hashtag): ?>
                        <li><a href="<?php echo('search.php' . '?q=%23' . $hashtag);?>">#<?=$hashtag;?>
                        <?php endforeach;?></a></li>
                    <?php endif;?>
                    </ul>
                    <div class="comments">
                        <form class="comments__form form" action="" method="post">
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
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="<?php echo('/profile.php' . '?user_id=' . $post['user_id']) . '&tab=posts';?>">
                            <?php if (!empty($post['avatar'])): ?>
                                <img class="post-details__picture user__picture" src="../uploads/avatars/<?php echo $post['avatar']; ?>" alt="Аватар пользователя">
                            <?php endif; ?>
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="<?php echo('/profile.php' . '?user_id=' . $post['user_id']) . '&tab=posts';?>">
                                <span><?=$post['login']; ?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="<?php echo(date_format(date_create($post['created_at']), 'Y-m-d'));?>"><?=get_date_interval_format(date_create($post['created_at']), 'на сайте');?></time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount">
                                <?=$subs;?>
                            </span>
                            <span class="post-details__rating-text user__rating-text">подписчиков</span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount">
                                <?=$totalpost; ?>
                            </span>
                            <span class="post-details__rating-text user__rating-text">публикаций</span>
                        </p>
                    </div>
                    <div class="post-details__user-buttons user__buttons">
                        <?php if ($user_id === $post['user_id']):?>
                        <a class="post-mini__user-button user__button user__button--subscription button button--quartz visually-hidden" href="#">&nbsp;</a>
                        <?php elseif ($user_subs && in_array($post['user_id'], $user_subs)): ?>
                        <a class="profile__user-button user__button user__button--subscription button button--main" href="<?php echo('/subscription.php' . '?user_id=' . $post['user_id']);?>">Отписаться</a>
                        <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
                        <?php else: ?>
                        <a class="profile__user-button user__button user__button--subscription button button--main" href="<?php echo('/subscription.php' . '?user_id=' . $post['user_id']);?>">Подписаться</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
