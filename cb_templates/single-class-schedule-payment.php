<?php include_once('single-class/header.php'); ?>
<div id="cb-form-area" class="clearfix">
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <form class="reg-page" id="cb_forms-only-ajax" method="post" name="cb_reg_form">
            <div style="width: 45%; float: left;">
                <input type="hidden" value="" name="paymentType">


                <div style="float:left; width:100%;" class="reg-header">
                    <h2 style="float:left; margin-right:20px;">Payment</h2>

                </div>

                <label>First Name <span class="color-red">*</span></label>

                <input type="text" class="span4" value="rahil" name="firstName">
                <label>Last Name <span class="color-red">*</span></label>
                <input type="text" class="span4" value="wazir" name="lastName">


                <label>Card Type <span class="color-red">*</span></label>
                <select onchange="javascript:generateCC(); return false;" class="span4" name="creditCardType">
                    <option selected="selected" value="">-- Select --</option>
                    <option value="Visa">Visa</option>
                    <option value="MasterCard">MasterCard</option>
                    <option value="Discover">Discover</option>
                    <option value="Amex">American Express</option>
                </select>
                <label>Card Number <img src="http://dev.classbyte.net/assets/img/Credit_Card_Icons.jpg"> <span
                        class="color-red">*</span></label>
                <input type="text" class="span4" value="" name="creditCardNumber">


                <label>Expiration Date <span class="color-red">*</span></label>
                <select style="width:155px;" class="span4" name="expDateMonth">
                    <option selected="selected" value="">-- Select --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select> <select style="width:210px;" class="span4" name="expDateYear">
                    <option selected="selected" value="">-- Select --</option>
                    <option value="2005">2005</option>
                    <option value="2006">2006</option>
                    <option value="2007">2007</option>
                    <option value="2008">2008</option>
                    <option value="2009">2009</option>
                    <option value="2010">2010</option>
                    <option value="2011">2011</option>
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>


                <label>Card Verification Number <span class="color-red">*</span></label>
                <input type="text" class="span4" value="" name="cvv2Number">
                <label>Address 1 <span class="color-red">*</span></label>
                <input type="text" class="span4" value="abc" name="address1">

                <label>City <span class="color-red">*</span></label>
                <input type="text" class="span4" value="city" name="city">
                <label>State <span class="color-red">*</span></label>
                <input type="text" class="span4" value="AK" name="state">
                <label>Zip <span class="color-red">*</span></label>
                <input type="text" class="span4" value="123432" name="zip">
                <label>Country <span class="color-red">*</span></label>
                <input type="text" disabled="" class="span4" value="US" name="country">

                <div style="display:none;">
                    <label>Amount <span class="color-red">*</span></label>
                    <input type="text" class="span4" value="12" name="amount"></div>

                <br><br>
                <button type="submit" class="btn-u btn-u-orange">Pay Now</button>
            </div>


            <div style="float: left; padding: 20px; width: 50%; box-shadow: 0px 0px 1px 1px rgb(204, 204, 204);"
                 class="s_p_a_n">
                <div class="headline">
                    <h3>
                        <strong style="font-size:14px; color:slategray;">

                            AHA
                        </strong><br>
                        CPR-AED-FA class in NY,
                        AL
                    </h3>
                </div>
                <style>
                    .table th, .table td {
                        vertical-align: middle;
                    }
                </style>
                <table width="100%" style=" margin-top:0px;" class="table table-striped">

                    <tbody>
                    <tr>
                        <td width="25%"><strong>Date/Time</strong></td>
                        <td width="">Monday, June 30, 2014 12:30 am - 12:30 am</td>
                        <td width="" rowspan="6">
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Location</strong></td>
                        <td><p>Test</p>

                            <p>NY, </p>

                            <p>NY, AL 8550 </p></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<?php include_once('single-class/footer.php'); ?>