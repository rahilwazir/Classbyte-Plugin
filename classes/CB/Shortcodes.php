<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes extends Abstract_ClassByte
{
    public function __construct()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));
    }

    public function classListing($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'echo' => true
        ), $atts));

        API::post(API::$apiurls['courses']['listing']);
        API::jsonDecode();
        API::insertCourseClasses();
    ?>
        <div class="reg-page full_width col-md-12">
            <div class="sub_accordian" style="float: left; width: 100% ! important;">
                <div class="panel-group" id="accordion">
                    <?php
                    if (Posttypes::havePosts()) {
                        $courses = Posttypes::queryPosts();
                        foreach ($courses as $course) :
                            ?>
                        <!-- repeat certificates -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="#collapse<?php echo $course['category']['cat_id']; ?>" data-parent="#accordion" data-toggle="collapse">
                                        <?php echo $course['category']['cat_name'];; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $course['category']['cat_id'];; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (count($course['classes']) > 2) echo '<h4><strong>Course Description</strong></h4>';
                                    foreach($course['classes'] as $class) :
                                    ?>
                                    <!-- repeat classes -->
                                    <table width="100%"  border="1" class="classdatestable">
                                        <tr>
                                            <td>
                                                <div class="nostyle">
                                                    <a href="<?php echo $class['url']; ?>">
                                                        <?php echo $class['datetime']; ?>
                                                    </a>
                                                    <div class="rightaligngreysmall"><?php echo $class['location']; ?></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- repeat classes -->
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- repeat certificates -->
                        <?php
                        endforeach;
                    } else { ?>
                        <h3>You have no access to API</h3>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
}