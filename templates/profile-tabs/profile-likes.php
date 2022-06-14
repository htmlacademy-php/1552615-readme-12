<section class="profile__likes tabs__content tabs__content--active">
    <h2 class="visually-hidden">Лайки</h2>
    <ul class="profile__likes-list">

        <?php foreach ($tab_data as $data): ?>
        <li class="post-mini post-mini--<?php echo $data['classname'];?> post user">

            <div class="post-mini__user-info user__info">
                <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="<?php echo('/profile.php' . '?user_id=' . $data['user_id']) . '&tab=posts';?>">
                        <?php if (!empty($data['avatar'])):?>
                        <img class="post-mini__picture user__picture" src="uploads/avatars/<?php echo $data['avatar'];?>" alt="Аватар пользователя">
                        <?php endif;?>
                    </a>
                </div>
                <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="<?php echo('/profile.php' . '?user_id=' . $data['user_id']) . '&tab=posts';?>">
                        <span><?=$data['user_login'];?></span>
                    </a>
                    <div class="post-mini__action">
                        <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                        <time class="post-mini__time user__additional" datetime="<?php echo(date_format(date_create($data['liked_at']), 'c'));?>"><?=get_date_interval_format(date_create($data['liked_at']), 'назад');?></time>
                    </div>
                </div>
            </div>

            <div class="post-mini__preview">
                <a class="post-mini__link" href="<?php echo('/post.php?post_id=' . $data['post_id'])?>" title="Перейти на публикацию">
            <?php if ($data['classname'] === 'photo'):?>
                    <div class="post-mini__image-wrapper">
                        <?php if (!empty($data['picture'])): ?>
                        <img class="post-mini__image" src="uploads/<?php echo $data['picture'];?>" width="109" height="109" alt="Превью публикации">
                        <?php endif;?>
                    </div>
                    <span class="visually-hidden">Фото</span>
                </a>
            </div>

            <?php elseif ($data['classname'] === 'text'):?>
                    <span class="visually-hidden">Текст</span>
                    <svg class="post-mini__preview-icon" width="20" height="21">
                        <use xlink:href="#icon-filter-text"></use>
                    </svg>


            <?php elseif ($data['classname'] === 'video'):?>
                    <div class="post-mini__image-wrapper">
                        <img class="post-mini__image" src="img/coast-small.png" width="109" height="109" alt="Превью публикации">
                        <span class="post-mini__play-big">
                        <svg class="post-mini__play-big-icon" width="12" height="13">
                            <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        </span>
                    </div>
                    <span class="visually-hidden">Видео</span>

            <?php elseif ($data['classname'] === 'quote'):?>
                    <span class="visually-hidden">Цитата</span>
                    <svg class="post-mini__preview-icon" width="21" height="20">
                        <use xlink:href="#icon-filter-quote"></use>
                    </svg>

            <?php elseif ($data['classname'] === 'link'):?>
                    <span class="visually-hidden">Ссылка</span>
                    <svg class="post-mini__preview-icon" width="21" height="18">
                        <use xlink:href="#icon-filter-link"></use>
                    </svg>
                    <?php endif;?>
                </a>
            </div>
        </li>
        <?php endforeach;?>

    </ul>
</section>
