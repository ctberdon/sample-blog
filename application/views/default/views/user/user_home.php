<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HomePage</title>
</head>
<body>
    <h2>Add New Post</h2>
    <?php echo form_open(site_url('user/posts/update_post'), array('id' => 'blog-form')) ?>
    <?php echo form_hidden('id', set_value('id', !empty($post_details['id']) ? $post_details['id'] : '')) ?>
    <div>
        <?php echo form_label('Title') ?> <?php echo form_input('post_title', set_value('post_title', !empty($post_details['post_title']) ? $post_details['post_title'] : '')) ?>
    </div>
    <div>
        <?php echo form_label('Summary') ?> <?php echo form_textarea('post_excerpt', set_value('post_excerpt', !empty($post_details['post_excerpt']) ? $post_details['post_excerpt'] : '')) ?>
    </div>
    <div>
        <?php echo form_label('Content') ?> <?php echo form_textarea('post_content', set_value('post_content', !empty($post_details['post_content']) ? $post_details['post_content'] : '')) ?>
    </div>
    <div>
        <?php echo form_label('Status') ?> <?php echo form_dropdown('post_status', array('unpublished' => 'Unpublished', 'published' => 'Published', 'private' => 'Private'), set_value('post_status', !empty($post_details['post_status']) ? $post_details['post_status'] : 'unpublished')) ?>
    </div>
    <div>
        <?php echo form_submit('post_blog', 'Submit') ?>
    </div>
    <?php echo form_close() ?>
    
    <h1>Your Posts</h1>
    <?php if ( ! empty($user_last_posts)) : ?>
        <?php foreach ($user_last_posts as $post) : ?>
            <h2>
                <?php echo $post['post_title'] ?>
                <?php if ($post['post_author_id'] == session_userdata('user_id')) : ?>
                <a href="<?php echo site_url("user/posts/edit_post/{$post['id']}") ?>">Edit Post</a>
                <a href="<?php echo site_url("user/posts/remove_post/{$post['id']}") ?>">Remove Post</a>
                <?php endif; ?>
            </h2>
            <p>
                By <?php echo !empty($post['author']) ? trim($post['author']) : 'Unknown' ?>
                
                <?php if (strcasecmp($post['post_status'], 'published') == 0) : ?>
                <br />Published on <?php echo date('D, M d, Y h:mA', strtotime($post['published'])) ?>
                <?php endif; ?>
                
                <?php if ($post['published'] != $post['modified']) : ?>
                <br />Updated on <?php echo date('D, M d, Y h:mA', strtotime($post['modified'])) ?>
                <?php endif; ?>
            </p>
            <p><?php echo word_limiter($post['post_content'], 300) ?></p>
        <?php endforeach; ?>
    <?php else : ?>
    <p>You have no posts yet.</p>
    <?php endif; ?>
    
    <h1>Latests Posts</h1>
    <?php if ( ! empty($all_last_posts)) : ?>
        <?php foreach ($all_last_posts as $post) : ?>
            <h2><?php echo $post['post_title'] ?></h2>
            <p>
                By <?php echo !empty($post['author']) ? trim($post['author']) : 'Unknown' ?><br />
                Published on <?php echo date('D, M d, Y h:mA', strtotime($post['published'])) ?>
                <?php if ($post['published'] != $post['modified']) : ?>
                <br />Updated on <?php echo date('D, M d, Y h:mA', strtotime($post['modified'])) ?>
                <?php endif; ?>
            </p>
            <p><?php echo word_limiter($post['post_content'], 300) ?></p>
        <?php endforeach; ?>
    <?php else : ?>
    <p>No posts yet.</p>
    <?php endif; ?>
</body>
</html>