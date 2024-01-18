<?php
    if(!class_exists("SQLite3"))
        exit("This PHP environment does not have SQLite3 support builtn in.");
    // DB properties.
    // define("db_HOST", "localhost");
    // define("db_USER", "admin2@t301006");
    // define("db_PASS", "Wassim_?321");
    // define("db_NAME", "te4ntihbg_se");

    class QueryArgsStruct {
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
            $this->open("database.db");

            $this->exec("
                CREATE TABLE IF NOT EXISTS `schools` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `name` TEXT NOT NULL
                )
            "); 

            $this->exec("
                CREATE TABLE IF NOT EXISTS `users` (
                    `id` TEXT PRIMARY KEY NOT NULL,
                    `username` TEXT NOT NULL,
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
                    `class` TEXT NOT NULL REFERENCES `classes`(`id`),
                    `data` TEXT,
                    `name` TEXT NOT NULL
                )
            ");
            //test admin, dont know what do if there is more admins. Should we have a super admin lol?
            $StatementK1 = $this->querySingle("SELECT id from users WHERE username = 'Admin'");
            if(!$StatementK1){
                $idschool = bin2hex(random_bytes(20));
                $idschool2 = bin2hex(random_bytes(20));
                $adminid = (string)uniqid();
                $passwordAdmin = password_hash("Veryynice123!", PASSWORD_DEFAULT);
                $this->exec("INSERT INTO schools(id, name) VALUES('$idschool', 'NTI-Helsingborg')");
                $this->exec("INSERT INTO schools(id, name) VALUES('$idschool2', 'NTI-Vetenskap')");
                //för att lägga in variablar använd '$var' 
                $this->exec("INSERT INTO users(id, username, name, password_hash, school, admin) VALUES('$adminid', 'Admin', 'Admin', '$passwordAdmin', '$idschool', 1)");
            }
    }

        //går igenom varje arg som har matats in i funktionen, första Query object skickas och dess värden name, value, type accessas och bind med bindValue
        function run_query(string $query, QueryArgsStruct ...$args)
        {
            $stmt = $this->prepare($query);
            //var_dump($args);
            foreach($args as $arg)
            {
                //var_dump($arg);
                $stmt->bindValue($arg->name, $arg->value, $arg->type);
                //echo($arg->value);
            }
            return $stmt->execute();
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
        $resultSchools = $db->query("SELECT name FROM schools");
        $i = 0;
        $_SESSION["schoolDisplay"] = array();
        while($res = $resultSchools->fetchArray(SQLITE3_ASSOC)){
            $_SESSION["schoolDisplay"][$i]["name"] = $res["name"];
            $i++;
        }
        $_SESSION["onPage"] = isset($_COOKIE["await"]) ? true : false; 
    }
?>