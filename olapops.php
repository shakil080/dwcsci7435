<?php

ob_start();

require_once 'database/database.php';
require_once 'database/config.php';

if(!session_id()) session_start ();

$error_msg = "";

$database = null;
$gparType = "";
$resultData = "";

$rollUpBtnSelected = 0;
$rollDownBtnSelected = 0;
$diceBtnSelected = 0;
$sliceBtnSelected = 0;
$pivotBtnSelected = 0;

$studentBtnSelected = 0;
$timeBtnSelected = 0;

$computerScBtnSelected = 0;
$informationScBtnSelected = 0;
$appliedScBtnSelected = 0;
$accountingBtnSelected = 0;
$businessAdminBtnSelected = 0;
$economicsBtnSelected = 0;
$elementaryEdBtnSelected = 0;
$secondaryEdBtnSelected = 0;
$biologyBtnSelected = 0;
$chemistryBtnSelected = 0;


$cyberCollegeBtnSelected = 0;
$collegeOfBusinessBtnSelected = 0;
$collegeOfEducationBtnSelected = 0;
$collegeOfArtAndScienceBtnSelected = 0;

$internationalBtnSelected = 0;
$outofStateBtnSelected = 0;
$noneBtnSelected = 0;

$asBtnSelected = 0;
$baBtnSelected = 0;
$bsBtnSelected = 0;
$eddBtnSelected = 0;
$maBtnSelected = 0;
$mbaBtnSelected = 0;
$msBtnSelected = 0;
$phdBtnSelected = 0;


$fallBtnSelected = 0;
$springBtnSelected = 0;
$summer1BtnSelected = 0;
$summer2BtnSelected = 0;
$summer3BtnSelected = 0;
$summer4BtnSelected = 0;


function executeAndGetResponse($sqlstr){

  $database = null;
  $database = Database::getDatabase($GLOBALS['connection_String'], $GLOBALS['user_name'], $GLOBALS['Password'], $GLOBALS['dataBaseName']);
  $resultData = $database->GetQueryResult($sqlstr);

  return $resultData;
}


?>
<!DOCTYPE HTML>

<html>
<head>
	<title>Data Warehousing - Olap Operations</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/custom.css">
<?php include("inc_head.php"); ?>
<link rel="stylesheet" type="text/css" href="css/fancySelect.css">
<script src="js/fancySelect.js"></script>

<script>

let rollUpBtnSelected = <?php echo $rollUpBtnSelected;?>;
let rollDownBtnSelected = <?php echo $rollDownBtnSelected;?>;
let diceBtnSelected = <?php echo $diceBtnSelected;?>;
let sliceBtnSelected = <?php echo $sliceBtnSelected;?>;
let pivotBtnSelected = <?php echo $pivotBtnSelected;?>;

let studentBtnSelected = <?php echo $studentBtnSelected;?>;
let timeBtnSelected = <?php echo $timeBtnSelected;?>;

let computerScBtnSelected = <?php echo $computerScBtnSelected;?>;
let informationScBtnSelected = <?php echo $informationScBtnSelected;?>;
let appliedScBtnSelected = <?php echo $appliedScBtnSelected;?>;
let accountingBtnSelected = <?php echo $accountingBtnSelected;?>;
let businessAdminBtnSelected = <?php echo $businessAdminBtnSelected;?>;
let economicsBtnSelected = <?php echo $economicsBtnSelected;?>;
let elementaryEdBtnSelected = <?php echo $elementaryEdBtnSelected;?>;
let secondaryEdBtnSelected = <?php echo $secondaryEdBtnSelected;?>;
let biologyBtnSelected = <?php echo $biologyBtnSelected;?>;
let chemistryBtnSelected = <?php echo $chemistryBtnSelected;?>;

let cyberCollegeBtnSelected = <?php echo $cyberCollegeBtnSelected;?>;
let collegeOfBusinessBtnSelected = <?php echo $collegeOfBusinessBtnSelected;?>;
let collegeOfEducationBtnSelected = <?php echo $collegeOfEducationBtnSelected;?>;
let collegeOfArtAndScienceBtnSelected = <?php echo $collegeOfArtAndScienceBtnSelected;?>;

let internationalBtnSelected = <?php echo $internationalBtnSelected;?>;
let outofStateBtnSelected = <?php echo $outofStateBtnSelected;?>;
let noneBtnSelected = <?php echo $noneBtnSelected;?>;

let asBtnSelected = <?php echo $asBtnSelected;?>;
let baBtnSelected = <?php echo $baBtnSelected;?>;
let bsBtnSelected = <?php echo $bsBtnSelected;?>;
let eddBtnSelected = <?php echo $eddBtnSelected;?>;
let maBtnSelected = <?php echo $maBtnSelected;?>;
let mbaBtnSelected = <?php echo $mbaBtnSelected;?>;
let msBtnSelected = <?php echo $msBtnSelected;?>;
let phdBtnSelected = <?php echo $phdBtnSelected;?>;

let fallBtnSelected = <?php echo $fallBtnSelected;?>;
let springBtnSelected = <?php echo $springBtnSelected;?>;
let summer1BtnSelected = <?php echo $summer1BtnSelected;?>;
let summer2BtnSelected = <?php echo $summer2BtnSelected;?>;
let summer3BtnSelected = <?php echo $summer3BtnSelected;?>;
let summer4BtnSelected = <?php echo $summer4BtnSelected;?>;

let olapOpsArr = [];
let dimensionArr = [];
let majorArr = [];
let collegeArr = [];
let statusArr = [];
let degreeArr = [];
let semesterArr = [];

let studentRollUpSetup = [];
let timeRollUpSetup = [];
let diceSetup = [];
let sliceSetup = [];

$(document).ready(function() {
		
	$('#gparangetype').fancySelect().on('change', function() {
			newSection = $('#' + $(this).val())

			if (newSection.hasClass('current')) {
				return;
			}

			$('section').removeClass('current');
			newSection.addClass('current');

			$('section:not(.current)').fadeOut(300, function() {
				newSection.fadeIn(300);
			});
			
			var selectedIndex = document.getElementById('gparangetype').selectedIndex;
			var type = document.getElementById('gparangetype').options[selectedIndex].value;
			
		});
	
	
	});

function generateOlapSeq(){

  var selectedIndex = document.getElementById('gparangetype').selectedIndex;
	let selectedGPARange = document.getElementById('gparangetype').options[selectedIndex].value;
	if(selectedGPARange == -1){
    selectedGPARange = "";
	}

  let name = document.getElementById('txtStdName').value;
  let address = document.getElementById('txtAddress').value;
  let year = document.getElementById('txtYear').value;

  console.log(selectedGPARange);
  console.log(name);
  console.log(address);
  console.log(year);

  let yearArr = year.split(",");
  console.log(yearArr);
  for (i = 0; i < yearArr.length; i++) {
    //result.push(x[i].substr(1));
    if(yearArr[i].length == 4){
      yearArr[i] = yearArr[i].slice(2);
    }
  }
  
  if(yearArr.length == 1){
    if(yearArr[0].length == 0)
      yearArr = [];
  }
  console.log(yearArr.length);

  //olapOpsArr
  // Check if olapops contain rollup
  if(olapOpsArr.length == 0 || olapOpsArr.indexOf("rollup") == -1){
    alert("Roll Up must be selected in olap operations to proceed");
    return;
  }

  // Check if dimension array contain any selection
  if(dimensionArr.length == 0){
    alert("One dimension must be selected in olap operations to proceed");
    return;
  }

studentRollUpSetup = [];
timeRollUpSetup = [];
diceSetup = [];
sliceSetup = [];


  if(dimensionArr.indexOf("Student") > -1){
    // Student dimension selected
    if(majorArr.length > 0){
      studentRollUpSetup.push("Major");
    }

    if(collegeArr.length > 0){
      if(majorArr.length == 0){
        studentRollUpSetup.push("Major");
      }
      studentRollUpSetup.push("College");
    }

  }

  if(dimensionArr.indexOf("Time") > -1){
    if(semesterArr.length > 0){
      timeRollUpSetup.push("Semester");
    }

    if(yearArr.length > 0){
      timeRollUpSetup.push("Year");
    }
  }

  // Generate Roll Up Olap Seq
  let rollUpOlapSeq = [];
  rollUpOlapSeq.push("C1: Roll-up Cuboid on:");
  if(studentRollUpSetup.length > 0){
    rollUpOlapSeq.push("Student to".concat(" ",studentRollUpSetup.toString()));
  }

  if(timeRollUpSetup.length > 0){
    rollUpOlapSeq.push("Time to".concat(" ",timeRollUpSetup.toString()));
  }
  
  console.log(rollUpOlapSeq);

  // Generate Dice Olap Seq
  let diceOlapSeq = [];
  diceOlapSeq.push("C2: Dice C1  on:");

  if(majorArr.length > 0){
    diceOlapSeq.push("Major:".concat(majorArr.toString()));
  }

  if(collegeArr.length > 0){
    diceOlapSeq.push("College:".concat(collegeArr.toString()));
  }

  if(degreeArr.length > 0){
    diceOlapSeq.push("Degree:".concat(degreeArr.toString()));
  }

  if(statusArr.length > 0){
    diceOlapSeq.push("Status:".concat(statusArr.toString()));
  }

  if(semesterArr.length > 0){
    diceOlapSeq.push("Semester:".concat(semesterArr.toString()));
  }

  if(yearArr.length > 0){
    diceOlapSeq.push("Year:".concat(yearArr.toString()));
  }
  console.log(diceOlapSeq);

  let currentCuboidCount = 2;
  let sliceOlapSeq = [];
  let initialStrForEachSlice = "";
  
  if(yearArr.length > 0){
    initialStrForEachSlice = "C" + (currentCuboidCount+1) + ": Slice C" + currentCuboidCount + " for ";
    currentCuboidCount++;
    let yearSliceStr = "";
    for (let index = 0; index < yearArr.length; index++) {
      //const element = yearArr[index];
      //year = 89 or year=90 or year=91
      yearSliceStr = yearSliceStr + "year = " + yearArr[index];

      if(index < yearArr.length - 1){
        yearSliceStr = yearSliceStr + " Or ";
      }
      
    }
    sliceOlapSeq.push(initialStrForEachSlice + yearSliceStr);
    
  }


  if(semesterArr.length > 0){
    initialStrForEachSlice = "C" + (currentCuboidCount+1) + ": Slice C" + currentCuboidCount + " for ";
    currentCuboidCount++;
    let semesterSliceStr = "";
    for (let index = 0; index < semesterArr.length; index++) {
      semesterSliceStr = semesterSliceStr + "semester = " + semesterArr[index];

      if(index < semesterArr.length - 1){
        semesterSliceStr = semesterSliceStr + " Or ";
      }
      
    }
    sliceOlapSeq.push(initialStrForEachSlice + semesterSliceStr);
    
  }

  if(selectedGPARange.length > 0){
    initialStrForEachSlice = "C" + (currentCuboidCount+1) + ": Slice C" + currentCuboidCount + " for ";
    currentCuboidCount++;
    let gpaRangeSliceStr = "gparange = '"+ selectedGPARange + "'";
    
    sliceOlapSeq.push(initialStrForEachSlice + gpaRangeSliceStr);
    
  }

  console.log(sliceOlapSeq);

  document.getElementById('dwreview').value = rollUpOlapSeq.join('\n') + '\n\n' + diceOlapSeq.join('\n') + '\n\n' + sliceOlapSeq.join('\n');

}

function generateSQL(){

  let olapSeq = document.getElementById('dwreview').value;
  if(olapSeq.length == 0){
    alert("Generate Olap Sequence then try the SQL operations.");
    reuturn;
  }

  let year = document.getElementById('txtYear').value;

  let yearArr = year.split(",");
  console.log(yearArr);
  for (i = 0; i < yearArr.length; i++) {
    if(yearArr[i].length == 4){
      yearArr[i] = yearArr[i].slice(2);
    }
  }
  
  if(yearArr.length == 1){
    if(yearArr[0].length == 0)
      yearArr = [];
  }

  console.log(yearArr);

  let initialSQLStr = "SELECT COUNT(*) as row FROM GradStudentInformation JOIN Student ON GradStudentInformation.studentid = student.studentid ";

  let sqlJoinStr = "";
  let sqlWhereStr = "";

  if(dimensionArr.indexOf("Time") > -1){
    sqlJoinStr = sqlJoinStr + "JOIN time ON GradStudentInformation.timeid = time.timeid ";
  }

  if(majorArr.length > 0){
    sqlJoinStr = sqlJoinStr + "JOIN major ON Student.majorid = major.majorid ";

    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";
    
    let majorWhereStr = "(";
    for (let index = 0; index < majorArr.length; index++) {
      majorWhereStr = majorWhereStr + "major.majorname = '" + majorArr[index] + "'";

      if(index < majorArr.length - 1){
        majorWhereStr = majorWhereStr + " Or ";
      }
      
    }
    majorWhereStr = majorWhereStr + ")";

    sqlWhereStr = sqlWhereStr + majorWhereStr;
  }

  if(collegeArr.length > 0){
    if(majorArr.length == 0){
      sqlJoinStr = sqlJoinStr + "JOIN major ON Student.majorid = major.majorid ";
    }
    sqlJoinStr = sqlJoinStr + "JOIN college ON major.collegeid = college.collegeid ";

    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";
    
    let collegeWhereStr = "(";
    for (let index = 0; index < collegeArr.length; index++) {
      collegeWhereStr = collegeWhereStr + "college.collegename = '" + collegeArr[index] + "'";

      if(index < collegeArr.length - 1){
        collegeWhereStr = collegeWhereStr + " Or ";
      }
      
    }
    collegeWhereStr = collegeWhereStr + ")";

    sqlWhereStr = sqlWhereStr + collegeWhereStr;
  }

  if(yearArr.length > 0){
    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";

    let yearWhereStr = "time.year in (" + yearArr.toString() + ")";
    sqlWhereStr = sqlWhereStr + yearWhereStr;
  }

  if(semesterArr.length > 0){
    sqlJoinStr = sqlJoinStr + "JOIN semester ON time.semesterid = semester.semesterid ";

    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";
    
    let semesterWhereStr = "(";
    for (let index = 0; index < semesterArr.length; index++) {
      semesterWhereStr = semesterWhereStr + "semester.semestername = '" + semesterArr[index] + "'";

      if(index < semesterArr.length - 1){
        semesterWhereStr = semesterWhereStr + " Or ";
      }
      
    }
    semesterWhereStr = semesterWhereStr + ")";

    sqlWhereStr = sqlWhereStr + semesterWhereStr;
  }

  //statusArr
  if(statusArr.length > 0){

    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";
    
    let statusWhereStr = "(";
    for (let index = 0; index < statusArr.length; index++) {
      statusWhereStr = statusWhereStr + "Student.statusid = '" + statusArr[index] + "'";

      if(index < statusArr.length - 1){
        statusWhereStr = statusWhereStr + " Or ";
      }
      
    }
    statusWhereStr = statusWhereStr + ")";

    sqlWhereStr = sqlWhereStr + statusWhereStr;
  }

  var selectedIndex = document.getElementById('gparangetype').selectedIndex;
  let selectedGPARange = document.getElementById('gparangetype').options[selectedIndex].value;
	if(selectedGPARange == -1){
    selectedGPARange = "";
	}

  if(selectedGPARange.length > 0){
    if(sqlWhereStr.length == 0)
      sqlWhereStr = "Where ";
    else
      sqlWhereStr = sqlWhereStr + " AND ";

    let gpaRangeWhereStr = "GradStudentInformation.gparange = '" + selectedGPARange + "'";
    sqlWhereStr = sqlWhereStr + gpaRangeWhereStr;

  }

  document.getElementById('sqlreview').value = initialSQLStr + " " + sqlJoinStr + " " + sqlWhereStr;

}

function executeSQL(){
  let finalSQLStr = document.getElementById('sqlreview').value;

  // var phpResult= 
  // console.log(phpResult);

  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "ajaxresult.php", true); 
  xhttp.setRequestHeader("Content-Type", "application/json");
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Response
      var response = this.responseText;
      document.getElementById('resultcount').innerHTML = "Number of Graduates: " + response;
    }
  };
  var data = {sql:finalSQLStr};
  xhttp.send(JSON.stringify(data));
}               

function semesterOpsFunction(buttonindicator, selectedstatus){
  //alert(selectedstatus);
  if(semesterArr.includes(buttonindicator))
    semesterArr = semesterArr.filter(v => v !== buttonindicator); 
  else
    semesterArr.push(buttonindicator);

  switch(buttonindicator) {
    case "Fall semester":
      fallBtnSelected = 0;
      document.getElementById("fallbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        fallBtnSelected = 1;
        document.getElementById("fallbtn").style.color = "#0000";
      }
      
      break;
    case "Spring semester":
      springBtnSelected = 0;
      document.getElementById("springbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        springBtnSelected = 1;
        document.getElementById("springbtn").style.color = "#0000";
      }
      break;
      
    case "Summer 1":
      summer1BtnSelected = 0;
      document.getElementById("summer1btn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        summer1BtnSelected = 1;
        document.getElementById("summer1btn").style.color = "#0000";
      }
      
      break;
    
    case "Summer 2":
      summer2BtnSelected = 0;
      document.getElementById("summer2btn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        summer2BtnSelected = 1;
        document.getElementById("summer2btn").style.color = "#0000";
      }
      
      break;
    case "Summer 3":
      summer3BtnSelected = 0;
      document.getElementById("summer3btn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        summer3BtnSelected = 1;
        document.getElementById("summer3btn").style.color = "#0000";
      }
      break;
    case "Summer 4":
      summer4BtnSelected = 0;
      document.getElementById("summer4btn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        summer4BtnSelected = 1;
        document.getElementById("summer4btn").style.color = "#0000";
      }
      
      break;
    
    default:
      // code block
  }
}

function degreeOpsFunction(buttonindicator, selectedstatus){
  //alert(selectedstatus);
  if(degreeArr.includes(buttonindicator))
    degreeArr = degreeArr.filter(v => v !== buttonindicator); 
  else
    degreeArr.push(buttonindicator);

  switch(buttonindicator) {
    case "AS":
      asBtnSelected = 0;
      document.getElementById("asbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        asBtnSelected = 1;
        document.getElementById("asbtn").style.color = "#0000";
      }
      
      break;
    case "BA":
      baBtnSelected = 0;
      document.getElementById("babtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        baBtnSelected = 1;
        document.getElementById("babtn").style.color = "#0000";
      }
      break;
      
    case "BS":
      bsBtnSelected = 0;
      document.getElementById("bsbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        bsBtnSelected = 1;
        document.getElementById("bsbtn").style.color = "#0000";
      }
      
      break;
    
    case "EdD":
      eddBtnSelected = 0;
      document.getElementById("eddbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        eddBtnSelected = 1;
        document.getElementById("eddbtn").style.color = "#0000";
      }
      
      break;
    case "MA":
      maBtnSelected = 0;
      document.getElementById("mabtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        maBtnSelected = 1;
        document.getElementById("mabtn").style.color = "#0000";
      }
      break;
    case "MBA":
      mbaBtnSelected = 0;
      document.getElementById("mbabtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        mbaBtnSelected = 1;
        document.getElementById("mbabtn").style.color = "#0000";
      }
      
      break;
    case "MS":
      msBtnSelected = 0;
      document.getElementById("msbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        msBtnSelected = 1;
        document.getElementById("msbtn").style.color = "#0000";
      }
      break;
    case "PhD":
      phdBtnSelected = 0;
      document.getElementById("phdbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        phdBtnSelected = 1;
        document.getElementById("phdbtn").style.color = "#0000";
      }
      
      break;
    
    default:
      // code block
  }
}

function statusOpsFunction(buttonindicator, selectedstatus){
  //alert(selectedstatus);
  if(statusArr.includes(buttonindicator))
    statusArr = statusArr.filter(v => v !== buttonindicator); 
  else
    statusArr.push(buttonindicator);
    

  switch(buttonindicator) {
    case "I":
      internationalBtnSelected = 0;
      document.getElementById("internationalbtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        internationalBtnSelected = 1;
        document.getElementById("internationalbtn").style.color = "#0000";
      }
      
      break;
    case "O":
      outofStateBtnSelected = 0;
      document.getElementById("outofStatebtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        outofStateBtnSelected = 1;
        document.getElementById("outofStatebtn").style.color = "#0000";
      }
      break;
    case "N":
      noneBtnSelected = 0;
      document.getElementById("nonebtn").style.color = "#ffff";
      
      if(selectedstatus == 0){
        noneBtnSelected = 1;
        document.getElementById("nonebtn").style.color = "#0000";
      }
      
      break;
    
    default:
      // code block
  }
}

function collegeOpsFunction(buttonindicator, selectedstatus){
    //alert(selectedstatus);
    if(collegeArr.includes(buttonindicator))
      collegeArr = collegeArr.filter(v => v !== buttonindicator); 
    else
      collegeArr.push(buttonindicator);
    
    switch(buttonindicator) {
      case "Cyber College":
        cyberCollegeBtnSelected = 0;
        document.getElementById("cyberCollegebtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          cyberCollegeBtnSelected = 1;
          document.getElementById("cyberCollegebtn").style.color = "#0000";
        }
        
        break;
      case "College of Business":
        collegeOfBusinessBtnSelected = 0;
        document.getElementById("collegeOfBusinessbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          collegeOfBusinessBtnSelected = 1;
          document.getElementById("collegeOfBusinessbtn").style.color = "#0000";
        }
        break;
      case "College of Education":
        collegeOfEducationBtnSelected = 0;
        document.getElementById("collegeOfEducationbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          collegeOfEducationBtnSelected = 1;
          document.getElementById("collegeOfEducationbtn").style.color = "#0000";
        }
        
        break;
      case "College of Art and Science":
        collegeOfArtAndScienceBtnSelected = 0;
        document.getElementById("collegeOfArtAndSciencebtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          collegeOfArtAndScienceBtnSelected = 1;
          document.getElementById("collegeOfArtAndSciencebtn").style.color = "#0000";
        }
        break;
      
      default:
        // code block
    }

}

function majorOpsFunction(buttonindicator, selectedstatus){
    //alert(selectedstatus);
    if(majorArr.includes(buttonindicator))
      majorArr = majorArr.filter(v => v !== buttonindicator); 
    else
      majorArr.push(buttonindicator);
    
    switch(buttonindicator) {
      case "Computer Sc":
        computerScBtnSelected = 0;
        document.getElementById("computerScbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          computerScBtnSelected = 1;
          document.getElementById("computerScbtn").style.color = "#0000";
        }
        
        break;
      case "Information Sc":
        informationScBtnSelected = 0;
        document.getElementById("informationScbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          informationScBtnSelected = 1;
          document.getElementById("informationScbtn").style.color = "#0000";
        }
        break;
      case "Applied Sc":
        appliedScBtnSelected = 0;
        document.getElementById("appliedScbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          appliedScBtnSelected = 1;
          document.getElementById("appliedScbtn").style.color = "#0000";
        }
        
        break;
      case "Accounting":
        accountingBtnSelected = 0;
        document.getElementById("accountingbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          accountingBtnSelected = 1;
          document.getElementById("accountingbtn").style.color = "#0000";
        }
        break;
      case "Business Admin":
        businessAdminBtnSelected = 0;
        document.getElementById("businessAdminbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          businessAdminBtnSelected = 1;
          document.getElementById("businessAdminbtn").style.color = "#0000";
        }
        
        break;
      case "Economics":
        economicsBtnSelected = 0;
        document.getElementById("economicsbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          economicsBtnSelected = 1;
          document.getElementById("economicsbtn").style.color = "#0000";
        }
        break;
      case "Elementary Ed":
        elementaryEdBtnSelected = 0;
        document.getElementById("elementaryEdbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          elementaryEdBtnSelected = 1;
          document.getElementById("elementaryEdbtn").style.color = "#0000";
        }
        
        break;
      case "Secondary Ed":
        secondaryEdBtnSelected = 0;
        document.getElementById("secondaryEdbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          secondaryEdBtnSelected = 1;
          document.getElementById("secondaryEdbtn").style.color = "#0000";
        }
        break;
      case "Biology":
        biologyBtnSelected = 0;
        document.getElementById("biologybtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          biologyBtnSelected = 1;
          document.getElementById("biologybtn").style.color = "#0000";
        }
        
        break;
      case "Chemistry":
        chemistryBtnSelected = 0;
        document.getElementById("chemistrybtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          chemistryBtnSelected = 1;
          document.getElementById("chemistrybtn").style.color = "#0000";
        }
        break;
      default:
        // code block
    }

}

function dimensionOpsFunction(buttonindicator, selectedstatus){
    //alert(selectedstatus);
    if(dimensionArr.includes(buttonindicator))
      dimensionArr = dimensionArr.filter(v => v !== buttonindicator); 
    else
      dimensionArr.push(buttonindicator);
    
    switch(buttonindicator) {
      case "Student":
        studentBtnSelected = 0;
        document.getElementById("studentbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          studentBtnSelected = 1;
          document.getElementById("studentbtn").style.color = "#0000";
        }
        
        break;
      case "Time":
        timeBtnSelected = 0;
        document.getElementById("timebtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          timeBtnSelected = 1;
          document.getElementById("timebtn").style.color = "#0000";
        }
        break;
      
      default:
        // code block
    }

}

function olapOpsFunction(buttonindicator, selectedstatus){
    //alert(selectedstatus);
    if(olapOpsArr.includes(buttonindicator))
      olapOpsArr = olapOpsArr.filter(v => v !== buttonindicator); 
    else
      olapOpsArr.push(buttonindicator);
    
    switch(buttonindicator) {
      case "rollup":
        rollUpBtnSelected = 0;
        document.getElementById("rollupbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          rollUpBtnSelected = 1;
          document.getElementById("rollupbtn").style.color = "#0000";
        }
        
        break;
      case "rolldown":
        rollDownBtnSelected = 0;
        document.getElementById("rolldownbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          rollDownBtnSelected = 1;
          document.getElementById("rolldownbtn").style.color = "#0000";
        }
        break;
      case "dice":
        diceBtnSelected = 0;
        document.getElementById("dicebtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          diceBtnSelected = 1;
          document.getElementById("dicebtn").style.color = "#0000";
        }
        
        break;
      case "slice":
        sliceBtnSelected = 0;
        document.getElementById("slicebtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          sliceBtnSelected = 1;
          document.getElementById("slicebtn").style.color = "#0000";
        }
        break;
      case "pivot":
        pivotBtnSelected = 0;
        document.getElementById("pivotbtn").style.color = "#ffff";
        
        if(selectedstatus == 0){
          pivotBtnSelected = 1;
          document.getElementById("pivotbtn").style.color = "#0000";ß
        }
        break;
      default:
        // code block
    }

}




</script>

</head>
<body>
	<div id="header">
		
	</div>
	<div id="contents">
		<div class="clearfix">
			
			<form action="" method="post" class="message">
            
            <div class="main" style="width:100%; background-image:none;">
                
				<?php echo $error_msg; ?>
                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Olap Operations</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="olapOpsFunction('rollup',rollUpBtnSelected);" id="rollupbtn" class="more" >Roll Up</a></p>
                </div>
                <div style="float:left; padding-left:50px;">
                <p><a href="javascript:void(0)" onclick="olapOpsFunction('rolldown',rollDownBtnSelected);" id="rolldownbtn" class="more" >Roll Down</a></p>
                </div>
                <div style="float:left; padding-left:50px;">
                <p><a href="javascript:void(0)" onclick="olapOpsFunction('dice',diceBtnSelected);" id="dicebtn" class="more" >Dice</a></p>
                </div>
                <div style="float:left; padding-left:50px;">
                <p><a href="javascript:void(0)" onclick="olapOpsFunction('slice',sliceBtnSelected);" id="slicebtn" class="more">Slice</a></p>
                </div>
                <div style="float:left; padding-left:50px;">
                <p><a href="javascript:void(0)" onclick="olapOpsFunction('pivot',pivotBtnSelected);" id="pivotbtn" class="more">Pivot</a></p>
                </div>
                </div>
                <div class="clear"></div>

                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Dimensions</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="dimensionOpsFunction('Student',studentBtnSelected);" id="studentbtn" class="more" >Student</a></p>
                </div>
                <div style="float:left; padding-left:50px;">
                <p><a href="javascript:void(0)" onclick="dimensionOpsFunction('Time',timeBtnSelected);" id="timebtn" class="more">Time</a></p>
                </div>
                </div>
                <div class="clear"></div>
                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Major</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Computer Sc',computerScBtnSelected);" id="computerScbtn" class="more">Computer Sc</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Information Sc',informationScBtnSelected);" id="informationScbtn" class="more">Info. Sc</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Applied Sc',appliedScBtnSelected);" id="appliedScbtn" class="more">Applied Sc</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Accounting',accountingBtnSelected);" id="accountingbtn" class="more">Accounting</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Business Admin',businessAdminBtnSelected);" id="businessAdminbtn" class="more">Business Adm.</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Economics',economicsBtnSelected);" id="economicsbtn" class="more">Economics</a></p>
                </div>
                </div>

                <div class="clear"></div>
                <div>
                <div style="float:left;">
                <label style="width: 130px; display:inline-block;"></label>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Elementary Ed',elementaryEdBtnSelected);" id="elementaryEdbtn" class="more">Elementary</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Secondary Ed',secondaryEdBtnSelected);" id="secondaryEdbtn" class="more">Secondary</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Biology',biologyBtnSelected);" id="biologybtn" class="more">Biology</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="majorOpsFunction('Chemistry',chemistryBtnSelected);" id="chemistrybtn" class="more">Chemistry</a></p>
                </div>
                </div>
                <div class="clear"></div>

                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">College</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="collegeOpsFunction('Cyber College',cyberCollegeBtnSelected);" id="cyberCollegebtn" class="more">Cyber</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="collegeOpsFunction('College of Business',collegeOfBusinessBtnSelected);" id="collegeOfBusinessbtn" class="more">Business</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="collegeOpsFunction('College of Education',collegeOfEducationBtnSelected);" id="collegeOfEducationbtn" class="more">Education</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="collegeOpsFunction('College of Art and Science',collegeOfArtAndScienceBtnSelected);" id="collegeOfArtAndSciencebtn" class="more">Art & Science</a></p>
                </div>
                </div>
                <div class="clear"></div>
                
                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Status</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="statusOpsFunction('I',internationalBtnSelected);" id="internationalbtn" class="more">International</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="statusOpsFunction('O',outofStateBtnSelected);" id="outofStatebtn" class="more">Out of State</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="statusOpsFunction('N',noneBtnSelected);" id="nonebtn" class="more">None</a></p>
                </div>
                </div>
                <div class="clear"></div>
                
                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Degree</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('AS',asBtnSelected);" id="asbtn" class="more" style="width:90px" title="Associate degree">AS</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('BA',baBtnSelected);" id="babtn" class="more" style="width:90px" title="Bachelor of Art">BA</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('BS',bsBtnSelected);" id="bsbtn" class="more" style="width:90px" title="Bachelor of Science">BS</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('EdD',eddBtnSelected);" id="eddbtn" class="more" style="width:90px" title="Doctor of Education">EdD</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('MA',maBtnSelected);" id="mabtn" class="more" style="width:90px" title="Master of Art">MA</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('MBA',mbaBtnSelected);" id="mbabtn" class="more" style="width:90px" title="Master of Business Administration">MBA</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('MS',msBtnSelected);" id="msbtn" class="more" style="width:90px" title="Master of Science">MS</a></p>
                </div>
                <div style="float:left; padding-left:10px;">
                <p><a href="javascript:void(0)" onclick="degreeOpsFunction('PhD',phdBtnSelected);" id="phdbtn" class="more" style="width:90px" title="Doctor of Philosophy">PhD</a></p>
                </div>
                </div>
                <div class="clear"></div>
                
                <div>
                <div style="float:left;">
                <label style="width: 150px; display:inline-block;">Semester</label>
                </div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Fall semester',fallBtnSelected);" id="fallbtn" class="more" style="width:90px" title="Fall Semester">Fall</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Spring semester',springBtnSelected);" id="springbtn" class="more" style="width:90px" title="Spring Semester">Spring</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Summer 1',summer1BtnSelected);" id="summer1btn" class="more" style="width:90px" title="Summer 1">Summer 1</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Summer 2',summer2BtnSelected);" id="summer2btn" class="more" style="width:90px" title="Summer 2">Summer 2</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Summer 3',summer3BtnSelected);" id="summer3btn" class="more" style="width:90px" title="Summer 3">Summer 3</a></p>
                </div>
                <div style="float:left; padding-left:20px;">
                <p><a href="javascript:void(0)" onclick="semesterOpsFunction('Summer 4',summer4BtnSelected);" id="summer4btn" class="more" style="width:90px" title="Summer 4">Summer 4</a></p>
                </div>
                </div>
                <div class="clear"></div>
                
                <label style="width: 150px; display:inline-block;">Name</label>
                <input class="textbox" type="text" name="txtStdName" id="txtStdName" value="" style="width:400px;">
                <br>
                <label style="width: 150px; display:inline-block;">Address</label>
                <input class="textbox" type="text" name="txtAddress" id="txtAddress" value="" style="width:400px;">
                <br>
                <label style="width: 150px; display:inline-block;">Year</label>
                <input class="textbox" type="text" name="txtYear" id="txtYear" value="" style="width:400px;">
                <p>you can add multiple years as comma separated list*</p>

                <div style="float:left;padding-top:10px;"><label style="width: 155px; display:inline-block;" >GPARange</label></div>
                <div style="float:left;">
                <select id="gparangetype" name="gparangetype" onselect="showCustomField()">
                    <option value="-1">Select GPA Range</option>
                    <option value="high" <?php if($gparType == "high") echo "selected=\"selected\"" ?>>High</option>
                    <option value="medium" <?php if($gparType == "medium") echo "selected=\"selected\"" ?>>Medium</option>
                    <option value="low" <?php if($gparType == "low") echo "selected=\"selected\"" ?>>Low</option>
                </select>
                </div>
				        <div class="clear"></div>
              	<br>
                
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="generateOlapSeq();" class="more">Olap Seq.</a></p>
                </div>
                <div class="clear"></div>
                <div style="float:left;">
                <p><label for="dwreview">Generated Olap Seq:</label></p>
                <textarea id="dwreview" name="dwreview" rows="4" cols="50"></textarea>
                </div>

                <div class="clear"></div>
              	<br>
                
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="generateSQL();" class="more">SQL Ops.</a></p>
                </div>
                <div class="clear"></div>
                <div style="float:left;">
                <p><label for="sqlreview">Generated SQL:</label></p>
                <textarea id="sqlreview" name="sqlreview" rows="4" cols="50"></textarea>
                </div>
                <div class="clear"></div>
                <div style="float:left;">
                <p><a href="javascript:void(0)" onclick="executeSQL();" class="more">Execute</a></p>
                </div>
                <div class="clear"></div>
                <p><label id="resultcount">Number of Graduates: </label></p>


		</div>
            </form>
		</div>
	</div>
</body>
</html>