<!DOCTYPE html>
<html>
<head>
  <title>Title of the document</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <style>
        .results{
            width:50%;
            margin: 0 auto;
            text-align: center;
        }
    </style>
    
</head>
</html>

<?php

class Word {
  
    public $text;
    public $spaceBefore;
    public $spaceAfter;
    public $isNumber;
    public $wordIsOneChar = False; 
    
public function __construct($text, $spaceBefore, $spaceAfter){
      $this->text = $text;
      $this->spaceBefore = $spaceBefore;
      $this->spaceAfter = $spaceAfter;

      $this->isNumber();
      $this->isWordOneChar();
      //$this->output();
  }
    
    function isNumber(){
        $this->isNumber = is_numeric($this->text);
    }
    
    function isWordOneChar(){
        
        $length = strlen($this->text);
        
        if ($length == 1){
            $this->wordIsOneChar = True;
        }
    }
    /*function output(){
        if($this->isNumber){
            echo "This is a number <br>";
        }else{
            echo "This is NOT a number <br>";
        }
    }*/
    
}

class TextSplitter{
    
    public $input;
    public $resultArray = array();
    public $wordCounter = 0;
    
    public function __construct($input){
        $this->input = $input;
        $this->splitInput();
        //$this->output();
    }
    
    function splitInput(){ //This function keeps track of how many spaces, and their indices.

        
        $lastSpaceIndex = 0;
        $spaceBefore = False;
        $spaceAfter = False;
        
        $length = strlen($this->input);
        
        //goes through each index of input string.
        
        //("john smith 24 M"); (str len = 15)
        
        for($i=0; $i< $length; $i++){
            
            //$word will be later pushed into final array
            if($this->input{$i} == " "){
                
                $word = "";
                //if there is no space before...this is the first word, create substring of $input and store it in $word
                if($lastSpaceIndex == 0){
                    $spaceBefore = False;
                    //php substring function takes three parameters, check documentation
                    $word = substr($this->input, $lastSpaceIndex, $i-$lastSpaceIndex); //john
                }else{
                    $spaceBefore = True;
                    $word = substr($this->input, $lastSpaceIndex+1, $i-$lastSpaceIndex-1);
                }
                
                $spaceAfter = True;
                
                
                
                array_push($this->resultArray, new Word($word, $spaceBefore, $spaceAfter));
                
              // a sample result array ("john", "smith", 24, "M");
                
                $this->wordCounter++;
                $lastSpaceIndex = $i;
                
            }
            else if($i == $length -1){
                $spaceBefore = True;
                $spaceAfter = False;
                $word = substr($this->input, $lastSpaceIndex+1, $i-$lastSpaceIndex);
                array_push($this->resultArray,new Word($word,$spaceBefore, $spaceAfter));
                
                $this->wordCounter++;
                $this->lastSpaceIndex = $i;
            }
                
            
                
        }
     
        
    }
    
    function output(){
   
            echo "Number of words found: <br>" . $this->wordCounter;
        
    }

}

class FirstNameParser{
    
    public $textSplitter; //instance of TextSplitter will be given already through the constructor
    public $firstName;
    public $firstNameFound = False;
    
    public function __construct($textSplitter){
        
        $this->textSplitter = $textSplitter;
        
        $this->findFirstName();
        //$this->output();
    }
    
    function findFirstName(){
        //("john", "smith", 24, "M");
        $word = $this->textSplitter->resultArray{0};
        
        
        //("john smith 24 M"); (str len = 15) spaceBefore here is false for 'john'
        if($word->spaceBefore == False && $word->spaceAfter == True && $word->isNumber == False && $word->wordIsOneChar == False){
            $this->firstName = $word->text;
            $this->firstNameFound = True;
        }
        
    }
    
    function output(){
        if($this->firstNameFound){
            echo "First Name found <br>";
        }else{
            echo "First Name NOT found. <br>";
        }
    }
}

class LastNameParser{
    
    public $textSplitter; //instance of TextSplitter will be given already through the constructor
    public $lastName;
    public $lastNameFound = False;
    
    public function __construct($textSplitter){
        
        $this->textSplitter = $textSplitter;
        
        $this->findLastName();
        //$this->output();
    }
    
    function findLastName(){
        
        $word = $this->textSplitter->resultArray{1};
        
        //echo "<br> Is One char? " . $word->wordIsOneChar;
        
        if($word->spaceBefore == True && $word->spaceAfter == True && $word->isNumber == False && $word->wordIsOneChar == False){
            $this->lastName = $word->text;
            $this->lastNameFound = True;
        }
        
    }
    
    function output(){
        if($this->lastNameFound){
            echo "<br> Last Name found.";
        }else{
            echo "<br> Last Name NOT found.";
        }
    }
}

class AgeParser{
    
    public $textSplitter; //instance of TextSplitter will be given already through the constructor
    public $age;
    public $ageFound = False;
    
    public function __construct($textSplitter){
        
        $this->textSplitter = $textSplitter;
        
        $this->findAge();
        //$this->output();
    }
    
    function findAge(){
        
        foreach($this->textSplitter->resultArray as $word){
            if($word->isNumber){
                $this->age = $word->text;
                $this->ageFound = True;
                break;
            }
        }
        
        
    }
    
    function output(){
        if($this->ageFound){
            echo "<br> Age found.";
        }else{
            echo "<br> Age NOT found.";
        }
    }
    
}

class GenderParser{
    
    public $textSplitter; //instance of TextSplitter will be given already through the constructor
    public $gender;
    public $genderFound = False;
    
    public function __construct($textSplitter){
        
        $this->textSplitter = $textSplitter;
        
        $this->findGender();
        //$this->output();
    }
    
    function findGender(){
        
        foreach($this->textSplitter->resultArray as $word){
            if($word->wordIsOneChar){
                $this->gender = $word->text;
                $this->genderFound = True;
                break;
            }
        }
        
        
    }
}

class User{
    
    public $firstNameParser;
    public $lastNameParser;
    public $ageParser;
    public $genderParser;
    
    public function __construct($firstNameParser, $lastNameParser, $ageParser, $genderParser){
        
        $this->firstNameParser = $firstNameParser;
        $this->lastNameParser = $lastNameParser;
        $this->ageParser = $ageParser;
        $this->genderParser = $genderParser;
        
    }
    
    function output(){

        //echo "<br />";
        
         //echo " " . $this->firstNameParser->firstName;
    
    
    if($this->lastNameParser->lastNameFound){
        echo " " .  $this->lastNameParser->lastName;
    }else{
        echo " <br>Last Name NOT found ";
    }
    
    
    echo " " .  $this->ageParser->age;
    
    
    echo " " .  $this->genderParser->gender;
        
echo "<br />";
    }
    
}

class UserParser{
    
    public $input; //incoming array
    public $userArr =array();
    
    public function __construct($input){
        
        $this->input = $input;
        
        $this->parseUsers();
        //$this->output();
    }
    
    function parseUsers(){
        
        foreach($this->input as $person){
            
            $textSplitter = new TextSplitter($person);
    
            $firstName = new FirstNameParser($textSplitter);
    
            $lastName = new LastNameParser($textSplitter);
    
            $age = new AgeParser($textSplitter);
    
            $gender = new GenderParser($textSplitter);
    
            $user = new User($firstName, $lastName, $age, $gender);
            
            array_push($this->userArr, $user);
    
            }
        }
    
    
    function output(){

          
       /* foreach($this->userArr as $user){
            $user->output();
            echo "ageee: " . $user->ageParser->age;
        */    
            
    ?>     <div class="container">           
  <table class="table table-striped">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Gender</th>
      </tr>
    </thead>
    <tbody>
    <?php 
        
        foreach($this->userArr as $user){
    ?>
    <tr>
        <td>
    <?php 
            echo $user->firstNameParser->firstName; 
     ?>   
        </td>
        <td>
    <?php 
            if($user->lastNameParser->lastNameFound){
        echo $user->lastNameParser->lastName;
            }else{
                echo "Last Name NOT found";
            }
     ?>   
        </td>
        <td>
    <?php 
            echo $user->ageParser->age; 
     ?>   
        </td>
        <td>
    <?php 
            echo $user->genderParser->gender; 
     ?>   
        </td>
    </tr>
            
       <?php }
        ?>
    </tbody>
  </table>
</div>   
    <?php       
        }
    }
        
        
    
    

$inputStr = array ("Twesh Chowdhury 24 M", "charlie 33 M", "John M Smith 45", "Jenna Houston F 36", "Ralph Lauren 65 M", "John 45 Smith M");
$userParser = new UserParser($inputStr);
$userParser->output();































/*

function findSpaceIndex($startPoint, $input){
    $result =-1;
    
    for($i=$startPoint+1; $i<strlen($input); $i++){
        
        if($input{$i} == " "){
            $result = $i;
            break;
        }
    }
    return $result;
}


function firstName($input){
    $firstSpace = findSpaceIndex(0, $input);
    return substr($input, 0, $firstSpace);
}

function lastName($input){
    $firstSpace = findSpaceIndex(0, $input);
    $secondSpace = findSpaceIndex($firstSpace, $input);
  
    $result = substr($input, $firstSpace, $secondSpace - $firstSpace );
    
    if(!is_numeric($result)){
        
        return $result;
    }
    else{ 
        
        return "";
    }
}

function age($input, $lastNameWasFound){
    $firstSpace = findSpaceIndex(0, $input);
    $secondSpace = findSpaceIndex($firstSpace, $input);
    $thirdSpace = findSpaceIndex($secondSpace, $input);

    if($lastNameWasFound){
        $result = substr($input, $secondSpace, $thirdSpace - $secondSpace );
    }
    else{
    $result = substr($input, $firstSpace, $secondSpace - $firstSpace );
    }
        
    return $result;
    
}

function gender($input){
    
    
    
    return $input{strlen($input)-1};
}

/*foreach($inputStr as $person){
$lastNameWasFound = False;
echo "First Name: " . firstName($person) ;
if(lastName($person) != ""){ 
    echo "Last Name: " . lastName($person);
    $lastNameWasFound = True;
}
echo "Age: " . age($person, $lastNameWasFound);
echo "Gender: " . gender($person);
echo "<br>";
    

}*/

//echo strlen($inputStr);
//echo substr($inputStr,1);
//echo $inputStr{1};
//echo substr($inputStr,2,2);

/*function checkIfLetter($input){
  $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRUSTUVWXYZ";
  
  $result = False;
  
  for($i=0;$i<strlen($letters);$i++){
      
      if($input === $letters{$i}){
              $result = True;
              break;
            }
    }
  return $result;
}*/

/*for($i=0; $i<$strLen;$i++){
  
  $currentLetter = $inputStr{$i};
  if(is_numeric($currentLetter)){
      echo "number-- ";
    }else if(checkIfLetter($currentLetter)){
      echo "is letter--";
    }else{
      echo "not a letter--";
}
}*/
?>