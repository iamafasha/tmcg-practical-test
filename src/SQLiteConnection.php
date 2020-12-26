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


    private $initial_data = array (
        array( 
            "id"=>1,
            "full_name"=>"Nabimanya Nelson John Paul",
            "start_date"=>"12th dec, 2019",
            "designation" =>"Manager"
        ),
        array( 
            "id"=>2,
            "full_name"=>"Kenneth Ojakol",
            "start_date"=>"13/06/2020",
            "designation" =>"backend developer"
        ),
        array( 
            "id"=>3,
            "full_name"=>"Thomas Kyamagero,",
            "start_date"=>"14th oct, 2020",
            "designation" =>"accountant,"
        ),
        array( 
            "id"=>4,
            "full_name"=>"Paul Nabimanya",
            "start_date"=>"4 years ago",
            "designation" =>"director of operations"
        ),
        array( 
            "id"=>5,
            "full_name"=>"Kyamagero Paul",
            "designation" =>"Network Manager"
        ),
        array( 
            "id"=>6,
            "full_name"=>"SSali Peter",
            "designation" =>"I.T"
        ), 
        array( 
            "id"=>7,
            "full_name"=>"Zizinga Pius",
            "designation" =>"Finance team lead"
        ),
        array( 
            "id"=>7,
            "full_name"=>"Zizinga Pius",
            "start_date"=>"last month",
            "designation" =>"Finance Manger"
        ),
        array( 
            "id"=>8,
            "full_name"=>"Jalia Nabukalu Esther",
            "start_date"=>"28th sep, 2020",
            "designation" =>"Systems admin Intern"
        ),
        array( 
            "id"=>9,
            "full_name"=>"John Zizinga",
            "start_date"=>"1st Jan 2021",
            "designation" =>"backend developer"
        ),
        array( 
            "id"=>10,
            "full_name"=>"Sharon Opoka",
            "designation" =>"communications manager"
        ),
        array( 
            "id"=>11,
            "full_name"=>"Nabimanya Paul",
            "designation" =>"assistant director of operations"
        ),
        array( 
            "id"=>12,
            "full_name"=>"Ojakol Kenneth",
            "designation" =>"backend developer"
        ),
        array( 
            "id"=>13,
            "full_name"=>"Opoka Jane Sharon",
            "designation" =>"general caretaker"
        ),
        array( 
            "id"=>14,
            "full_name"=>"Kikoyo Paul",
            "designation" =>"front end developer"
        ),
        array( 
            "id"=>15,
            "full_name"=>"Esther Nabukalu",
            "start_date"=>"1st Jan 2021",
            "designation" =>"Graphics Designer"
        ),
      );


    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:".PATH_TO_SQLITE_FILE.".sqlite3");
            $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }



        try {
            //checks if it's a new database
            $this->pdo->query("SELECT 1 FROM designations LIMIT 1");
        } catch (\Throwable $th) {
            $this->create_base_tables();
            $this->add_base_table_data();
        }
        return $this->pdo;
    }


    public function insert_employees($employee) {
        $id=$employee['id'];
        $full_name=$employee['full_name'];

        $sql = 'INSERT INTO employees ( id , full_name ) VALUES ( :id, :full_name)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':full_name', $full_name);
        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->pdo->lastInsertId();
    }

    public function insert_designations($employee) {
        $employee_id=$employee['id'];
        $name=$employee['designation'];
        $start_date=strtotime($employee['start_date']);
        $sql = 'INSERT INTO designations ( employees_id , name , start_date) VALUES ( :employee_id, :name, :start_date)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':employee_id', $employee_id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':start_date', $start_date);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function create_base_tables(){

        //Create employees table
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS employees (
            id   INTEGER PRIMARY KEY,
            full_name TEXT
        )');

        //Create designations table
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS designations (
            id INTEGER PRIMARY KEY, 
            employees_id INTEGER, 
            name TEXT,
            start_date INTEGER DEFAULT '.strtotime("1st Jan 2012").' ,
            FOREIGN KEY (employees_id)
            REFERENCES employees (id) ON UPDATE CASCADE ON DELETE CASCADE
            )');
    }

    private function add_base_table_data($data = null ){
        if($data === null){
            foreach ($this->initial_data as $employee) {
                $this->insert_employees($employee);
                $this->insert_designations($employee);
            } 
        }
    }
}