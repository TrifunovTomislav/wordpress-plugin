<?php 

$args = [
    'post_type' => 'testimonial',
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'meta_query' => [
        [
            'key' => '_sparky_testimonial_key',
            'value' => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        ]
    ]
];

$query = new WP_Query($args);

if($query->have_posts()):
    $i = 1;

    echo '<div class="sparky-slider--wrapper">
            <div class="sparky-slider--container">
                <div class="sparky-slider--view">
                    <ul>';
    
                    while($query->have_posts()): $query->the_post();
                        $name = get_post_meta(get_the_ID(), '_sparky_testimonial_key', true)['name'] ?? '';
                        echo '<li class="sparky-slider--view-slides '
                                . ($i === 1 ? 'is-active' : '') .'">' 
                                . '<p class="testimonial-quote">' 
                                . get_the_content() 
                                . '</p><p class="testimonial-author">' 
                                .  $name
                                . '</p></li>';
                    $i++;
                    endwhile;
    
    echo            '</ul>
                </div>
                    <div class="sparky-slider--arrows">
                        <span class="arrow sparky-slider--arrows-left">&#x3c</span>
                        <span class="arrow sparky-slider--arrows-right">&#x3e</span>
                    </div>
            </div>
         </div>';
endif;

wp_reset_postdata();