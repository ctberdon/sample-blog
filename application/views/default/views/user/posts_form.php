<div class="blog-post">
    <h2><?php echo !empty($post_details) ? 'Edit Post' : 'Quick Draft' ?></h2>
    
    <?php if (!empty(flashdata_value('form_validation_errors'))) : ?>
        <div class="alert alert-danger">
            <?php echo flashdata_value('form_validation_errors') ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty(flashdata_value('success_message'))) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo flashdata_value('success_message') ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty(flashdata_value('error_message'))) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo flashdata_value('error_message') ?>
        </div>
    <?php endif; ?>
    
    <?php echo form_open(site_url('user/posts/update_post'), array('id' => 'blog-form', 'class' => 'blog-form')) ?>
    <?php echo form_hidden('id', set_value('id', !empty($post_details['id']) ? $post_details['id'] : '')) ?>
        <div class="form-group">
            <?php echo form_input('post_title', set_value('post_title', !empty($post_details['post_title']) ? $post_details['post_title'] : ''), array('id' => 'post_title', 'class' => 'form-control', 'placeholder' => 'Title')) ?>
        </div>
        <div class="form-group">
            <?php echo form_textarea(array('id' => 'post_content', 'class' => 'form-control', 'rows' => '10', 'placeholder' => 'Content'), set_value('post_content', !empty($post_details['post_content']) ? $post_details['post_content'] : ''), array('name' => 'post_content')) ?>
        </div>
        <div class="form-group">
            <?php echo form_label('Status', 'post_status') ?>
            <?php echo form_dropdown('post_status', array('unpublished' => 'Unpublished', 'published' => 'Published', 'private' => 'Private'), set_value('post_status', !empty($post_details['post_status']) ? $post_details['post_status'] : 'unpublished'), array('id' => 'post_status', 'class' => 'form-control')) ?>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
        <?php if ( ! empty($post_details)) : ?>
            <a href="<?php echo site_url('user') ?>" class="btn btn-info">Cancel</a>
        <?php endif; ?>
    <?php echo form_close() ?>
</div>