<?PHP
/*
    Class to collect information from Volunteer application
    and add to the database.
    V0.1

    Henry Suzukawa
    June 21, 2015
    July 28, 2015 - switch to object mysqli, add formatting to names
    July 29, 2015 - fix bug in names
    Sept  8, 2015 - add state to db

*/
require_once("./include/MyLogPHP.class.php");

class VolApplicant
{
    var $guid;
    var $submitDate;
    var $fname;
    var $lname;
    var $phone;
    var $streetAdd;
    var $city;
    var $state;
    var $zip;
    var $email;
    var $contPref;
    var $collDonat;
    var $orgDrive;
    var $delFood;
    var $unloadFood;
    var $sortFood;
    var $raiseMoney;
    var $distFood;
    var $techAid;
    var $clericAid;
    var $otherServ;
    var $otherNote;
    var $langs;
    var $hearAbout;
    var $serProject;
    var $Comments;
    var $vehicle;
    var $contact;
    var $training;
    var $timestamp;
    var $category;

    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;
    var $msqli;
   
    var $error_message;
    
    //-----Initialization -------
    function AVMembersite()
    {
      $this->sitename = 'www.westvalleyfoodpantry.org';
      $this->rand_key = '0iQx5oBk66oVZep';
    }
    
    function InitDB($host,$uname,$pwd,$database)
    {
      $this->db_host   = $host;
      $this->username  = $uname;
      $this->pwd       = $pwd;
      $this->database  = $database;
    }
    function Initialize()
    {
      $this->guid       = '';
      $this->submitDate = '';
      $this->fname      = '';
      $this->lname      = '';
      $this->phone      = '';
      $this->streetAdd  = '';
      $this->city       = '';
      $this->state      = 'CA';
      $this->zip        = '';
      $this->email      = '';
      $this->contPref   = '';
      $this->collDonat  = '';
      $this->orgDrive   = '';
      $this->delFood    = '';
      $this->unloadFood = '';
      $this->sortFood   = '';
      $this->raiseMoney = '';
      $this->distFood   = '';
      $this->techAid    = '';
      $this->clericAid  = '';
      $this->otherServ  = '';
      $this->otherNote  = '';
      $this->langs      = '';
      $this->hearAbout  = '';
      $this->serProject = '';
      $this->Comments   = '';
      $this->vehicle    = '';
      $this->contact    = '';
      $this->training   = '';
      $this->timestamp  = '';
      $this->category   = '';
    }
    function SetWebsiteName($sitename)
    {
      $this->sitename = $sitename;
    }
    function SetRandomKey($key)
    {
      $this->rand_key = $key;
    }
    function SetSubmittalDate($subdate)
    {
      $this->submitDate = $subdate;
    }
    function SetName($fname,$lname)
    {
      $this->fname      = ucwords(strtolower($fname));
      $this->lname      = ucwords(strtolower($lname));
    }
    function SetPhone($phone)
    {
      $this->phone      = $phone;
    }
    function SetAddress($street,$city,$state,$zip)
    {
      $this->streetAdd  = ucwords(strtolower($street));
      $this->city       = ucwords(strtolower($city));
      $this->state      = $state;
      $this->zip        = $zip;
    }
    function SetEmail($email)
    {
      $this->email      = $email;
    }
    function SetContactPreference($prefer)
    {
      $this->contPref   = $prefer;
    }
    function SetCollectFood()
    {
      $this->collDonat  = 'CollectFood';
    }
    function SetOrganizeDrive()
    {
      $this->orgDrive   = 'OrganizeDrive';
    }
    function SetDeliverFood()
    {
      $this->delFood    = 'DeliverFood';
    }
    function SetUnloadFood()
    {
      $this->unloadFood = 'UnloadFood';
    }
    function SetSortFood()
    {
      $this->sortFood   = 'SortFood';
    }
    function SetRaiseMoney()
    {
      $this->raiseMoney = 'RaiseMoney';
    }
    function SetDistributeFood()
    {
      $this->distFood   = 'DistributeFood';
    }
    function SetTechnicalAid()
    {
      $this->techAid    = 'TechnicalAid';
    }
    function SetClericalAid()
    {
      $this->clericAid  = 'ClericalAid';
    }
    function SetOther($other)
    {
      if ($other != '')
      {
        $this->otherServ  = 'Other';
        $this->otherNote  = $other;
      }
    }
    function SetLanguage($languageStr)
    {
      $this->langs      = $languageStr;
    }
    function SetHeardAbout($heardAbout)
    {
      $this->hearAbout  = $heardAbout;
    }
    function SetServiceProject($serProj)
    {
      $this->serProject = $serProj;
    }
    function SetComments($commentStr)
    {
      $commentStr = str_replace("\r"," ",$commentStr);
      $commentStr = str_replace("\n"," ",$commentStr);
      $commentStr = str_replace(",",";",$commentStr);
      $commentStr = str_replace("'","\'",$commentStr);
      $commentStr = str_replace('"','',$commentStr);
      $commentStr = trim($commentStr);
      $this->Comments   = $commentStr;
    }
    function SetVehicle($car)
    {
      $car = str_replace("\r"," ",$car);
      $car = str_replace("\n"," ",$car);
      $car = str_replace(",",";",$car);
      $this->vehicle    = $car;
    }
    function SetContactNote($contact)
    {
      $contact = str_replace("\r"," ",$contact);
      $contact = str_replace("\n"," ",$contact);
      $contact = str_replace(",",";",$contact);
      $contact = str_replace("'","''",$contact);
      $contact = str_replace('"','',$contact);
      $this->contact    = $contact;
    }
    function SetTraining($trained)
    {
      $this->training   = $trained;
    }
    function SetCategory($category)
    {
      $this->category   = $category;
    }
    
    //-------Main Operations ----------------------

    function addToPeople()
    {
      $log   = new MyLogPHP('./logs/logfile.csv');
      date_default_timezone_set('America/Los_Angeles');

      if(!$this->DBLogin())
      {
        $this->HandleError("Database login failed!");
        return false;
      }
      $query  = 'INSERT INTO People';
      $query .= ' (SubmittalDate, FName, LName, Phone, StreetAddress,';
      $query .= ' City, State, Zip, Email, ContactPref, HeardAbout, Category) ';
      $query .= 'VALUES ';
      $rawdate = $this->submitDate;
      if ($rawdate == '')
      {
        $rawdate = date('Y-m-d h:i a');
      }
      $fields  = explode(' ',$rawdate);
      $rtime   = explode(':',$fields[1]);
      if ($fields[2] == 'PM')
      {
        $hour    = intval( $rtime[0] );
        $hour += 12;
        if ($hour > 23)
        {
          $hour = 0;
        }
        $rtime[0] = (string) $hour;
      }
      $timestamp = $fields[0] . " " . $rtime[0] . ":" . $rtime[1] . ":00";
      $this->timestamp = $timestamp;
      $query .= "( '" . $timestamp . "', '";
      $rfname = $this->mysqli->real_escape_string($this->fname);
      $rlname = $this->mysqli->real_escape_string($this->lname);
      $query .= $rfname . "', '" . $rlname . "', '";
      $phonum = $this->phone;
      $phonum = str_replace("-","",$phonum);
      $phonum = str_replace(" ","",$phonum);
      $phonum = str_replace("(","",$phonum);
      $phonum = str_replace(")","",$phonum);
      $phonum = str_replace(".","",$phonum);
      $phonum = str_replace("/","",$phonum);
      $query .= $phonum . "', '" . $this->streetAdd . "', '";
      $query .= $this->city . "', '" . $this->state . "', '" . $this->zip . "', '";
      $query .= $this->email . "', '";
      if ($this->contPref == 'E-mail')
      {
        $query .= "Email', '";
      }
      else
      {
        $query .= "Post', '";
      }
      $query .= $this->hearAbout . "', '";
      if ($this->category != '')
      {
        $query .=  $this->category . "' );";
      }
      else
      {
        $query .= "Applicant' );";
      }
      $log->info( $query, 'SQL' );
      //echo $query . "<br>";

      $result = $this->mysqli->query($query);
      if(!$result)
      {
        $this->HandleError("Error adding data to the People table.");
        $log->error("Error adding data to the People table.");
        return false;
      }
      $this->guid = $this->mysqli->insert_id;

//      $query = "SELECT * FROM People where FName = '" . $this->fname . "' and LName = '" . $this->lname . "';";
      //echo $query . "<br>";
//      $log->info( $query, 'SQL' );
//      $result = mysqli_query($this->connection,$query);
//      if(!$result || mysqli_num_rows($result) <= 0)
//      {
//        $this->HandleError("Error retrieving user ID.");
//        $log->error("Error retrieving user ID.");
//        return false;
//      }
//      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//      $this->guid = trim($row["GUID"]);
      //$this->guid = '200';

      return true;
      
    }
    function addToJobs()
    {
      $log   = new MyLogPHP('./logs/logfile.csv');

      if(!$this->DBLogin())
      {
        $this->HandleError("Database login failed!");
        return false;
      }
      $query  = 'INSERT INTO Jobs';
      $query .= ' (GUID, Type, Trained, TrainedBy, TrainingYear) ';
      $query .= 'VALUES ';

      if ($this->collDonat  != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->collDonat . "', False, '', ''),";
      }
      if ($this->orgDrive   != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->orgDrive . "', False, '', ''),";
      }
      if ($this->delFood    != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->delFood . "', False, '', ''),";
      }
      if ($this->unloadFood != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->unloadFood . "', False, '', ''),";
      }
      if ($this->sortFood   != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->sortFood . "', False, '', ''),";
      }
      if ($this->raiseMoney != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->raiseMoney . "', False, '', ''),";
      }
      if ($this->distFood   != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->distFood . "', False, '', ''),";
      }
      if ($this->techAid    != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->techAid . "', False, '', ''),";
      }
      if ($this->clericAid  != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->clericAid . "', False, '', ''),";
      }
      if ($this->otherServ  != '')
      {
        $query .= "( '" . $this->guid . "', '" . $this->otherServ . "', False, '', ''),";
      }
      $query = substr($query, 0, -1) . ";";
      $log->info( $query, 'SQL' );
      //echo $query . "<br>";

      $result = $this->mysqli->query($query);
      if(!$result)
      {
        $this->HandleError("Error adding data to the Jobs table.");
        $log->error("Error adding data to the Jobs table.");
        return false;
      }

      return true;
    }

    function addToNotes()
    {
      $log   = new MyLogPHP('./logs/logfile.csv');

      $query  = 'INSERT INTO Notes';
      $query .= ' (GUID, Type, Comment, Date) ';
      $query .= 'VALUES ';

      $gotNotes = false;

      if ($this->otherNote  != '')
      {
        $query .= "( '" . $this->guid . "', 'OtherService', '" . $this->otherNote . "', '" . $this->timestamp . "'),";
        $gotNotes = true; 
      }
      if ($this->serProject != '')
      {
        $query .= "( '" . $this->guid . "', 'ServiceProject', '" . $this->serProject . "', '" . $this->timestamp . "'),";
        $gotNotes = true; 
      }
      if ($this->Comments   != '')
      {
        $query .= "( '" . $this->guid . "', 'Volunteer', '" . $this->Comments . "', '" . $this->timestamp . "'),";
        $gotNotes = true; 
      }
      if ($this->vehicle    != '')
      {
        $query .= "( '" . $this->guid . "', 'Vehicle', '" . $this->vehicle . "', '" . $this->timestamp . "'),";
        $gotNotes = true; 
      }
      $query = substr($query, 0, -1) . ";";
      $log->info( $query, 'SQL' );
      //echo $query . "<br>";

      if ($gotNotes)
      {
        if(!$this->DBLogin())
        {
          $this->HandleError("Database login failed!");
          return false;
        }
        $result = $this->mysqli->query($query);
        if(!$result)
        {
          $this->HandleError("Error adding data to the Notes table.");
          $log->error("Error adding data to the Notes table.");
          return false;
        }
      }

      return true;
    }
    function addToLanguage()
    {
      $log   = new MyLogPHP('./logs/logfile.csv');

      if ($this->langs == '')
      {
        return true;
      }

      $query  = 'INSERT INTO Languages';
      $query .= ' (GUID, Language, Fluent) ';
      $query .= 'VALUES ';

      $langStr = trim($this->langs);
      $langParts = explode( ";", $langStr );
      $nlang     = count($langParts);
      for ($ic=0; $ic<$nlang; $ic++)
      {
        if ($langParts[$ic] != '')
        {
          $istwo = explode( " ", $langParts[$ic] );
          $fluent = false;
          $lannam = $istwo[0];
          if (count($istwo) > 1)
          {
            $lannam = $istwo[1];
            if ($istwo[0] == 'Fluent')
            {
              $fluent = true;
            }
            else
            {
              $fluent = false;
            }
          }
          $query .= "( '" . $this->guid . "', '" . $lannam . "', ";
          if ($fluent)
          {
            $query .= "True),";
          }
          else
          {
            $query .= "False),";
          }
        }
      }
      $query = substr($query, 0, -1) . ";";
      $log->info( $query, 'SQL' );
      //echo $query . "<br>";

      if(!$this->DBLogin())
      {
        $this->HandleError("Database login failed!");
        return false;
      }
      $result = $this->mysqli->query($query);
      if(!$result)
      {
        $this->HandleError("Error adding data to the Languages table.");
        $log->error("Error adding data to the Languages table.");
        return false;
      }

      return true;
    }
    
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
      return htmlentities($_SERVER['PHP_SELF']);
    }    
    
    function SafeDisplay($value_name)
    {
      if(empty($_POST[$value_name]))
      {
        return'';
      }
      return htmlentities($_POST[$value_name]);
    }
    
    function RedirectToURL($url)
    {
      header("Location: $url");
      exit;
    }
    
    function GetErrorMessage()
    {
      if(empty($this->error_message))
      {
        return '';
      }
      $errormsg = nl2br(htmlentities($this->error_message));
      return $errormsg;
    }    

    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
      $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
      $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    
    function DBLogin()
    {

      $this->mysqli = new mysqli($this->db_host,$this->username,$this->pwd,$this->database);
      if($this->mysqli->connect_errno)
      {   
        $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
        return false;
      }
      return true;
    }    
    
 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
 */
    function Sanitize($str,$remove_nl=true)
    {
      $str = $this->StripSlashes($str);

      if($remove_nl)
      {
        $injections = array('/(\n+)/i',
            '/(\r+)/i',
            '/(\t+)/i',
            '/(%0A+)/i',
            '/(%0D+)/i',
            '/(%08+)/i',
            '/(%09+)/i'
            );
        $str = preg_replace($injections,'',$str);
      }

      return $str;
    }    
    function StripSlashes($str)
    {
      if(get_magic_quotes_gpc())
      {
        $str = stripslashes($str);
      }
      return $str;
    }    
}
?>