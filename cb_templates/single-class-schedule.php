<?php
namespace CB;
/**
 * Single Post Template file for Class Schedule post type
 */
get_header();
the_post();
$fcd = get_post_meta($post->ID, 'cb_course_full_object', true);

include_once('steps-template.php');
?>
<div id="cb-form-area" class="clearfix">
    <?php
    if (is_student_logged_in() && isset($_GET['action'])) {
        include_once('single-class-schedule-step3.php');
    } else {
        include_once('enroll-template.php');
    }
    ?>
</div>
<?php get_footer(); ?>