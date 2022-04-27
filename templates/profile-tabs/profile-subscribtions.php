<section class="profile__subscriptions tabs__content tabs__content--active">
    <h2 class="visually-hidden">Подписки</h2>
    <ul class="profile__subscriptions-list">
        <?php foreach ($tab_data as $data): ?>
        <li class="post-mini post-mini--photo post user">
            <div class="post-mini__user-info user__info">
                <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="<?php echo('/profile.php' . '?user_id=' . $data['id']) . '&tab=posts';?>">
                        <?php if(!empty($data['avatar'])): ?>
                        <img class="post-mini__picture user__picture" src="/uploads/avatars/<?php echo $data['avatar'];?>" alt="Аватар пользователя">
                        <?php endif; ?>
                    </a>
                </div>
                <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="<?php echo('/profile.php' . '?user_id=' . $data['id']) . '&tab=posts';?>">
                        <span><?=$data['user_login'];?></span>
                    </a>
                    <time class="post-mini__time user__additional" datetime="<?php echo (date_format(date_create($data['created_at']), 'c'));?>"><?=get_date_interval_format(date_create($data['created_at']), 'на сайте');?></time>
                </div>
            </div>
            <div class="post-mini__rating user__rating">
                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                    <span class="post-mini__rating-amount user__rating-amount"><?=$data['total_posts'];?></span>
                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                </p>
                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                    <span class="post-mini__rating-amount user__rating-amount"><?=$data['total_subs'];?></span>
                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                </p>
            </div>
            <div class="post-mini__user-buttons user__buttons">
                <?php if(isset($data)):?>
                <a class="post-mini__user-button user__button user__button--subscription button button--quartz" href="<?php echo ('/subscribtion.php' . '?user_id=' . $profile_user_id);?>">Отписаться</a>
                <?php else: ?>
                <a class="post-mini__user-button user__button user__button--subscription button button--main" href="<?php echo ('/subscribtion.php' . '?user_id=' . $profile_user_id);?>">Подписаться</a>
                <?php endif;?>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</section>
