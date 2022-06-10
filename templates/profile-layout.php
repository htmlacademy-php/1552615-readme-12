<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <?php if (!empty($user_data['avatar'])):?>
                        <img class="profile__picture user__picture" src="uploads/avatars/<?php echo($user_data['avatar']);?>" alt="Аватар пользователя">
                        <?php endif;?>
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?=$user_data['user_login'];?></span>
                        <time class="profile__user-time user__time" datetime="<?php echo(date_format(date_create($user_data['created_at']), 'Y-m-d'));?>"><?=get_date_interval_format(date_create($user_data['created_at']), 'на сайте');?></time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?=$user_data['total_posts'];?></span>
                        <span class="profile__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?=$user_data['total_subs'];?></span>
                        <span class="profile__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>
                <div class="profile__user-buttons user__buttons">
                    <?php if ($user_id === $profile_user_id):?>
                    <a class="profile__user-button user__button user__button--subscription button button--quartz visually-hidden" href="#"></a>
                    <?php elseif (in_array($profile_user_id, $user_subs)): ?>
                    <a class="profile__user-button user__button user__button--subscription button button--main" href="<?php echo('/subscribtion.php' . '?user_id=' . $profile_user_id);?>">Отписаться</a>
                    <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
                    <?php else: ?>
                    <a class="profile__user-button user__button user__button--subscription button button--main" href="<?php echo('/subscribtion.php' . '?user_id=' . $profile_user_id);?>">Подписаться</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button  tabs__item  button <?php if ($tab === 'posts'):?>filters__button--active tabs__item--active<?php endif;?>" href="<?php echo($url . '?user_id=' . $profile_user_id . '&tab=' . 'posts');?>">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button <?php if ($tab === 'likes'):?>filters__button--active tabs__item--active<?php endif;?>" href="<?php echo($url . '?user_id=' . $profile_user_id . '&tab=' . 'likes');?>">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button <?php if ($tab === 'subscribtions'):?>filters__button--active tabs__item--active<?php endif;?>" href="<?php echo($url . '?user_id=' . $profile_user_id . '&tab=' . 'subscribtions');?>">Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">

                <?=$active_tab;?>

                </div>
            </div>
        </div>
    </div>
</main>
