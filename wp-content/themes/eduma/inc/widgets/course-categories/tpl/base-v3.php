<?php

$show_count   = isset( $instance['list-options']['show_counts'] ) ? $instance['list-options']['show_counts'] : 0 ;
$hierarchical = isset( $instance['list-options']['hierarchical'] ) ? $instance['list-options']['hierarchical'] : true;
$taxonomy     = 'course_category';

$args_cat = array(
	'show_count'   => $show_count,
	'hierarchical' => $hierarchical,
	'taxonomy'     => $taxonomy,
    'parent' => 0,
	'title_li'     => '',
);
?>
<?php if ( $instance['title'] ) {
	echo ent2ncr($args['before_title'] . $instance['title'] . $args['after_title']);
} ?>

<?php
$cats = get_categories( $args_cat );

?>
<ul>
    <?php foreach( $cats as $category ) {?>
        <li>
            <a href="<?php echo esc_url( get_term_link( $category->term_id ) );?>"><?php echo $category->name;?></a>
            <?php
                if ( $instance['list-options']['show_counts'] == true ) { ?>
                    <span>(<?php echo $category->count;?>)</span>
                <?php } ?>
            <?php
            $args_cat_child = array(
                'show_count'   => $show_count,
                'hierarchical' => $hierarchical,
                'taxonomy'     => $taxonomy,
                'child_of' => $category->cat_ID,
                'title_li'     => '',
            );
            if(get_categories( $args_cat_child )) {
                echo '<ul>';
                wp_list_categories( $args_cat_child );
                echo '</ul>';
            }
            ?>
        </li>
    <?php }?>
</ul>
