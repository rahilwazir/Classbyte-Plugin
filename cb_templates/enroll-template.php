<div class="col-md-12">
    <div class="headline">
        <h3>
            <strong style="font-size:20px; color:slategray;"><?php echo $fcd['agency']; ?></strong> -
            <?php echo $fcd['course']; ?> class in <?php echo $fcd['location']; ?>
        </h3>
    </div>
    <form method="post" name="cb_enroll_form" id="cb_forms-only-ajax">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td><strong>Date/Time</strong></td>
                    <td><?php echo date('l, M j, Y', strtotime($fcd['coursedate'])) . ' ' . date('g:i a', strtotime($fcd['coursetime'])) . ' - ' . date('g:i a', strtotime($fcd['courseendtime'])); ?></td>
                    <td rowspan="6">
                        <?php if ($fcd['lat'] && $fcd['lon']) { ?>
                            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
                            <script>
                                var map;
                                function initialize() {
                                    var mapOptions = {
                                        zoom: 12,
                                        center: new google.maps.LatLng(<?php echo $fcd['lat'] . ', ' . $fcd['lon']?>)
                                    };
                                    map = new google.maps.Map(document.getElementById('map-canvas'),
                                        mapOptions);
                                }
                                google.maps.event.addDomListener(window, 'load', initialize);
                            </script>
                            <div id="map-canvas"></div>
                        <?php } ?>
                    </td>
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
                        <input type="hidden" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>" name="_cb_nonce">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>