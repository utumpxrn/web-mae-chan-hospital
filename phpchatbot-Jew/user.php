<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow all origins for testing
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Exit for preflight request
}

// Connect to the database
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Handle request based on method
switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo) {
    try {
        $sql = "SELECT * FROM users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch data', 'details' => $e->getMessage()]);
    }
}

function handlePost($pdo, $input) {
    try {
        if (!isset($input['Name']) || !isset($input['Line_name']) || !isset($input['Role'])) {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['message' => 'Missing required fields']);
            return;
        }

        $name = $input['Name'];
        $line_name = $input['Line_name'];
        $role = $input['Role'];

        $sql = "INSERT INTO users (Name, Line_name, Role) VALUES (:name, :line_name, :role)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':line_name', $line_name);
        $stmt->bindParam(':role', $role);

        $stmt->execute();

        http_response_code(201); // 201 Created
        echo json_encode(['message' => 'User created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data', 'details' => $e->getMessage()]);
    }
}
function handlePut($pdo, $input) {
    try{
        if (!isset($input['Name']) || !isset($input['Line_name']) || !isset($input['Role'])) {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['message' => 'Missing required fields']);
            return;
        }
        $name = $input['Name'];
        $line_name = $input['Line_name'];
        $role = $input['Role'];
        $id = $input['ID'];

        $sql = "UPDATE users set Name=:name, Line_name=:line_name, Role=:role where ID=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':line_name', $line_name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        http_response_code(201); // 201 Created
        echo json_encode(['message' => 'User created successfully']);

    }catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data', 'details' => $e->getMessage()]);
    }

}
function handleDelete($pdo, $input) {
    try{
        $id = $input['ID'];

        $sql = "DELETE From users where ID=:id";
        $stmt = $pdo->prepare($sql);

        
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        http_response_code(201); // 201 Created
        echo json_encode(['message' => 'User created successfully']);

    }catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data', 'details' => $e->getMessage()]);
    }

}
?>