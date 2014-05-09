<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes extends Abstract_ClassByte
{
    public function __construct()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));
        $this->api = new API();
    }

    public function classListing($atts, $content = null)
    {
        extract(shortcode_atts(array(

        ), $atts));

        $courses = $this->api->post($this->api->apiurls['courses']['listing'])->jsonDecode();
        #var_dump($courses);

        ob_start();
    ?>
        <div class="reg-page full_width col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="sub_accordian" style="float: left; width: 100% ! important;">
                <div class="panel-group" id="accordion">
                    <?php
                    foreach ($courses as $course) :
                    ?>
                    <!-- repeat certificates -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php #if (count($course['classes']) > 0) {
                            #foreach ($course['classes'] as $class) : ?>
                                <h4 class="panel-title"><?php echo $course['certification_details']['certificate_type']; // . ' ' . $class['coursetypename']; ?></h4>
                            <?php #endforeach; } ?>
                        </div>

                        <div id="collapseID" class="panel-collapse collapse">
                            <div class="panel-body">
                                <h4><strong>Course Description</strong></h4>
                                <?php
                                if (count($course['classes']) > 0) {
                                    foreach ($course['classes'] as $class) :
                                ?>
                                <!-- repeat classes -->
                                <table width="100%"  border="1" class="classdatestable">
                                    <tr>
                                        <td><span class="nostyle">
                                                <a href="#">
                                                    <?php echo date("l, F d, Y",strtotime($class['coursedate']));?> at <?php echo date("g:i a",strtotime($class['coursetime']));?>
                                                </a>
                                                <div class="rightaligngreysmall"><?php echo $class['locationcity'] . ', ' . $class['locationstate']; ?></div>
                                        </span></td>
                                    </tr>
                                </table>
                                <!-- repeat classes -->
                                <?php
                                    endforeach;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- repeat certificates -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php
        $result = ob_get_clean();

        return $result;
    }

}