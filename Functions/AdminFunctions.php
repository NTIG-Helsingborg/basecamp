<?php
require_once("../backend/db.php");
header("Content-Type: application/json");




/*
    
    $postData = file_get_contents("php://input");      får datan i body skickad från en request
    $jsonData = json_decode($postData, true);          Gör om sträng till ett json object
    echo json_encode($response);                       eftersom header är satt till json blir det som echoas respons, json_encode($var) ger en sträng som kan decodas på frontend


*/


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = file_get_contents("php://input");       //Tar emot post datan som skickas i en assioativ array
    //print_r($postData);
    $jsonData = json_decode($postData, true);
    $isChecked = isset($jsonData["isChecked"]) && $jsonData["isChecked"];
    // Prepare the response data as an associative array
    $response = [
        "isChecked" => $isChecked,
    ];
    //använd också en session variabel som kontrollerar så att det är en admin som gör requesten
    if (isset($isChecked) && !isset($jsonData["deleteVar"]) && !isset($jsonData["addschool"])) {
        $statement = "SELECT id FROM users WHERE mail = :mail";
        $argmail = new QueryArgsStruct(":mail", $jsonData["mail"], SQLITE3_TEXT);
        $res = $db->run_query($statement, $argmail);
        $resId = $res->fetchArray(SQLITE3_ASSOC);

        $statement = "UPDATE users SET admin = :admin WHERE id = :id";
        $argAdmin = new QueryArgsStruct(":admin", $isChecked ? 1 : 0, SQLITE3_INTEGER);
        $argId = new QueryArgsStruct(":id", $resId["id"], SQLITE3_TEXT);
        $res = $db->run_query($statement, $argAdmin, $argId);
    }

    if (isset($jsonData["deleteVar"])) {
        $statementDeleteUsers = "DELETE FROM users WHERE id = :id";
        $statementDeleteClasses = "DELETE FROM classes WHERE owner = :owner";
        $statementDeleteChapters = "DELETE FROM chapters WHERE owner =:owner";
        print_r($_SESSION["Userlist"]);
        foreach ($_SESSION["Userlist"] as $key => $value) {
            if ($value["admin"] == 0) {
                echo $value["admin"];

                $argOwnerChapters = new QueryArgsStruct(":owner", $value["id"], SQLITE3_TEXT);
                $resChapters = $db->run_query($statementDeleteChapters, $argOwnerChapters);
                $chapterDeleted = $resChapters->fetchArray(SQLITE3_ASSOC);

                $argOwnerClasses = new QueryArgsStruct(":owner", $value["id"], SQLITE3_TEXT);
                $resClasses = $db->run_query($statementDeleteClasses, $argOwnerClasses);
                $classesDeleted =  $resClasses->fetchArray(SQLITE3_ASSOC);

                $argIdUsers = new QueryArgsStruct(":id", $value["id"], SQLITE3_TEXT);
                $resUsers = $db->run_query($statementDeleteUsers, $argIdUsers);
                $classesDeleted =  $resUsers->fetchArray(SQLITE3_ASSOC);
                header("Refresh: 0");
            }
        }
    }
    if (isset($jsonData["addschool"])) {
        $id = bin2hex(random_bytes(20));
        $statement =  "INSERT INTO schools(id, name) VALUES(:id, :name)";
        $argId = new QueryArgsStruct(":id", $id, SQLITE3_TEXT);
        $argName = new QueryArgsStruct(":name", $jsonData["school"], SQLITE3_TEXT);
        $result = $db->run_query($statement, $argId, $argName);
    }
    // Convert the associative array to JSON and echo it,
    // Vilket skickar tillbaka responsen till client sidan
    echo json_encode($response);
}
