<?php
namespace App;

/**
 * SQLite connection
 */
class SQLiteConnection {
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:".PATH_TO_SQLITE_FILE.".sqlite3");
        }
        return $this->pdo;
    }

    public function create_base_tables(){
        //Create employees table
        $file_db->exec("CREATE TABLE IF NOT EXISTS employees (
            id INTEGER PRIMARY KEY, 
            full_name TEXT, 
            join_date INTEGER)");

        //Create designations table
        $file_db->exec("CREATE TABLE IF NOT EXISTS designations (
            id INTEGER PRIMARY KEY, 
            employees_id INTEGER UNIQUE, 
            designation TEXT)");
    }
}