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
        handleEdit($pdo, $input);
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
        if (!isset($input['Name']) || !isset($input['Role'])) {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['message' => 'Missing required fields']);
            return;
        }

        $name = $input['Name'];
        $role = $input['Role'];

        $sql = "INSERT INTO users (Name, Role) VALUES (:name, :role)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);

        $stmt->execute();

        http_response_code(201); // 201 Created
        echo json_encode(['message' => 'User created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert data', 'details' => $e->getMessage()]);
    }
}

function handleEdit($pdo, $input) {
    try {
        $ID = $input['ID'] ?? null;
        $Name = $input['Name'] ?? null;
        $Role = $input['Role'] ?? null;

        // Validate input
        if (!$ID || !$Name || !$Role) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Missing required fields']);
            return;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE ID = :ID");
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if (!$existingUser) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'User not found']);
            return;
        }

        // Update user in the database
        $stmt = $pdo->prepare("UPDATE users SET Name = :Name, Role = :Role WHERE ID = :ID");
        $stmt->bindParam(':ID', $ID);
        $stmt->bindParam(':Name', $Name);
        $stmt->bindParam(':Role', $Role);
        $stmt->execute();

        http_response_code(200); // OK
        echo json_encode(['message' => 'User updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
}

function handleDelete($pdo, $input) {
    try {
        $ID = $input['ID'] ?? null;

        // Validate ID input
        if (!$ID) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Missing required ID']);
            return;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE ID = :ID");
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $existingUser = $stmt->fetch();

        if (!$existingUser) {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'User not found']);
            return;
        }

        // Delete user from the database
        $stmt = $pdo->prepare("DELETE FROM users WHERE ID = :ID");
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();

        http_response_code(200); // OK
        echo json_encode(['message' => 'User deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
}
?>