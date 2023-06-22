<?php
// if (!function_exists('add_research_post')) {
    function add_research_post(){
        $user_id   = get_current_user_id();
		ob_start();
        if(pmpro_hasMembershipLevel('1', $user_id)){ ?>
            <section class="on_research_main">
                <div class="inner_main">
                    <div class="research-grid" id="research-grid-container">
                         <form id="research-form" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input class="form-control" type="text" name="title" id="title" required placeholder="Reaserch Title">
                            </div>

                            <div class="form-group">
                                <label for="content_area">Content:</label>
                                <?php 
                                $description = '';
                                    $settings = array(
                                        'media_buttons' => true,             // Enable media upload button
                                        'textarea_name' => 'content',
                                        'editor_class' => 'customwp_editor',
                                        'editor_height' => 300
                                    );

                                    wp_editor($description, 'content_area', $settings);

                                ?>
                            </div>
                             <div class="form-group">
                                <label for="attachment">Attachment:</label>
                                <input type="file" name="attachment" id="attachment" accept=".doc,.pdf,.mp4,.mov,.avi">
                            </div>
                            <button type="submit" class="btn btn-default btn_submit_post">Submit</button>
                        </form>
                    </div>
                </div>
            </section>
            <?php
        }
        return ob_get_clean(); // Return the buffered content
    }
    add_shortcode('add_research', 'add_research_post');
// }