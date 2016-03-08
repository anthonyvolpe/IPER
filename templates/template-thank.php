<?php
    //ini_set("display_errors",1);
    session_start(); get_header(); include_once('nav_bar.php'); call_the_style();//richiama la funzione per settare gli stili. vedi functions.php ?>


    <!--<nav class="navbar breadcrumb-navbar">
        <div class="container_">
            <ul class="list-inline">
                <li class="go-back">
                    <a href="<?php /*echo get_permalink($config['id_medical_order']); */?>" title="go back">Go Back</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_home']); */?>" title="Select Products"><span>1</span> Select Products</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_product']); */?>" title="Select Products"><span>2</span> Payment Plan</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_shipping']); */?>" title="Select Products"><span>3</span> Shipping & Billing</a>
                </li>
                <li>
                    <a href="<?php /*echo get_permalink($config['id_medical_order']); */?>" title="Thank You"><span>4</span> Thank You</a>
                </li>
                <li>
                    <span class="item"><span>5</span> Profile Set Up</span>
                </li>
            </ul>
        </div>
    </nav>-->
<?php
    function fix_dash($num){
        $number = $num;
        $number = str_replace("-","",$number); //se ci sono dash li rimuovo
        $number = str_split($number); //trasformo in array
        $number1 = array_slice($number, 0, 3);
        array_push($number1, '-');
        $number2 = array_slice($number, 3, 3);
        array_push($number2, '-');
        $number3 = array_slice($number, 6, 4);
        $number = array_merge($number1, $number2, $number3);
        $number = implode('', $number);
        return $number;
    }

    //profile data
    $Name_shipping = $_POST['firstName_shipping'].'%'.$_POST['lastName_shipping'];
    $birth_date = $_POST['profile_month'].'/'.$_POST['profile_day'].'/'.$_POST['profile_year'];
    $country_shipping = $_POST['country_shipping'];
    $address1_shipping = $_POST['address1_shipping'];
    $address2_shipping = $_POST['address2_shipping'];
    $cross_street = $_POST['cross_street'];
    $city_shipping = $_POST['city_shipping'];
    $state_shipping = $_POST['state_shipping'];
    $zip_shipping = $_POST['zip_shipping'];
    $email_shipping = $_POST['email_shipping'];
    $phone_shipping = $_POST['phone_shipping'];



    //Will Anyone Else Use This System*
    $name_two = $_POST['anotherperson_firstname'].'%'.$_POST['anotherperson_lastname'];
    $another_person_date_of_birth = $_POST['another_person_month'].'/'.$_POST['another_person_day'].'/'.$_POST['another_person_year'];
    $state_another_person_phone = $_POST['another_person_phone'];

    //first_emergency_contact
    $name_first_emergency_contact = $_POST['fname_emergency_contact'].'%'.$_POST['sname_emergency_contact'];
    $first_emergencycontact_relationship = $_POST['1_emergencycontact_relationship'];
    $phone_first_emergency_contact = $_POST['phone_first_emergency_contact'];
    /*$s_phone_first_emergency_contact = $_POST['s_phone_first_emergency_contact'];*/

    //sec_emergency_contact
    $name_second_emergency_contact = $_POST['fname_second_emergency_contact'].'%'.$_POST['sname_second_emergency_contact'];
    //$name_second_emergency_contact = ($_POST['fname_second_emergency_contact']) ? $_POST['fname_second_emergency_contact']($_POST['sname_second_emergency_contact']) ? ' '.$_POST['sname_second_emergency_contact'] :'' : '';
    $second_emergencycontact_relationship = $_POST['2_emergencycontact_relationship'];
    $phone_second_emergency_contact = $_POST['phone_second_emergency_contact'];

    //third_emergency_contact
    $fname_third_emergency_contact = $_POST['fname_third_emergency_contact'].'%'.$_POST['sname_third_emergency_contact'];
    //$fname_third_emergency_contact = ($_POST['fname_third_emergency_contact']) ? $_POST['fname_third_emergency_contact']($_POST['sname_third_emergency_contact']) ? ' '.$_POST['sname_third_emergency_contact'] :'' : '';
    $third_emergencycontact_relationship = $_POST['3_emergencycontact_relationship'];
    $phone_third_emergency_contact = $_POST['phone_third_emergency_contact'];

    $phone_shipping = fix_dash($phone_shipping);
    if($state_another_person_phone && isset($state_another_person_phone)){
    $state_another_person_phone = fix_dash($state_another_person_phone);}
    if($phone_first_emergency_contact && isset($phone_first_emergency_contact)){
    $phone_first_emergency_contact = fix_dash($phone_first_emergency_contact);}
    if($phone_second_emergency_contact && isset($phone_second_emergency_contact)){
    $phone_second_emergency_contact = fix_dash($phone_second_emergency_contact);}
    if($phone_third_emergency_contact && isset($phone_third_emergency_contact)){
    $phone_third_emergency_contact = fix_dash($phone_third_emergency_contact);}


    //EmergencyContact
    function createEmergencyContact($Name, $Phone, $Priority, $Relationship){
            $emergency_contact= array(
                "Name"=>$Name,
                "Phone"=>$Phone,
                "Priority"=>$Priority,
                "Relationship"=>$Relationship);

            return $emergency_contact;
        }

    $Emergency_contact[0] = createEmergencyContact($name_first_emergency_contact, $phone_first_emergency_contact, 1, $first_emergencycontact_relationship);

    if(isset($name_second_emergency_contact)  && rtrim($name_second_emergency_contact) != '' && rtrim($name_second_emergency_contact) != '%' && isset($phone_second_emergency_contact) && $phone_second_emergency_contact != ''){
    $Emergency_contact[1] = createEmergencyContact($name_second_emergency_contact, $phone_second_emergency_contact, 2, $second_emergencycontact_relationship);}else{unset($Emergency_contact[1]);}


    if(isset($fname_third_emergency_contact)  && rtrim($fname_third_emergency_contact) != '' && rtrim($fname_third_emergency_contact) != '%' && isset($phone_third_emergency_contact) && $phone_third_emergency_contact!=''){
    $Emergency_contact[2] = createEmergencyContact($fname_third_emergency_contact, $phone_third_emergency_contact, 3, $third_emergencycontact_relationship);}else{unset($Emergency_contact[2]);}

    /*echo 'name_second_emergency:'; print_r($name_second_emergency_contact);
    echo 'emergency_contact_array:'; print_r($Emergency_contact);*/


    //Address
    function createAddress($Name, $Phone, $Street1, $Street2, $City, $State, $PostalCode, $Country ){
            $address= array(
                "Name"=>$Name,
                "Phone"=>$Phone,
                "Street1"=>$Street1,
                "Street2"=>$Street2,
                "City"=>$City,
                "State"=>$State,
                "PostalCode"=>$PostalCode,
                "Country"=>$Country);

            return $address;
        }
    $Address = createAddress($Name_shipping, $phone_shipping, $address1_shipping, $address2_shipping, $city_shipping, $state_shipping, $zip_shipping, $country_shipping);

    //ProductServiceTo
    function createProductServiceTo($Email, $CrossStreet, $DateOfBirth, $Address, $Name2/*, $DateOfBirth2*/){

        if(rtrim($Name2)=='%'){
            $ProductServiceTo= array(
                "Email"=>$Email,
                "CrossStreet"=>$CrossStreet,
                "DateOfBirth"=>$DateOfBirth,
                "Address"=>$Address
                /*"DateOfBirth2:"=>$DateOfBirth2*/);

        }else{
            $ProductServiceTo= array(
                "Email"=>$Email,
                "CrossStreet"=>$CrossStreet,
                "DateOfBirth"=>$DateOfBirth,
                "Address"=>$Address,
                "Name2"=>$Name2,
                /*"DateOfBirth2:"=>$DateOfBirth2*/);
        }
            return $ProductServiceTo;
        }
    $Product_service = createProductServiceTo($email_shipping, $cross_street, $birth_date, $Address, $name_two/*, $another_person_date_of_birth*/);

    //Profile
    function createProfile($ServiceTo, $EmergencyContact){
        $Profile= array(
            "ServiceTo"=>$ServiceTo,
            "EmergencyContacts"=>$EmergencyContact);
        return $Profile;}

    $profile = createProfile($Product_service, $Emergency_contact);

    //RequestHeader
    function createRequestHeader(){

        $RequestHeader =  array(

            "RequestID" =>"Request_".time()

        );
        return $RequestHeader;

    }
    $RequestHeader = createRequestHeader();

    //ProfileRequest
    function createProfileRequest($RequestedHeader, $RequestBody, $OpportunityID){
        $ProfileRequest = array(
            "RequestHeader"=>$RequestedHeader,
            "RequestBody"=>$RequestBody,
            "OpportunityID"=>$OpportunityID

        );

        return $ProfileRequest;
    }
    if(!empty($_SESSION['Opportunity_ID_Profile_Page'])){
        $opportunity_id = $_SESSION['Opportunity_ID_Profile_Page'];
    }
    $opportunity_id = $_SESSION['Opportunity_ID_Profile_Page'];


$ProfileRequest= createProfileRequest($RequestHeader, $profile, $opportunity_id);
$sf=new IperSalseforceSync(IperSalseforceSync::kACTION_PROFILE_CREATE,$ProfileRequest);

    $res=json_decode(json_decode($sf->sendRequest()));
    if(isset($res->ResponseBody) && $res->ResponseBody !=''){
        $res_text =  'Success';
    }
    if(!isset($res->ResponseBody) || $res->ResponseBody ==''){
        $res_text = 'Failed';
    }
    /*if(!isset($res->ResponseBody) || $res->ResponseBody =''){
        $res=json_decode(json_decode($sf->sendRequest()));
    }*/

if($_SERVER["REMOTE_ADDR"]=='79.3.196.80'):
    ?>
<?php endif; ?>


    <span class="_result"><?php echo $res_text; ?></span>
    <button class="tmp_btn" data-target="#responses2" data-toggle="collapse" data-responses = "<?php echo $res_text; ?>">See the Response</button>
    <div id="responses2" class="collapse">
        <div class="req">
            <h2>Request</h2>
            <?php echo json_encode($ProfileRequest); ?>
        </div>
        <div class="res">
            <h2>Response</h2>
            <?php echo json_encode($res); ?>
        </div>
    </div>
    <div class="total-page">
        <div class="col-sm-12">
            <div class="super-title">
                <span class="thank-span">Thank you</span> for completing your profile. We are processing your information and a representative will contact you shortly.
                <?php echo '<span class="response_"></br>'.$opportunity_id.'</span>'; ?>
            </div>

        </div>
    </div>

<?php include($ABS_path . "/footer.php");
get_footer(); ?>