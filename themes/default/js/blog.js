(function() {
    $('.blog-post-container').on('click', 'a.remove-blog-button, a.toggle-blog-button', function(e) {
        e.preventDefault();
        var _this = $(this); // cache object
        var _prompt = _this.hasClass('remove-blog-button') ? 'Are you sure you want to remove this post?' : 'Are you sure you want to change status of this post?';
        
        if (confirm(_prompt)) {
            $.ajax({
                method: "POST",
                url: _this.attr('href') + '?_=' + new Date().getTime(),
                dataType: "json",
                data: {post_id: _this.attr('data-post-id')}
            })
            .done(function (response) {
                if (typeof response.status != 'undefined') {
                    
                    if (_this.hasClass('remove-blog-button'))
                    {
                        if (response.status.match(/^success/i)) {
                            $('div.blog-post-container[data-post-id=' + _this.attr('data-post-id') + ']').slideUp().empty().remove();
                        } else {
                            alert('Unable to remove post. Something went wrong.');
                        }
                        return;
                    }
                    
                    if (_this.hasClass('toggle-blog-button'))
                    {
                        if (response.data.post_status.match(/^published/i)) {
                            _this.removeClass('btn-default').addClass('btn-success').text('Published');
                        } else {
                            _this.removeClass('btn-success').addClass('btn-default').text('Unpublished');
                        }
                    }
                }
            });
        }
    });
    
    $('body').on('mouseenter mouseleave', '.blog-post-editable', function(e) {
        $(this)[e.type == 'mouseleave' ? 'removeClass' : 'addClass']('hover');
    });
    
    $('body').on('submit', '#random-post-generator-form', function (e) {
        // disable submit button
        $(':input[type=submit]', '#random-post-generator-form').attr('disabled', true);
    });
    
    var old_post_content_value = '';
    $('#blog-form').on('change keyup paste', 'textarea[name=post_content]', function(e) {
        var _this = $(this); // cache object
        var _thisform = $('#markdown-preview-area');
        var _target_form = $('#blog-form');
        
        // prevent multiple triggering
        var _currentval = _this.val();
        if (_currentval == old_post_content_value) {
            return; //check to prevent multiple simultaneous triggers
        }
        // store this value as old
        old_post_content_value = _currentval;
        
        $.ajax({
            method: "POST",
            url: _thisform.attr('data-url') + '?_=' + new Date().getTime(),
            dataType: "json",
            data: _target_form.serialize()
        })
        .done(function (response) {
            if (typeof response.status != 'undefined' && response.status.match(/^success/i)) {
                _thisform.html(response.message);
            }
        });
    });
    
    // init markdown render on editing
    $('textarea[name=post_content]', '#blog-form').change();
})();