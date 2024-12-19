<?php
get_header();
$privacyPolicy = get_fields(get_the_ID());
ob_start();
?>
<section class="section-privacy-policy">
    <div class="section__inner">
        <h3 class="section-privacy-policy__title"><?= $privacyPolicy['title'] ?></h3>
        <p><?= $privacyPolicy['description'] ?></p>
        <?php foreach ($privacyPolicy['category'] as $item):
            ?>
            <h4 class="category-title"><?= $item['category_title'] ?></h4>
            <?php if ($item['description']):
                ?>
                <ul class="category-description"><?= $item['description'] ?></ul>
                <?php
            endif;
            if ($item['content']):
                foreach ($item['content'] as $content):
                    ?>
                    <li><?= $content['item_content'] ?></li>
                    <?php
                endforeach;
            endif;
            if ($item['content_bottom']):
                ?>
                <p><?= $item['content_bottom'] ?></p>
                <?php
            endif;
        endforeach;
        ?>
        <h3 class="privacy-policy__title">Credit Limits by Garment Type</h3>

        <table style="width:100%">
            <tr>
                <th>Garment Type</th>
                <th>Cash Back</th>
                <th>Store Credit</th>
            </tr>
            <?php foreach ($privacyPolicy['table'] as $table):
                ?>
                <tr>
                    <td><?= $table['garment'] ?></td>
                    <td>$<?= $table['cash_back'] ?></td>
                    <td>$ <?= $table['store_credit'] ?></td>
                </tr>
                <?php
            endforeach; ?>
        </table>
        <?php
        foreach ($privacyPolicy['content_under_table'] as $contentUnderTable) {
            if ($contentUnderTable['content']):
                ?>
                <p><?= $contentUnderTable['content'] ?></p>
                <?php
            endif;
            if ($contentUnderTable['title']):
                ?>
                <h4><?= $contentUnderTable['title'] ?></h4>
                <?php
            endif;
            if ($contentUnderTable['ulli']):
                foreach ($contentUnderTable['ulli'] as $ulli):
                    if ($ulli['name_ul']):
                        ?>
                        <ul><?= $ulli['name_ul'] ?>
                        </ul>
                        <?php foreach ($ulli['content_li'] as $li):
                            ?>
                            <li><?= $li['name'] ?></li>
                            <?php
                        endforeach;
                        ?>
                        <?php
                    else:
                        foreach ($ulli['content_li'] as $li):
                            ?>
                            <li><?= $li['name'] ?></li>
                            <?php
                        endforeach;

                    endif;
                endforeach;
            endif;
        }
        ?>
    </div>
</section>
<?php
get_footer();