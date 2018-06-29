<?php

  include( "mailjet-php/php-mailjet-v3-simple.class.php" );


  function smtpmailer($to, $from, $ccname, $subject, $body, $ishtml) {

//  get the login for the api

//  another testdir correction
    $handle = fopen( "../../.mailjet", "r" );
//    $handle = fopen( "../.mailjet", "r" );
    $buffer = fgets( $handle );
    $fields = explode( ':', $buffer );
    $apiKey = trim($fields[1]);
    $buffer = fgets( $handle );
    $fields = explode( ':', $buffer );
    $secretKey = trim($fields[1]);
    fclose( $handle );

    $mj = new Mailjet ( $apiKey, $secretKey );

    if ($ishtml == 'True')
    {
      if (strlen($ccname) < 1)
      {
        $params = array(
          "method" => "POST",
          "from" => $from,
          "to" => $to,
          "subject" => $subject,
          "html" => $body
        );
      }
      else
      {
        $params = array(
          "method" => "POST",
          "from" => $from,
          "to" => $to,
          "cc" => $ccname,
          "subject" => $subject,
          "html" => $body
        );
      }
    }

    if ($ishtml != 'True')
    {
      if (strlen($ccname) < 1)
      {
        $params = array(
          "method" => "POST",
          "from" => $from,
          "to" => $to,
          "subject" => $subject,
          "text" => $body
        );
      }
      else
      {
        $params = array(
          "method" => "POST",
          "from" => $from,
          "to" => $to,
          "cc" => $ccname,
          "subject" => $subject,
          "text" => $body
        );
      }
    }

    $result = $mj->sendEmail($params);

    if ($mj->_response_code == 200)
      return TRUE;
    else
      return FALSE;

  }

// functions to check input strings and email

  function goodchars($field)
  {
    if (preg_match('/[^A-Za-z0-9. \'\-]/', $field))
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  function isfilled($field)
  {
    if (strlen($field) < 1)
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  function spamcheck($field)
  {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

// generate the volunteer form, then check input before posting

  $warn_line       = '';
  $error           = FALSE;
  $first_time      = TRUE;
  $VAsubmit        = 0;

  $first_name_line = '';
  $last_name_line  = '';
  $name_line       = '';
  $street_line     = '';
  $city_line       = '';
  $state_line      = '';
  $zip_line        = '';
  $tel_line        = '';
  $email_line      = '';
  $post_check      = '';
  $email_check     = '';
  $yage_check      = '';
  $nage_check      = '';
  $col_food_check  = '';
  $org_food_check  = '';
  $pkup_food_check = '';
  $unl_food_check  = '';
  $sort_food_check = '';
  $raz_mon_check   = '';
  $dist_food_check = '';
  $tech_supp_check = '';
  $cleric_supp_check = '';
  $other_line      = '';
  $lang_line       = '';
  $car_line        = '';
  $how_line        = '';
  $proj_line       = '';
  $com_line        = '';
  $aLanguages      = '';

  if (isset($_SESSION['VAsubmit']))
  {
    $VAsubmit        = 1;
  }

  if (isset($_POST['submit']))
  {

    $first_time      = FALSE;
    $number_checked  = 0;
    $first_name_line = str_replace("\'","'",htmlentities($_REQUEST['first_name_field']));
    $last_name_line  = str_replace("\'","'",htmlentities($_REQUEST['last_name_field']));
    $name_line       = $first_name_line . ' ' . $last_name_line;
    $street_line     = str_replace("\'","'",htmlentities($_REQUEST['st_add']));
    $city_line       = str_replace("\'","'",htmlentities($_REQUEST['city']));
    $state_line      = $_POST['state'];
    $zip_line        = str_replace("\'","'",htmlentities($_REQUEST['zip']));
    $tel_line        = str_replace("\'","'",htmlentities($_REQUEST['tel']));
    $email_line      = str_replace("\'","'",htmlentities($_REQUEST['email_add']));
    $post_check      = '';
    $email_check     = '';
    $which_one       = $_POST['pref_radio_group'];
    if ($which_one == 'postal_p')
    {
      $post_check    = 'checked';
    }
    else if ($which_one == 'email_p')
    {
      $email_check   = 'checked';
    }
    $which_one       = $_POST['age_radio_group'];
    if ($which_one == 'y18_p')
    {
      $yage_check    = 'checked';
      $nage_check    = '';
    }
    else if ($which_one == 'n18_p')
    {
      $yage_check   = '';
      $nage_check   = 'checked';
    }
    $col_food_check  = '';
    if (isset($_POST['col_food']))
    {
      $col_food_check = 'checked';
      $number_checked++;
    }
    $org_food_check  = '';
    if (isset($_POST['org_food']))
    {
      $org_food_check = 'checked';
      $number_checked++;
    }
    $pkup_food_check = '';
    if (isset($_POST['pkup_food']))
    {
      $pkup_food_check = 'checked';
      $number_checked++;
    }
    $unl_food_check  = '';
    if (isset($_POST['unload_food']))
    {
      $unl_food_check = 'checked';
      $number_checked++;
    }
    $sort_food_check = '';
    if (isset($_POST['sort_food']))
    {
      $sort_food_check = 'checked';
      $number_checked++;
    }
    $raz_mon_check   = '';
    if (isset($_POST['raise_money']))
    {
      $raz_mon_check = 'checked';
      $number_checked++;
    }
    $dist_food_check = '';
    if (isset($_POST['dist_food']))
    {
      $dist_food_check = 'checked';
      $number_checked++;
    }
    $tech_supp_check = '';
    if (isset($_POST['tech_supp']))
    {
      $tech_supp_check = 'checked';
      $number_checked++;
    }
    $cleric_supp_check = '';
    if (isset($_POST['cleric_supp']))
    {
      $cleric_supp_check = 'checked';
      $number_checked++;
    }

    $other_line      = str_replace("\'","'",htmlentities($_REQUEST['other_serv']));
    if (isfilled($other_line))
    {
      $number_checked++;
    }

    $lang_line       = '';
    if (isset($_POST['allLanguages']))
    {
      $aLanguages = $_POST['allLanguages'];
      $nLanguages = count($aLanguages);

      for ($i=0; $i < $nLanguages; $i++ )
      {
        $lang_line = $lang_line . $aLanguages[$i] . '; ';
      }
    }

    $car_line        = str_replace("\'","'",htmlentities($_POST['car_type']));

    $how_line = $_POST['how_hear'];

    $proj_line       = str_replace("\'","'",htmlentities($_REQUEST['is_proj']));
    $com_line        = str_replace("\'","'",htmlentities($_POST['add_comm']));
  
    if (!isfilled($name_line))
    {
      $warn_line = 'You must supply a valid Name!';
      $error     = TRUE;
    }
    else if (!isfilled($street_line))
    {
      $warn_line = 'You must supply a valid street address!';
      $error     = TRUE;
    }
    else if (!isfilled($city_line))
    {
      $warn_line = 'You must supply a valid city!';
      $error     = TRUE;
    }
    else if (!isfilled($tel_line))
    {
      $warn_line = 'Please supply a phone number so we can contact you!';
      $error     = TRUE;
    }
    else if (($email_check == 'checked') && (!isfilled($email_line)))
    {
      $warn_line = 'You must supply an email address if email is the preferred mail method!';
      $error     = TRUE;
    }
    else if ((!spamcheck($email_line)) && (isfilled($email_line)))
    {
      $warn_line = 'Please supply a valid email address!';
      $error     = TRUE;
    }
    else if (($yage_check == '') && ($nage_check == ''))
    {
      $warn_line = 'Please indicate whether you are under 18 years of age!';
      $error     = TRUE;
    }
    else if ($number_checked < 1)
    {
      $warn_line = 'You have not volunteered to do anything!';
      $error     = TRUE;
    }

  }

  if ($first_time || $error)
  {

    echo "<div class='row'>";
    echo "<div class='col-sm-10 col-sm-offset-1' style='text-align:center;'>";

    if ($warn_line != '')
    {
      echo "<button type='button' class='btn btn-danger'>$warn_line</button>";
    }
    echo "
      <br>
      <p>
        <b>West Valley Food Pantry</b><br>
        At Prince of Peace Episcopal Church<br>
        5700 Rudnick Ave., Woodland Hills, CA 91367 &mdash; (818) 346-5554<br>
        http://www.westvalleyfoodpantry.org<br><br>
        <b>Volunteer Application</b><br>
        Required fields are marked with an asterisk (*)<br>
        <br><br>
      </p>
    ";
    echo "</div></div>";

    echo "
      <form action='./vol-app-form-page.html' method='post' enctype='multipart/form-data'>
      <div class='form-group'>
      ";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>
              <label for='first_name_field'>
                First Name*:
              </label>
            </div>
            <div class='col-sm-6'>
      ";
    if ($first_name_line == "")
    {
      echo "<input type='text' name='first_name_field' id='first_name_field' class='form-control input-sm'
             placeholder='First Name*'>";
    }
    else
    {
      echo "<input type='text' name='first_name_field' id='first_name_field' class='form-control input-sm'
             value='$first_name_line'>";
    }
    echo "<br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>
              <label for='last_name_field'>
                Last Name*:
              </label>
            </div>
            <div class='col-sm-6'>
      ";
    if ($last_name_line == "")
    {
      echo "<input type='text' name='last_name_field' id='last_name_field' class='form-control input-sm'
             placeholder='Last Name*'>";
    }
    else
    {
      echo "<input type='text' name='last_name_field' id='last_name_field' class='form-control input-sm'
             value='$last_name_line'>";
    }
    echo "<br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>
              <label for='st_add'>
                Mailing Address*:
              </label>
            </div>
            <div class='col-sm-6'>
      ";
    if ($street_line == "")
    {
      echo "<input type='text' name='st_add' id='st_add' class='form-control input-sm' placeholder='Street Address*'>";
    }
    else
    {
      echo "<input type='text' name='st_add' id='st_add' class='form-control input-sm' value='$street_line'>";
    }
    echo "<br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>

            </div>
            <div class='col-sm-4'>
      ";
    if ($city_line == "")
    {
      echo "<input type='text' name='city' id='city' class='form-control input-sm' placeholder='City*'>";
    }
    else
    {
      echo "<input type='text' name='city' id='city' class='form-control input-sm' value='$city_line'>";
    }
    echo "
      <br>
      </div>
      <div class='col-sm-1'>
      <select  size='1' name='state' style='height:30px;'>
        <option value='AL'>AL</option>
        <option value='AK'>AK</option>
        <option value='AZ'>AZ</option>
        <option value='AR'>AR</option>
        <option value='CA' selected='selected'>CA</option>
        <option value='CO'>CO</option>
        <option value='CT'>CT</option>
        <option value='DE'>DE</option>
        <option value='DC'>DC</option>
        <option value='FL'>FL</option>
        <option value='GA'>GA</option>
        <option value='HI'>HI</option>
        <option value='ID'>ID</option>
        <option value='IL'>IL</option>
        <option value='IN'>IN</option>
        <option value='IA'>IA</option>
        <option value='KS'>KS</option>
        <option value='KY'>KY</option>
        <option value='LA'>LA</option>
        <option value='ME'>ME</option>
        <option value='MD'>MD</option>
        <option value='MA'>MA</option>
        <option value='MI'>MI</option>
        <option value='MN'>MN</option>
        <option value='MS'>MS</option>
        <option value='MO'>MO</option>
        <option value='MT'>MT</option>
        <option value='NE'>NE</option>
        <option value='NV'>NV</option>
        <option value='NH'>NH</option>
        <option value='NJ'>NJ</option>
        <option value='NM'>NM</option>
        <option value='NY'>NY</option>
        <option value='NC'>NC</option>
        <option value='ND'>ND</option>
        <option value='OH'>OH</option>
        <option value='OK'>OK</option>
        <option value='OR'>OR</option>
        <option value='PA'>PA</option>
        <option value='RI'>RI</option>
        <option value='SC'>SC</option>
        <option value='SD'>SD</option>
        <option value='TN'>TN</option>
        <option value='TX'>TX</option>
        <option value='UT'>UT</option>
        <option value='VT'>VT</option>
        <option value='VA'>VA</option>
        <option value='WA'>WA</option>
        <option value='WV'>WV</option>
        <option value='WI'>WI</option>
        <option value='WY'>WY</option>
      </select>
      <br>&nbsp;</div>
      <div class='col-sm-2'>
      ";
    if ($zip_line == "")
    {
      echo "<input type='text' name='zip' id='zip' class='form-control input-sm' placeholder='Zip*'>";
    }
    else
    {
      echo "<input type='text' name='zip' id='zip' class='form-control input-sm' value='$zip_line'>";
    }
    echo "<br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>
              <label for='tel'>
                Telephone*:
              </label>
            </div>
            <div class='col-sm-2'>
      ";
    if ($tel_line == "")
    {
      echo "<input type='text' name='tel' id='tel' class='form-control input-sm' placeholder='Telephone*'>";
    }
    else
    {
      echo "<input type='text' name='tel' id='tel' class='form-control input-sm' value='$tel_line'>";
    }
    echo "<br></div>
            <div class='col-sm-1'>
              <label for='email_add'>
                E-mail*:
              </label>
            </div>
            <div class='col-sm-4'>
         ";
    if ($email_line == "")
    {
      echo "<input type='text' name='email_add' id='email_add' class='form-control input-sm' placeholder='Email*'>";
    }
    else
    {
      echo "<input type='text' name='email_add' id='email_add' class='form-control input-sm' value='$email_line'>";
    }
    echo "<br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-5 col-sm-offset-1' style='text-align:left;'>
              <b>Preferred mail method:</b><br>
            </div>
            <div class='col-sm-5'>
      ";
    echo "
          <label class='radio-inline'>
            <input type='radio' id='postal_p' value='postal_p' name='pref_radio_group' {$post_check} />
            Postal Delivery
          </label>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <label class='radio-inline'>
          <input type='radio' id='email_p' value='email_p' name='pref_radio_group' {$email_check} />
            Email
          </label>
         ";
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-5 col-sm-offset-1' style='text-align:left;'>
              <b>Are you under 18 years of age and/or attending school? *</b>
            </div>
            <div class='col-sm-4'>
      ";
    echo "
          <label class='radio-inline'>
          <input type='radio' id='y18_p' value='y18_p' name='age_radio_group' {$yage_check} />
            Yes
          </label>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <label class='radio-inline'>
          <input type='radio' id='n18_p' value='n18_p' name='age_radio_group' {$nage_check} />
            No
          </label>
         ";
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-5 col-sm-offset-1' style='text-align:left;'>
              <b>Select all types of service that interest you:</b><br><br>
            </div>
          </div>
      ";
    echo "<div class='row'>
            <div class='col-sm-1 col-sm-offset-1' style='text-align:left;'>
            </div>
            <div class='col-sm-6'>
      ";
    echo "
         <label class='checkbox-inline'>
         <input type='checkbox' id='col_food' value='col_food' name='col_food' {$col_food_check} />
         Collect food donated by supermarket shoppers, two-hour shifts: 10 am to 4 pm on the first
         and second Saturday each month.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='org_food' value='org_food' name='org_food' {$org_food_check} />
         Organize a food drive in a congregation, workplace, school, civic group, or neighborhood.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='pkup_food' value='pkup_food' name='pkup_food' {$pkup_food_check} />
         Pick up food from a market or donor drive, deliver to Pantry.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='unload_food' value='unload_food' name='unload_food' {$unl_food_check} />
         Unload food at Pantry.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='sort_food' value='sort_food' name='sort_food' {$sort_food_check} />
         Sort food at Pantry.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='raise_money' value='raise_money' name='raise_money' {$raz_mon_check} />
         Raise money to buy food.
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='dist_food' value='dist_food' name='dist_food' {$dist_food_check} />
         Distribute food at Pantry (requires training, shift availability limited):
         Monday thru Thursday &mdash; morning &amp; afternoon shifts; Friday &mdash;
         morning shift only. (Training opportunities limited during summer due to influx of summer volunteers.)
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='tech_supp' value='tech_supp' name='tech_supp' {$tech_supp_check} />
         Provide technical help (data entry, web site, social media, etc.).
         </label><br>
         <label class='checkbox-inline'>
         <input type='checkbox' id='cleric_supp' value='cleric_supp' name='cleric_supp' {$cleric_supp_check} />
         Provide clerical help (thank you notes, phone calls, mailing, etc.).
         </label><br>
         ";
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-2 col-sm-offset-1' style='text-align:left;'>
              <label for='other_serv'>
                Other service(s) you could offer:
              </label>
            </div>
            <div class='col-sm-6'>
      ";
    if ($other_line == "")
    {
      echo "<input type='text' name='other_serv' id='other_serv' class='form-control input-sm'
             placeholder='Other Service'>";
    }
    else
    {
      echo "<input type='text' name='other_serv' id='other_serv' class='form-control input-sm'
             value='$other_line'>";
    }
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-8 col-sm-offset-1' style='text-align:left;'>
              <h4>Of interest to us:</h4>
              <label for='allLanguages[]'>
              Do you speak a(any) language(s) other than English?
              Please select all that apply<br> (Select multiple lines by holding down
              the Ctrl key while you click).<br>(<i>If you know one that is not listed,
              select Other and tell us about it in the comments.</i>)
            </label>
            <br><br>
            <select multiple='multiple' name='allLanguages[]' class='form-control'>
              <option value='Fluent Spanish'>Fluent Spanish</option>
              <option value='Some Spanish'>Some Spanish</option>
              <option value='Fluent Afrikaans'>Fluent Afrikaans</option>
              <option value='Some Afrikaans'>Some Afrikaans</option>
              <option value='Fluent Armenian'>Fluent Armenian</option>
              <option value='Some Armenian'>Some Armenian</option>
              <option value='Fluent Chinese'>Fluent Chinese</option>
              <option value='Some Chinese'>Some Chinese</option>
              <option value='Fluent Dari'>Fluent Dari</option>
              <option value='Some Dari'>Some Dari</option>
              <option value='Fluent Farsi'>Fluent Farsi</option>
              <option value='Some Farsi'>Some Farsi</option>
              <option value='Fluent Flemish'>Fluent Flemish</option>
              <option value='Some Flemish'>Some Flemish</option>
              <option value='Fluent French'>Fluent French</option>
              <option value='Some French'>Some French</option>
              <option value='Fluent German'>Fluent German</option>
              <option value='Some German'>Some German</option>
              <option value='Fluent Gujurati'>Fluent Gujurati</option>
              <option value='Some Gujurati'>Some Gujurati</option>
              <option value='Fluent Hebrew'>Fluent Hebrew</option>
              <option value='Some Hebrew'>Some Hebrew</option>
              <option value='Fluent Hindi'>Fluent Hindi</option>
              <option value='Some Hindi'>Some Hindi</option>
              <option value='Fluent Italian'>Fluent Italian</option>
              <option value='Some Italian'>Some Italian</option>
              <option value='Fluent Japanese'>Fluent Japanese</option>
              <option value='Some Japanese'>Some Japanese</option>
              <option value='Fluent Korean'>Fluent Korean</option>
              <option value='Some Korean'>Some Korean</option>
              <option value='Fluent Portuguese'>Fluent Portuguese</option>
              <option value='Some Portuguese'>Some Portuguese</option>
              <option value='Fluent Russian'>Fluent Russian</option>
              <option value='Some Russian'>Some Russian</option>
              <option value='Fluent Swedish'>Fluent Swedish</option>
              <option value='Some Swedish'>Some Swedish</option>
              <option value='Fluent Tagalog'>Fluent Tagalog</option>
              <option value='Some Tagalog'>Some Tagalog</option>
              <option value='Fluent Telugu'>Fluent Telugu</option>
              <option value='Some Telugu'>Some Telugu</option>
              <option value='Fluent Urdu'>Fluent Urdu</option>
              <option value='Some Urdu'>Some Urdu</option>
              <option value='Fluent Vietnamese'>Fluent Vietnamese</option>
              <option value='Some Vietnamese'>Some Vietnamese</option>
              <option value='Other'>Other</option>
            </select>
            <br><br>
            </div>
          </div>
      ";

    echo "<div class='row'>
            <div class='col-sm-3 col-sm-offset-1' style='text-align:left;'>
              <label for='car_type'>
                Type of vehicle <i>(if interested in collecting or picking up food)</i>:
              </label>
            </div>
            <div class='col-sm-5'>
      ";
    if ($car_line == "")
    {
      echo "<input type='text' name='car_type' id='car_type' class='form-control input-sm'
             placeholder='Type of Car'>";
    }
    else
    {
      echo "<input type='text' name='car_type' id='car_type' class='form-control input-sm'
             value='$car_line'>";
    }
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-10 col-sm-offset-1' style='text-align:left;'>
            How did you hear about us?<br>
            (<i>If you feel the choices too limiting, please explain in the comments.</i>)<br><br>
            </div>
          </div>
      ";

    echo "<div class='row'>
            <div class='col-sm-1 col-sm-offset-1' style='text-align:left;'>
            </div>
            <div class='col-sm-6'>
      ";
    echo "
            <select name='how_hear' class='form-control'>
              <option value=''>Select ...</option>
              <option value='Website'>Website</option>
              <option value='Internet'>The Internet</option>
              <option value='Newspaper'>Newspaper</option>
              <option value='OtherMedia'>Other Media</option>
              <option value='PantryVisit'>A Pantry Visit</option>
              <option value='ReligiousOrg'>Through a Religious Organization</option>
              <option value='School'>Your School</option>
              <option value='OtherOrg'>Through Some Other Organization</option>
              <option value='PersonalRef'>Through a Friend or Personal Reference</option>
            </select>
            <br><br>
          </div>
        </div>
        ";

    echo "<div class='row'>
            <div class='col-sm-3 col-sm-offset-1' style='text-align:left;'>
              <label for='is_proj'>
                This is a service project for (school/organization)&nbsp;
              </label>
            </div>
            <div class='col-sm-5'>
      ";
    if ($proj_line == "")
    {
      echo "<input type='text' name='is_proj' id='is_proj' class='form-control input-sm'
             placeholder='Name of organization/Type of project'>";
    }
    else
    {
      echo "<input type='text' name='is_proj' id='is_proj' class='form-control input-sm'
             value='$proj_line'>";
    }
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-3 col-sm-offset-1' style='text-align:left;'>
              <label for='add_comm'>
                Additional Comments (please limit to 255 characters):
              </label>
            </div>
            <div class='col-sm-5'>
      ";
    if ($com_line == "")
    {
      echo "<textarea id='add_comm' name='add_comm' class='form-control input-sm' rows='4'
             placeholder='Additional Comments' maxlength='255'></textarea>";
    }
    else
    {
      echo "<textarea id='add_comm' name='add_comm' class='form-control input-sm' rows='4' maxlength='255'>
             {$com_line}
            </textarea>";
    }
    echo "<br><br></div></div>";

    echo "<div class='row'>
            <div class='col-sm-8 col-sm-offset-2' style='text-align:center;'>
              <input type='submit' name='submit' value='Submit Form' />
            </div>
          </div>
        </div>
        </form>
      ";
    echo "<br>";

  }
  else
  {
//  add file to access the database

    if ($VAsubmit == 0)
    {
//    for testdir, when move put back to normal
      require_once("../../include/initialize.php");
//      require_once("../include/initialize.php");

      require("./include/add-vol-app-to-db.php");

      $volApplicant = new VolApplicant();

      $volApplicant->InitDB( $hostname, $username, $password, $dbname );
      $volApplicant->Initialize();

      $message = "This message comes from the Food Pantry, not the applicant\n";
      $message = $message . "DO NOT REPLY to this message\n";
      $message = $message . "\n===================================================\n";
      $message = $message . "\n    West Valley Food Pantry\n";
      $message = $message . "\n    VOLUNTEER APPLICATION\n\n";
      date_default_timezone_set('America/Los_Angeles');
      $date    = date('Y-m-d h:i a');
      $message = $message . "Submitted on {$date}\n\n";
      $csvline = "$date,";
      $volApplicant->SetSubmittalDate($date);
      $message = $message . "NAME:                   {$name_line}\n";
      $first_name_line = str_replace(",",";",$first_name_line);
      $last_name_line = str_replace(",",";",$last_name_line);
      $csvline = $csvline . trim($first_name_line) . ",";
      $csvline = $csvline . trim($last_name_line) . ",";
      $volApplicant->SetName(trim($first_name_line), trim($last_name_line));

      $tel_line = str_replace(",",";",$tel_line);
      $tel_line2 = $tel_line;
      if (!isfilled($tel_line))
      {
        $tel_line2 = '- none given -';
      }
      $message = $message . "PHONE:                  {$tel_line2}\n";
      $csvline = $csvline . trim($tel_line) . ",";
      $volApplicant->SetPhone(trim($tel_line));
      $street_line = str_replace(",",";",$street_line);
      $message = $message . "MAILING ADDRESS:        {$street_line}\n";
      $csvline = $csvline . trim($street_line) . ",";

      $city_line = str_replace(",",";",$city_line);
      $zip_line = str_replace(",",";",$zip_line);
      $zip_line2 = $zip_line;
      if (!isfilled($zip_line))
      {
        $zip_line2 = '- none given -';
      }
      $message = $message . "CITY: {$city_line}  STATE: {$state_line}    ZIP: {$zip_line2}\n";
      $csvline = $csvline . trim($city_line) . ",";
      $csvline = $csvline . trim($state_line) . ",";
      $csvline = $csvline . trim($zip_line) . ",";
      $volApplicant->SetAddress(trim($street_line), trim($city_line), trim($state_line), trim($zip_line));
      if (isfilled($email_line))
      {
        $email_line = str_replace(",",";",$email_line);
        $message = $message . "E-MAIL ADDRESS:         {$email_line}\n\n";
        $csvline = $csvline . trim($email_line) . ",";
        $volApplicant->SetEmail(trim($email_line));
      }
      else
      {
        $message = $message . "E-MAIL ADDRESS:         - none given - \n\n";
        $csvline = $csvline . " ,";
      }
      if ($post_check == 'checked')
      {
        $message = $message . "PREFERRED MAIL METHOD: Postal delivery \n\n";
        $csvline = $csvline . "Post,";
        $volApplicant->SetContactPreference("Post");
      }
      else
      {
        $message = $message . "PREFERRED MAIL METHOD: E-mail \n\n";
        $csvline = $csvline . "E-mail,";
        $volApplicant->SetContactPreference("E-mail");
      }
      if ($yage_check == 'checked')
      {
        $message = $message . "APPLICANT IS UNDER 18 AND/OR ATTENDING SCHOOL! \n\n";
      }
      $message = $message . "I AM INTERESTED IN THE FOLLOWING:\n\n";

      if ($col_food_check == 'checked')
      {
        $message = $message . "    Collect food donated by supermarket shoppers.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetCollectFood();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($org_food_check == 'checked')
      {
        $message = $message . "    Organize a food drive in a congregation, workplace, school, civic group or neighborhood.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetOrganizeDrive();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($pkup_food_check == 'checked')
      {
        $message = $message . "    Pick up food from market or donor drive, deliver to Pantry.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetDeliverFood();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($unl_food_check == 'checked')
      {
        $message = $message . "    Unload food at the Pantry.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetUnloadFood();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($sort_food_check == 'checked')
      {
        $message = $message . "    Sort food at the Pantry.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetSortFood();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($raz_mon_check == 'checked')
      {
        $message = $message . "    Raise money to buy food for Pantry.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetRaiseMoney();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($dist_food_check == 'checked')
      {
        $message = $message . "    Distribute food at Pantry.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetDistributeFood();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($tech_supp_check == 'checked')
      {
        $message = $message . "    Provide technical help.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetTechnicalAid();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      if ($cleric_supp_check == 'checked')
      {
        $message = $message . "    Provide clerical help.\n"; 
        $csvline = $csvline . "X,";
        $volApplicant->SetClericalAid();
      }
      else
      {
        $csvline = $csvline . ",";
      }

      $other_line2 = $other_line;
      if (!isfilled($other_line))
      {
        $other_line2 = '- no entry -';
      }
      $message = $message . "\nOTHER SERVICE: {$other_line2}\n\n";
      $other_line = str_replace(",",";",$other_line);
      $csvline = $csvline . trim($other_line) . ",";
      $volApplicant->SetOther( trim($other_line) );

      $lang_line2 = $lang_line . '- end of entries -';
      $message = $message . "LANGUAGES OTHER THAN ENGLISH: {$lang_line2}\n\n";
      $lang_line = str_replace(",",";",$lang_line);
      $csvline = $csvline . trim($lang_line) . ",";
      $volApplicant->SetLanguage(trim($lang_line));

      $car_line  = str_replace(",",";",$car_line);
      $car_line2 = $car_line;
      if (!isfilled($car_line))
      {
        $car_line2 = '- no entry -';
      }
      $message = $message . "TYPE OF VEHICLE: {$car_line2}\n\n";

      $how_line2 = $how_line;
      if (!isfilled($how_line))
      {
        $how_line2 = '- no entry -';
      }
      $message = $message . "HOW DID YOU HEAR ABOUT US? {$how_line2}\n\n";
      $how_line = str_replace(",",";",$how_line);
      $csvline  = $csvline . trim($how_line) . ",";
      $volApplicant->SetHeardAbout(trim($how_line));

      $proj_line2 = $proj_line;
      if (!isfilled($proj_line))
      {
        $proj_line2 = '- no entry -';
      }
      $message = $message . "THIS IS A SERVICE PROJECT FOR {$proj_line2}\n\n";
      $proj_line = str_replace(",",";",$proj_line);
      $csvline  = $csvline . trim($proj_line) . ",";
      $volApplicant->SetServiceProject(trim($proj_line));

      $com_line = str_replace("&amp;","&",$com_line);
      $com_line = str_replace("&lt;","<",$com_line);
      $com_line = str_replace("&gt;",">",$com_line);
      $com_line = str_replace('\&quot;','"',$com_line);
      $com_line = str_replace(",",";",$com_line);
      $com_line2 = $com_line . '- end of entries -';
      $message = $message . "ADDITIONAL COMMENTS: {$com_line2}\n\n";
      $csvline = $csvline . trim($com_line);
      $volApplicant->SetComments(trim($com_line));
      $message = $message . "\n--End of Application Form--\n";
      $message = $message . "\n===================================================\n";

      if (!isfilled($car_line))
      {
        $csvline = $csvline . ",";
      }
      else
      {
        $csvline = $csvline . "," . trim($car_line); 
        $volApplicant->SetVehicle(trim($car_line));
      }

//      $to = "buesings@aol.com";
      $ishtml = 'False';
      $from = "wvfoodpantry@westvalleyfoodpantry.org";
      $subject = "Food Pantry Volunteer Application";
/*
      if (isfilled($email_line))
      {
        $headers ="From:$email_line\r\nReply-To:$email_line";
      }
      else
      {
        $headers ="From:wvfoodpantry@gmail.com\r\nReply-To:wvfoodpantry@gmail.com";
      }
*/
      $ccname = "";

      $headers ="From:wvfoodpantry@westvalleyfoodpantry.org\r\nReply-To:wvfoodpantry@westvalleyfoodpantry.org";
/*
      $mail_sent = mail($to,$subject,$message,$headers);
*/

//    for testing comment this block

      if ($dist_food_check == 'checked')
      {
        $to = "buesings20701@gmail.com";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }
      if ($col_food_check == 'checked')
      {
        $to = "jeff.albee@svn.com";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }
      if ($tech_supp_check == 'checked' || $cleric_supp_check == 'checked' || $raz_mon_check == 'checked')
      {
        $to = "foodpantry@popwh.org";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }
      if ($org_food_check == 'checked' || $sort_food_check == 'checked')
      {
        $to = "jsorice123@yahoo.com";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }
      if ($unl_food_check == 'checked')
      {
        $to = "lescarney@yahoo.com";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }
      if ($sort_food_check == 'checked')
      {
        $to = "lescarney@yahoo.com";
//        $to = "c2630@aol.com";
        $mail_sent = smtpmailer($to,$from,$ccname,$subject,$message,$ishtml);
        $subject = $subject . " - Mailed to " . $to;
      }


//    check for name in the DB

      $alreadythere = FALSE;
      $link = mysqli_connect($hostname, $username, $password, $dbname);
      $query = "SELECT * FROM People WHERE FName = '" . trim($first_name_line) . "' and LName = '" . trim($last_name_line) . "'";
      $answer = mysqli_query($link,$query);

      if ($answer)
      {
        $number_names = mysqli_num_rows($answer);
        if ($number_names > 0)
        {
          $alreadythere = TRUE;
        }
      }


//      $to2 = "henrysuzukawa@gmail.com";
      $to2 = "timothyj.abad@gmail.com";
      $headers = $headers . "\r\nCc:applicants@westvalleyfoodpantry.org";
      if ($alreadythere)
      {
        $message .= "Possible duplicate entry. Not saved to DB\n";
      }

//      $mail_sent1 = mail($to2,$subject,$message,$headers);

//      $ccname = "suzukawa@att.net";
      $ccname = "applicants@westvalleyfoodpantry.org";

//    for testing swap the two lines

//      $mail_sent = smtpmailer($to2,$from,$ccname,$subject,$message,$ishtml);

      $mail_sent1 = smtpmailer($to2,$from,$ccname,$subject,$message,$ishtml);

      $to2 = "henrysuzukawa@gmail.com";
      $subject2 = 'FP-Vol-csv';
/*
      $csv_sent  = mail($to2,$subject2,$csvline,$headers);
*/
      $csv_sent = smtpmailer($to2,$from,$ccname,$subject2,$csvline,$ishtml);
    
      $ishtml = 'True';
      if (isfilled($email_line))
      {
        $to = $email_line;

        $handle = fopen( "./vol-reply-head.html", "r" );
        $reply = "";
        while ( ($buffer = fgets( $handle )) !== FALSE )
        {
          $reply = $reply . $buffer;
        }
        fclose( $handle );

        $reply = $reply . "Dear " . $name_line;

        $handle = fopen( "./vol-reply-tail.html", "r" );
        while ( ($buffer = fgets( $handle )) !== FALSE )
        {
          $reply = $reply . $buffer;
        }
        fclose( $handle );

        $reply_sent = smtpmailer($to,$from,"","Thank you for volunteering",$reply,$ishtml);

      }

//    for testing comment the block

      if (!$alreadythere)
      {
        $volApplicant->addToPeople();
        $volApplicant->addToJobs();
        $volApplicant->addToNotes();
        $volApplicant->addToLanguage();
      }

      echo "<div class='row'>
              <div class='col-sm-8 col-sm-offset-2' style='text-align:center;'>";

      if ($mail_sent)
      {
        echo "<h4>Thank you for volunteering to help the<br>
                 West Valley Food Pantry.<br><br>Your application has been sent</h4>";
      }
      else
      {
        echo "<h4>We are sorry, but there has been a problem!<br>
                 Your application was not sent.<br>Please try again!</h4>";
      }

      $_SESSION['VAsubmit'] = 1;

    }
  
    echo "<br><br>";       
    echo "<a href='volunteer-page.html' class='btn btn-primary btn-block'>Return to Volunteer Page</a>";
    echo "<br><br></div></div>";
    
  }

?>