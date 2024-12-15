<?php
use gpw\controller\PostController;

$postController = new PostController();
$recentPost = $postController->getRecentPostByCategoryEvents();

/**
 * @package Giaiphapweb_Theme
 * * Template for google review section.
 */
?>
<section class="events">
    <div class="events__content">
        <div class="events__content-left">
            <img src="<?= $recentPost['thumbnail'] ?>">
        </div>
        <div class="events__content-right">;
            <div class="events__content-item">
                <h5>BEST TAILORS IN HOI AN
                </h5>
                <h2 class="content-item__title"> <?= $recentPost['title'] ?>
                </h2>
                <?= $recentPost['excerpt'] ?>
                <h4>Come now, lest you miss, or contact Lana Tailor for advice.
                </h4>
                <div class="event-button-wrapper"><a href="<?= $recentPost['link'] ?>" class="normal-button"
                        target="_self">Read more</a></div>
                <p class="notice">* Online and In-Store, some exclusions apply.</p>
            </div>
        </div>
    </div>
</section>