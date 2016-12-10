<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HomePage</title>
</head>
<body>
    <h1>Latests Posts</h1>
    <?php if ( ! empty($last_posts)) : ?>
        <?php foreach ($last_posts as $post) : ?>
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