<h2>Generate Random Post</h2>
<div class="alert alert-danger">
    <strong>WARNING!!!!</strong>
    <p>This might take a while depending on your internet connection.</p>
    <p>Do not close your browser until this function is done generating random posts for you.</p>
</div>

<div class="blog-post">
    <?php echo form_open(site_url('user/generate_random_post', array('id' => 'random-post-generator-form'))) ?>
        <?php echo form_label('Number of Post to Generate', 'num_of_posts') ?>
        <?php echo form_input('num_of_posts', '1000', array('id' => 'num_of_posts')) ?>
        <button type="submit" name="generate_random" class="btn btn-default">Submit</button>
    <?php echo form_close() ?>
</div>