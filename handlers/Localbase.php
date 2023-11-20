<?php



class Localbase
{
    private $db;
    public $chat_id;
    public $user_id;
    function __construct($chat_id)
    {
        $this->chat_id = $chat_id;
        $this->connect();
    }

    function connect()
    {
        try {
            $dbPath = 'db/users.db';
            $pdo = new PDO('sqlite:' . $dbPath);
            // return "success";
        } catch (PDOException $e) {
            echo "Error connecting to database: " . $e->getMessage();
            die();
        }
        // return 'ok';
        $this->db = $pdo;
    }

    function get_user()
    {
        return $this->db->query("SELECT * FROM users WHERE chat_id = '{$this->chat_id}'")->fetch(PDO::FETCH_ASSOC);
    }

    public function create_user($name, $step, $chatId, $userId, $lang='uz', $phoneNumber = null, $code = null, $categoryId = null, $sectionId = null, $childSectionId = null, $productId = null, $profile = null)
    {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Prepare the SQL statement
        $sql = "INSERT INTO users (name, step, lang, chat_id, user_id, phone_number, code, category_id, section_id, childsection_id, product_id, profile, created_at, updated_at)
                VALUES (:name, :step, :lang, :chatId, :userId, :phoneNumber, :code, :categoryId, :sectionId, :childSectionId, :productId, :profile, :created_at, :updated_at)";

        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':step', $step);
        $stmt->bindParam(':lang', $lang);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->bindParam(':sectionId', $sectionId);
        $stmt->bindParam(':childSectionId', $childSectionId);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':profile', $profile);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);

        // Execute the statement
        $stmt->execute();
    }

    public function updateUser($update_datas)
    {
        // Prepare the SQL statement
        $sql = "UPDATE users
                SET {$update_datas}
                WHERE chat_id = :id";

        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':id', $this->chat_id);

        // Execute the statement
        $stmt->execute();
        return "ok";
    }

    public function deleteuser(){
        $this->db->query("DELETE FROM users WHERE chat_id = '{$this->chat_id}'");
        return "okey";
    }
}

// $localbase = new Localbase();