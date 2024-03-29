<?php
if (!class_exists("SQLite3"))
    exit("This PHP environment does not have SQLite3 support builtn in.");
// DB properties.
// define("db_HOST", "localhost");
// define("db_USER", "admin2@t301006");
// define("db_PASS", "Wassim_?321");
// define("db_NAME", "te4ntihbg_se");

class QueryArgsStruct
{
    public $name;
    public $value;
    public $type;

    function __construct($a1, $a2, $a3)
    {
        $this->name = $a1;
        $this->value = $a2;
        $this->type = $a3;
    }
}

class DBClass extends SQLite3
{
    function __construct()
    {
        //unlink("database.db");
        $this->open($_SERVER['DOCUMENT_ROOT'] . "/backend/database.db");

        $this->exec("
                CREATE TABLE IF NOT EXISTS `schools` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `name` TEXT NOT NULL
                )
            ");

        $this->exec("
                CREATE TABLE IF NOT EXISTS `users` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `mail` TEXT NOT NULL,
                    `name` TEXT NOT NULL,
                    `password_hash` TEXT NOT NULL,
                    `school` TEXT NOT NULL REFERENCES `schools`(`id`),
                    `admin` TINYINT NOT NULL
                )
            ");

        $this->exec("
                CREATE TABLE IF NOT EXISTS `classes` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `owner` TEXT NOT NULL REFERENCES `users`(`id`),
                    `name` TEXT NOT NULL,
                    `data` TEXT,
                    `school` TEXT NOT NULL REFERENCES `schools`(`id`)
                )
            ");

        $this->exec("
                CREATE TABLE IF NOT EXISTS `chapters` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `owner` TEXT NOT NULL REFERENCES `users`(`id`),
                    `class` TEXT NOT NULL REFERENCES `classes`(`id`),
                    `data` TEXT NOT NULL,
                    `url` TEXT,
                    `name` TEXT NOT NULL
                )
            ");
        //test admin, dont know what do if there is more admins. Should we have a super admin lol?
        $StatementK1 = $this->querySingle("SELECT id from users WHERE mail = 'Admin@Admin.Admin'");
        if (!$StatementK1) {
            $idschool = bin2hex(random_bytes(20));
            $idschool2 = bin2hex(random_bytes(20));
            $idClass = bin2hex(random_bytes(20));

            $adminid = (string) uniqid();
            $passwordAdmin = password_hash("Veryynice123!", PASSWORD_DEFAULT);
            $passwordJosef = password_hash("Josef123!", PASSWORD_DEFAULT);      //Extra account for testing
            $this->exec("INSERT INTO schools(id, name) VALUES('$idschool', 'NTI-Helsingborg')");
            $this->exec("INSERT INTO schools(id, name) VALUES('$idschool2', 'NTI-Vetenskap')");
            $this->exec("INSERT INTO users(id, mail, name, password_hash, school, admin) VALUES('$adminid', 'Admin@Admin.Admin', 'Admin', '$passwordAdmin', '$idschool', 1)");
            $this->exec("INSERT INTO users(id, mail, name, password_hash, school, admin) VALUES('01', 'Josef@Nobach.Admin', 'Admin', ' $passwordJosef', '$idschool', 1)");      //Extra account for testing


            $adminName = "Admin@Admin.Admin";
            // echo "<br>";
            // echo $idClass;
            // echo "<br>";
            // echo $adminName;
            // echo "<br>";
            // echo $idschool;

            $this->exec("INSERT INTO classes(id, owner, name, data, school) VALUES('$idClass', '$adminName', 'Programmering 1', 'genomgångar för programmering 1', '$idschool')");
            //för att lägga in variablar använd '$var' 
        }
    }

    //går igenom varje arg som har matats in i funktionen, första Query object skickas och dess värden name, value, type accessas och bind med bindValue
    function run_query(string $query, QueryArgsStruct ...$args)
    {
        $stmt = $this->prepare($query);
        //var_dump($args);
        foreach ($args as $arg) {
            //var_dump($arg);
            $stmt->bindValue($arg->name, $arg->value, $arg->type);
            //echo($arg->value);
        }
        return $stmt->execute();
    }
    //pending_users inte tillagd än email, kolumnen inte rikitgt nödvändig eftersom användaren måste använda mail som mail
    function add_pending_user($mail, $email, $password, $school)
    {
        $user_id = bin2hex(random_bytes(20));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $temp_query = "INSERT INTO `pending_users`(id, mail, email, password_hash, school) VALUES(:id, :mail, :email, :password_hash, :school)";

        return $this->run_query(
            $temp_query,
            new QueryArgsStruct(":id", $user_id, SQLITE3_TEXT),
            new QueryArgsStruct(":mail", $mail, SQLITE3_TEXT),
            new QueryArgsStruct(":email", $email, SQLITE3_TEXT),
            new QueryArgsStruct(":password_hash", $hashed_password, SQLITE3_TEXT),
            new QueryArgsStruct(":school", $school, SQLITE3_TEXT)
        );
    }

    /**
     * Adds user to the users table
     */
    function add_user($mail, $email, $hashed_password, $school, $is_admin = 0)
    {
        $user_id = bin2hex(random_bytes(20));

        $temp_query = "INSERT INTO `users`(id, mail, email, password_hash, school, admin) VALUES(:id, :mail, :email, :password_hash, :school, :admin)";

        return $this->run_query(
            $temp_query,
            new QueryArgsStruct(":id", $user_id, SQLITE3_TEXT),
            new QueryArgsStruct(":mail", $mail, SQLITE3_TEXT),
            new QueryArgsStruct(":email", $email, SQLITE3_TEXT),
            new QueryArgsStruct(":password_hash", $hashed_password, SQLITE3_TEXT),
            new QueryArgsStruct(":school", $school, SQLITE3_TEXT),
            new QueryArgsStruct(":admin", $is_admin, SQLITE3_TEXT)
        );
    }

    /**
     * Returns all pending users
     */
    function get_pending_users()
    {
        return $this->run_query("
                 SELECT id, mail, name, password_hash, school FROM `pending_users`
             ");
    }
    function get_users()
    {
        return $this->run_query("
            SELECT id, mail, name, password_hash, school, admin FROM `users`
        ");
    }
}


$db = new DBClass();
/*
if (!$db)
{
   echo $db->lastErrorMsg();
}
else
{
   echo "Opened database.db successfully!";
}
*/
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    //To Be Displayed in dropdown
    $resultSchools = $db->query("SELECT id, name FROM schools");
    $i = 0;
    $_SESSION["schoolDisplay"] = array();
    while ($res = $resultSchools->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION["schoolDisplay"][$i]["name"] = $res["name"];
        $i++;
    }
    //fetchArray ger bara resultat för en rad, därför behövs en while loop som får ut varje rad
    $c = 0;
    $resultClasses = $db->query("SELECT * FROM classes");
    $_SESSION["classDisplay"] = array();
    while ($res1 = $resultClasses->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION["classDisplay"][$c]["id"] = $res1["id"];
        $_SESSION["classDisplay"][$c]["owner"] = $res1["owner"];
        $_SESSION["classDisplay"][$c]["name"] = $res1["name"];
        $_SESSION["classDisplay"][$c]["data"] = $res1["data"];
        $_SESSION["classDisplay"][$c]["school"] = $res1["school"];
        $c++;
    }

    $l = 0;
    $resultChapters = $db->query("SELECT * FROM chapters");
    $_SESSION["chapterDisplay"] = array();
    while ($res4 = $resultChapters->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION["chapterDisplay"][$l]["id"] = $res4["id"];
        $_SESSION["chapterDisplay"][$l]["owner"] = $res4["owner"];
        $_SESSION["chapterDisplay"][$l]["class"] = $res4["class"];
        $_SESSION["chapterDisplay"][$l]["data"] = $res4["data"];
        $_SESSION["chapterDisplay"][$l]["url"] = $res4["url"];
        $_SESSION["chapterDisplay"][$l]["name"] = $res4["name"];
        $l++;
    }

    $_SESSION["onPage"] = isset($_COOKIE["await"]) ? true : false;
    //Default choosen school för kurser
    $resultDef = $db->query("SELECT id FROM schools WHERE name = 'NTI-Helsingborg'");
    $def = $resultDef->fetchArray(SQLITE3_ASSOC);
    $_SESSION["SchoolDefault"] = $def["id"];
}
