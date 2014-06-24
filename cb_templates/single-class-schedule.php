<?php include_once('single-class/header.php'); ?>
<?php
    $map = false;
    if (isset($fcd['lat'], $fcd['lon'])
        && is_numeric($fcd['lat'])
        && is_numeric($fcd['lon'])
    ) {
        $map = true;
    }
?>
<div class="col-md-12">
    <form method="post" action="<?php echo get_permalink() . CB_ENDPOINT_REGISTER; ?>" id="cb_forms-only-ajax" name="cb_enroll_form">
        <div class="headline">
            <h3 <?php echo !$map ? 'class="text-center"' : ''; ?>>
                <strong style="font-size:20px; color:slategray;"><?php echo $fcd['agency']; ?></strong> -
                <?php echo $fcd['course']; ?> class in <?php echo $fcd['location']; ?>
            </h3>
        </div>
        <div class="row">
            <div class="col-md-6<?php echo !$map ? ' col-md-offset-3' : ''; ?>" id="course_information">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td><strong>Date/Time</strong></td>
                            <td><?php echo date('l, M j, Y', strtotime($fcd['coursedate'])) . ' ' . date('g:i a', strtotime($fcd['coursetime'])) . ' - ' . date('g:i a', strtotime($fcd['courseendtime'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Location</strong></td>
                            <td><p><?php echo $fcd['locationname']; ?></p>

                                <p><?php echo $fcd['address'] . ','; ?></p>

                                <p><?php echo $fcd['location'] . ', ' . $fcd['locationzip']; ?></p></td>
                        </tr>
                        <?php if ($fcd['notes']) { ?>
                            <tr>
                                <td><strong>Notes</strong></td>
                                <td>
                                    <?php echo $fcd['notes']; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ($fcd['coursecost']) { ?>
                            <tr>
                                <td><strong>Cost</strong></td>
                                <td><h4><?php echo '&dollar;' . $fcd['coursecost']; ?></h4></td>
                            </tr>
                        <?php } ?>
                        <tr id="action-enroll-btn">
                            <td></td>
                            <td>
                                <input type="submit" class="btn" value="ENROLL">
                                <input type="hidden" value="<?php echo wp_create_nonce($fcd['scheduledcoursesid']); ?>" name="course_token">
                                <input type="hidden" value="<?php echo $fcd['scheduledcoursesid']; ?>" name="course_id">
                                <input type="hidden" value="<?php echo wp_create_nonce($post->ID); ?>" name="class_token">
                                <input type="hidden" value="<?php echo $post->ID; ?>" name="class_id">
                                <input type="hidden" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>" name="_cb_nonce">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($map) { ?>
            <div class="col-md-6" id="course_geo_map">
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
                <script>
                    (function() {
                        var map;
                        function initialize() {
                            var cb_form_loading = document.getElementById('cb-form-loading');

                            var mapOptions = {
                                zoom: 12,
                                center: new google.maps.LatLng(<?php echo $fcd['lat'] . ', ' . $fcd['lon']?>)
                            };
                            map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);

                            cb_form_loading.parentNode.removeChild(cb_form_loading);
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
                    })();
                </script>
                <div id="map-canvas"><div id="cb-form-loading"></div></div>
            </div>
            <?php } ?>
        </div>
    </form>
</div>
<?php include_once('single-class/footer.php'); ?>