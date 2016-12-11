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
})();