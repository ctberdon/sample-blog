<?php if ( ! empty($last_posts)) : ?>
    <?php foreach ($last_posts as $post) : ?>
        <div class="blog-post">
            <h2 class="blog-post-title"><?php echo $post['post_title'] ?></h2>
            <p class="blog-post-meta">
                <?php echo date('D, M d, Y h:m A', strtotime($post['published'])) ?>
                by <a href="<?php echo !empty($post['profile_url']) ? $post['profile_url'] : '#' ?>" target="_blank"><?php echo !empty($post['author']) ? trim($post['author']) : 'Unknown' ?></a>
                <?php if ($post['published'] != $post['modified']) : ?>
                    <br />Updated on <?php echo date('D, M d, Y h:m A', strtotime($post['modified'])) ?>
                <?php endif; ?>
            </p>
            <p><?php echo !empty($post['post_content']) ? $post['post_content'] : '' ?></p>
        </div><!-- /.blog-post -->
    <?php endforeach; ?>
<?php else : ?>
    <div class="blog-post"><p>No posts yet.</p></div><!-- /.blog-post -->
<?php endif; ?>