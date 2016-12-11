<?php include_once __DIR__ .'/posts_form.php' ?>

<h2>Your Activity</h2>
<?php if (!empty($user_posts)) : ?>
    <?php foreach ($user_posts as $post) : ?>

        <div class="blog-post blog-post-editable blog-post-container" data-post-id="<?php echo $post['id'] ?>">
            <h2 class="blog-post-title">
                <?php echo $post['post_title'] ?>
                <div class="pull-right edit-buttons">
                    <a class="btn btn-warning btn-sm edit-blog-button hide-me" title="Edit post" data-post-id="<?php echo $post['id'] ?>" href="<?php echo site_url("user/posts/edit_post/{$post['id']}") ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                    <a class="btn btn-danger btn-sm remove-blog-button hide-me" title="Remove post" data-post-id="<?php echo $post['id'] ?>" href="<?php echo site_url("user/posts/remove_post/{$post['id']}") ?>"><i class="fa fa-remove" aria-hidden="true"></i> Remove</a>
                    <a class="btn btn-sm toggle-blog-button <?php echo strcasecmp($post['post_status'], 'published') == 0 ? 'btn-success' : 'btn-default' ?>" title="Click to <?php echo strcasecmp($post['post_status'], 'published') == 0 ? 'Unpublished' : 'Published' ?>" data-post-id="<?php echo $post['id'] ?>" href="<?php echo site_url("user/posts/toggle_status/{$post['id']}") ?>"><?php echo strcasecmp($post['post_status'], 'published') == 0 ? 'Published' : 'Unpublished' ?></a>
                </div>
            </h2>
            <p class="blog-post-meta">
                Created on <?php echo date('D, M d, Y h:m A', strtotime($post['created'])) ?>
                <?php if (strcasecmp($post['post_status'], 'published') == 0) : ?>
                    <br />Published on <?php echo date('D, M d, Y h:mA', strtotime($post['published'])) ?>
                <?php endif; ?>

                <?php if ($post['published'] != $post['modified']) : ?>
                    <br />Updated on <?php echo date('D, M d, Y h:mA', strtotime($post['modified'])) ?>
                <?php endif; ?>
            </p>
            <p><?php echo !empty($post['post_content']) ? render_markdown($post['post_content']) : '' ?></p>
        </div>

    <?php endforeach; ?>
<?php else : ?>
    <p>You have no posts yet.</p>
<?php endif; ?>