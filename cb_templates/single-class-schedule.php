<?php
/**
 * Single Post Template file for Class Schedule post type
 */
get_header(); the_post();

$fcd = get_post_meta($post->ID, 'cb_course_full_object', true);
?>
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="reg-page full_width">
                    <div style="width:100%;" class="span6">
                        <div class="headline">
                            <h4 style="border-bottom: medium none; display:none; margin-bottom: 15px; background-color: wheat; color: black; border-radius: 5px ! important; padding: 10px; text-shadow: 1px 1px 4px rgb(255, 255, 255);">BLS</h4>
                            <h3>
                                <strong style="font-size:20px; color:slategray;"><?php echo $fcd['agency']; ?></strong> -
                                <?php echo $fcd['course']; ?> class in <?php echo $fcd['location']; ?>
                            </h3>
                        </div>
                        <style>
                            .table th, .table td{ vertical-align:middle;}
                        </style>
                        <table width="100%" style=" margin-top:0px;" class="table table-striped">

                            <tbody>
                            <tr>
                                <td width="13%"><strong>Date/Time</strong></td>
                                <td width="">Thursday, May 15, 2014 5:00 pm - 10:00 pm </td>
                                <td width="100%" rowspan="6">
                                    <?php if ($fcd['lat'] && $fcd['lon']) { ?>
                                        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
                                        <div id="map-canvas"></div>
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
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Location</strong></td>
                                <td><p>Beverly Hills Satellite</p>
                                    <p>123 Main St, </p>
                                    <p>Beverly Hills, CA 90210 </p></td>
                            </tr>
                            <tr>
                                <td><strong>Notes</strong></td>
                                <td>
                                    <p>Jeremy Gruber client.</p>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Cost</strong></td>
                                <td><h4>$80.00</h4></td>
                            </tr>


                            <tr>
                                <td>&nbsp;</td>
                                <td>



                                    <a href="http://dev.classbyte.net/payment_for_BLS-Healthcare-Provider_May-15-2014_Beverly-Hills_CA_Class_200148_.html" class="btn-u btn-u-orange">ENROLL</a>

                                    <div style="display:none;">
                                        <div id="div_agreement">
                                            <div style="width:auto; padding:10px;" class="container">
                                                <div class="row-fluid privacy">

                                                    <div class="headline"><h3>Class Terms, Conditions &amp; Cancellation Policy</h3></div>

                                                    <p>My CPR Pros has earned a reputation for delivering high-quality, custom-tailored, medical emergency response training programs. BLS<strong>, ACLS, CPR, First Aid, Advanced First Aid, &amp; Medical Emergency Response Team training </strong>are just a small sample of what we can provide. We have some of the best instructors and subject experts in the medical emergency response training field.
                                                        <br><br>

                                                        We offer fast, convenient training options for individuals and onsite group training anytime, anywhere, NATIONWIDE.</p><br>

                                                    <div class="">

                                                        <div class="col-lg-6 text-left">
                                                            <a href="http://dev.classbyte.net/payment_for_BLS-Healthcare-Provider_May-15-2014_Beverly-Hills_CA_Class_200148_.html" class="btn-u btn-u-orange">I have read Class Terms, Conditions &amp; Cancellation Policy</a>

                                                        </div>
                                                    </div>

                                                </div><!--/row-fluid-->
                                            </div>                                        </div>
                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>