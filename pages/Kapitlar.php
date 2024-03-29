<?php
include "../videotest.php";
?>

<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="utf-8">
  <title>Kapitlar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- CSS-fil -->
  <link rel="stylesheet" href="../CSS/baseCamp.css">
  <link rel="stylesheet" href="../CSS/specifikkurs.css">
  <link rel="stylesheet" href="../CSS/kapitlar.css">
  <link rel="stylesheet" href="../CSS/navbarbackground.css">
</head>

<body>
  <header>
    <?php include '../Components/Navbar.php'; ?>
  </header>
  <div class="course-title">
    <div class="container">
      <?php
          echo '
          <h1>Welcome to '.$_SESSION["selectedClass"]["name"].'</h1>
          ';
      ?>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et ante non metus vehicula pulvinar in sit
        amet ipsum</p>
    </div>
  </div>
  <div id="sidebar" class="sideBar">
    <h1>Skolor <button data-bs-toggle="collapse" data-bs-target="#demo1" class="showlinks" aria-expanded="false"><i
          class="fa fa-chevron-down"></i></button></h1>
    <div id="demo1" class="collapse" aria-labelledby="demo1">
      <!--
         <li><a href="#">Länk 1</a></li>  x antal gångaer
        
         Nedanstående får ut alla skolor, session variabel finns i db.php
      -->
      <ul>
        <?php
          foreach($_SESSION["schoolDisplay"] as $key=>$value){
            echo  '
            <li><a href="#" id = "'.$value["name"].'" onclick = "schoolChooseFetch(this.id)">' . $value["name"] .'</a></li>
            ';
          }
        ?>
      </ul>
    </div>
  </div>
  <button onClick="ShowSideBar()" class="showsideBtn" id="showsidebtnID"><i class="fa fa-chevron-right"></i></button>


  <!-- 12 Content boxes -->
  <div class="" id="box-container">
    <!-- Första gruppen med boxar -->
    <div class="row box-group" id="group1">
      <?php
        //Loopars igenom en session variabel som finns i db.php som får alla chapters och dess kolumner
        foreach($_SESSION["chapterDisplay"] as $key=>$value){
          //selected class sätts i kurserFunctions.php
          if(isset($_SESSION["selectedClass"])){
            if($_SESSION["selectedClass"]["id"] == $value["class"]){
              //eftersom '' används och functionen som finns onclick använder av "" så använder vi oss av \' för att kunna skriva in single qoutes
              echo '<div class = "col-lg-4 col-md-6 col-sm-6" id = "'.$value["id"].'"' . 'onclick = "chooseChapter(\''.$value["id"].'\', \''.$value["class"].'\', \''.$value["data"].'\', \''.$value["url"].'\', \''.$value["name"].'\');">';
              echo '<div class = "box">';   
              echo '<button class = "deleteBtn" onclick = "deleteClass(\''.$value["id"].'\');">X</button>';
              echo '<h4>'.$value["name"].'</h4>'; // "." Konjugerar ihop strängar
              echo '</div>';
              echo "</div>";
          }
        }
      }
      ?>
    </div>

    <?php
    if(isset($_SESSION["loginStatus"]) && $_SESSION["loginStatus"]){
        echo '
        <button class="circular-button" onclick="addNewBoxToDb()"></button>
        ';
    }
?>
    <!--
    tidigare kod:
    <div class="button-container d-flex flex-column flex-sm-row ">
      <button id="buttonLeft" class="rounded-button left p-1 p-sm-4 my-3 my-sm-5"><i
          class="fa fa-chevron-left"></i>Webbutveckling
        1</button>
      <button id="buttonRight" class="rounded-button right p-1 p-sm-4 my-3 my-sm-5">Programmering 2<i
          class="fa fa-chevron-right"></i></button>
    </div>
  

    knappar som väljer nästa kurs
    
    håller id för vald class
    $_SESSION["selectedClass"]

    $query = "SELECT name FROM schools WHERE name > :name ORDER BY name ASC LIMIT 1";
    SELECT id FROM courses WHERE id < :currentCourseId ORDER BY id DESC LIMIT 1
    --> 
    <?php
      
      $statementAsc = "SELECT name FROM classes WHERE id > :id ORDER BY id ASC LIMIT 1";
      $argAsc= new QueryArgsStruct(":id", $_SESSION["selectedClass"]["id"], SQLITE3_TEXT);
      $resAsc = $db->run_query($statementAsc, $argAsc);
      $Asc = $resAsc->fetchArray(SQLITE3_ASSOC);
      
      $statementDesc = "SELECT name FROM classes WHERE id < :id ORDER BY id DESC LIMIT 1";
      $argDesc= new QueryArgsStruct(":id", $_SESSION["selectedClass"]["id"], SQLITE3_TEXT);
      $resDesc = $db->run_query($statementDesc, $argDesc);
      $Desc = $resDesc->fetchArray(SQLITE3_ASSOC);
      
      echo '<div class="button-container d-flex flex-column flex-sm-row ">';
      for($q = 0; $q < count($_SESSION["classDisplay"]); $q++){
        if($_SESSION["classDisplay"][$q]["id"] == $_SESSION["selectedClass"]["id"]){
            if(isset($_SESSION["classDisplay"][$q-1]["name"])){
              $prevData = json_encode($_SESSION["classDisplay"][$q-1]);
              //htmlspecialchars eftersom json_encode() generar double qoutes  
              echo '
              <button id="buttonLeft" class="rounded-button left p-1 p-sm-4 my-3 my-sm-5" onclick = "changeClass(\''.htmlspecialchars($prevData, ENT_QUOTES, 'UTF-8').'\')"><i
              class="fa fa-chevron-left"></i>
              ';
              echo $_SESSION["classDisplay"][$q-1]["name"];
              echo '</button>';
            }
            
            if(isset($_SESSION["classDisplay"][$q+1]["name"])){
              $nextData = json_encode($_SESSION["classDisplay"][$q+1]);
              echo '
              <button id="buttonRight" class="rounded-button right p-1 p-sm-4 my-3 my-sm-5" onclick = "changeClass(\''.htmlspecialchars($nextData, ENT_QUOTES, 'UTF-8').'\')">';
              echo $_SESSION["classDisplay"][$q+1]["name"];
              echo'
              <i class="fa fa-chevron-right"></i></button>
              ';
            }

        }
      }
      /*
      if($Desc){
        echo '
        <button id="buttonLeft" class="rounded-button left p-1 p-sm-4 my-3 my-sm-5"><i
        class="fa fa-chevron-left"></i>
        ';
        echo $Desc["name"];
        echo '</button>';
      }
      if($Asc){
          echo '
          <button id="buttonRight" class="rounded-button right p-1 p-sm-4 my-3 my-sm-5">';
          echo $Asc["name"];
          echo'
          <i class="fa fa-chevron-right"></i></button>
          ';
      }
      */
      
    ?>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <script>


  </script>
  <script>
    $(document).ready(function () {
      $('.collapse').on('shown.bs.collapse', function () {
        $(this).prev().find('.showlinks').html('<i class="fa fa-chevron-up"></i>');
      }).on('hidden.bs.collapse', function () {
        $(this).prev().find('.showlinks').html('<i class="fa fa-chevron-down"></i>');
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
          $('#sidebar, #showsidebtnID').addClass('sidebarScroll');
        } else {
          $('#sidebar, #showsidebtnID').removeClass('sidebarScroll');
        }
      });
    });
  </script>
  <script>
    function ShowSideBar() {
      document.getElementById("sidebar").classList.toggle("showsidebar");
      document.getElementById("showsidebtnID").classList.toggle("showsideBtnToggle");
    }
  </script>
  <script>
    //vill inte riktigt ändra i databasen
    function addNewBoxToDb(){
      //var rubrik = prompt("Ange rubrik för det nya kapitlet!");
      window.location.href = '../formtest.php';
    }

    function addNewBox() {
      var boxTitle = prompt("Ange rubrik för det nya kapitlet!");
      var boxDescription = prompt("Ange en kort beskrivning för det nya kapitlet!");

      var newBox = document.createElement('div');
      newBox.className = 'col-lg-4 col-md-6 col-sm-6';

      var boxInner = document.createElement('div');
      boxInner.className = 'box';
      boxInner.innerHTML = '<h4>' + boxTitle + '</h4><p>' + boxDescription + '</p>';

      var deleteButton = document.createElement('button');
      deleteButton.className = 'deleteBtn'; // Lägg till Bootstrap-klass för knappstilen
      deleteButton.innerHTML = 'X';
      deleteButton.onclick = function () {
        // Ta bort den aktuella boxen när knappen klickas på
        newBox.remove();
      };

      // Lägg till "Ta bort" knappen i den nya boxen
      boxInner.appendChild(deleteButton);

      // Lägg till boxens innehåll i den yttre boxen
      newBox.appendChild(boxInner);

      var container = document.getElementById('group1');

      container.appendChild(newBox);



      window.location.href = '../editchapter.php';
    }

    //användes från början kurser.php filen, kanske inte nödvändigt här eftersom det är bättre att ha en dropdown med skolor
    function schoolChooseFetch(id){
      fetch("../kurserFunctions.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
              school: id,
            })
        })
        .then(response => response.text())
        .then(() => {
          location.reload();
        })
      }

  function deleteClass(id){
    console.log(id);
    fetch("../kapitlarFunctions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        class: id,
      })
    })
    .then(response => response.text())
    .then(data =>{
    location.reload();
        });
    event.stopPropagation();
    }
    

    function changeClass(args){
      fetch("../kapitlarFunctions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        classArgs: JSON.parse(args),
      })
    })
    .then(response => response.text())
    .then(data =>{
      location.reload();
    });
    }



    function chooseChapter(id, Class, data, url, name){
      fetch("../kapitlarFunctions.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
              chapterArr: "set",
              id: id,
              class: Class,
              data: data,
              url: url,
              name: name,
            })
        })
        .then(response => response.text())
        .then(data =>{
          window.location.href = '../editchapter.php';
        });
        //hindrar parent för att aktiveras
        event.stopPropagation();
    }
  </script>

</body>

</html>