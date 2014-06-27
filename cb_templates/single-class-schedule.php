<?php include_once('single-class/header.php'); ?>
<?php
    $map = false;
    if (get_df_data($fcd['lat'])
        && get_df_data($fcd['lon'])
        && is_numeric($fcd['lat'])
        && is_numeric($fcd['lon'])
    ) {
        $map = true;
    }
?>
<div class="col-md-12">
    <form method="post" action="<?php echo get_permalink() . CB_ENDPOINT_REGISTER; ?>" id="cb_forms-only-ajax" name="cb_enroll_form">
        <div class="headline">
            <h3 class="text-center">
                <strong style="font-size:20px; color:slategray;"><?php echo get_df_data($fcd['agency']); ?></strong> -
                <?php echo $fcd['course']; ?> class in <?php echo get_df_data($fcd['location']); ?>
            </h3>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3" id="course_information">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td><strong>Date/Time</strong></td>
                            <td>
                                <p><?php format_course_date(get_df_data($fcd['coursedate']), get_df_data($fcd['coursetime']), get_df_data($fcd['courseendtime'])); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Location</strong></td>
                            <td><p><?php echo get_df_data($fcd['locationname']); ?></p>

                                <p><?php echo get_df_data($fcd['address']) . ','; ?></p>

                                <p><?php echo get_df_data($fcd['location']) . ', ' . get_df_data($fcd['locationzip']); ?></p></td>
                        </tr>
                        <?php if (get_df_data($fcd['notes'])) { ?>
                        <tr>
                            <td><strong>Notes</strong></td>
                            <td>
                                <?php echo $fcd['notes']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if (get_df_data($fcd['coursecost'])) { ?>
                        <tr>
                            <td><strong>Cost</strong></td>
                            <td><h4><?php echo '&dollar;' . get_df_data($fcd['coursecost']); ?></h4></td>
                        </tr>
                        <?php }
                            if (get_df_data($fcd['remainingseats']) >= 0) {
                                $remaining_seats = $fcd['remainingseats'];
                                if ($remaining_seats < 6 && $remaining_seats > 0) {
                                    $seats_need = "<strong style='font-size: 20px;'>{$remaining_seats}</strong> seats are available.";
                                } else if ($remaining_seats == 0) {
                                    $seats_need = "Class is <strong>FULL</strong><p>Contact us for further details.</p>";
                                }

                                if (get_df_data($seats_need, false)) {
                        ?>
                        <tr>
                            <td><strong>Seats</strong></td>
                            <td valign="middle"><?php echo get_df_data($seats_need, '', false); ?></td>
                        </tr>
                        <?php
                                }
                            }
                        ?>
                        <tr id="action-enroll-btn">
                            <td></td>
                            <td>
                                <input type="submit" class="btn" value="ENROLL">
                                <input type="hidden" value="<?php echo wp_create_nonce(get_df_data($fcd['scheduledcoursesid'])); ?>" name="course_token">
                                <input type="hidden" value="<?php echo get_df_data($fcd['scheduledcoursesid']); ?>" name="course_id">
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
            <div class="col-md-6 col-md-offset-3" id="course_geo_map">
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
                <script>
                    (function() {
                        var map;
                        function initialize() {
                            var cb_form_loading = document.getElementById('cb-form-loading'),
                                latLng = new google.maps.LatLng(<?php echo get_df_data($fcd['lat']) . ', ' . get_df_data($fcd['lon']); ?>);

                            var mapOptions = {
                                zoom: 12,
                                center: latLng
                            };
                            map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);

                            var marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                                title: "<?php echo get_df_data($fcd['locationname']); ?>"
                            });

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