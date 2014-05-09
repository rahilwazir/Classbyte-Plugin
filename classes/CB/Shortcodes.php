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
                            <h4 class="panel-title">
                                <a href="#collapse<?php echo $course['coursetype'] ?>" data-parent="#accordion" data-toggle="collapse">
                                    <?php echo $course['coursename']; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $course['coursetype'] ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <h4><strong>Course Description</strong></h4>
                                <!-- repeat classes -->
                                <table width="100%"  border="1" class="classdatestable">
                                    <tr>
                                        <td><span class="nostyle">
                                                <a href="#">
                                                    <?php echo date("l, F d, Y",strtotime($course['coursedate']));?> at <?php echo date("g:i a",strtotime($course['coursetime']));?>
                                                </a>
                                                <div class="rightaligngreysmall"><?php echo $course['location']; ?></div>
                                        </span></td>
                                    </tr>
                                </table>
                                <!-- repeat classes -->
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