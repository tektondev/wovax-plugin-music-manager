<div id="wovax-mm-library-az-index" class="wovax-mm-library-section">
    <nav>
        <ul>
        <?php foreach( $alpha as $char ):?>
            <?php if( array_key_exists( $char , $alpha_posts ) ):?>
            <li><a href="#index-<?php echo $char;?>"><?php echo $char;?></a></li>
            <?php else:?>
            <li><?php echo $char;?></li>
            <?php endif;?>
        <?php endforeach;?>
        </ul>
    </nav>
    <?php echo $az_sections_html;?>
</div>