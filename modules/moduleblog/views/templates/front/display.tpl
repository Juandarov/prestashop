<!-- Block moduleBlog -->
<div id="moduleBlog_block_left_blog" class="block">
    <h4>Welcome to this Blog!</h4>
    <div class="block_content">
        {foreach from=$posts item=post}
            <p><a href="{$post.link}">{$post.title}</a></p>
        {/foreach}
    </div>
</div>
<!-- /Block moduleBlog -->
