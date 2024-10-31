<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Exit for preflight request
}

require 'db.php'; // Database connection

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        handleRegister($pdo, $input);
        break;
    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

function handleRegister($pdo, $input) {
    try {
        if (empty($input['Username']) || empty($input['Email']) || empty($input['Password']) || empty($input['Name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'All fields (Username, Email, Password, Name) are required']);
            exit;
        }

        $username = $input['Username'];
        $email = $input['Email'];
        $password = $input['Password'];
        $name = $input['Name'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE Username = :username OR Email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(409); // Conflict status code
            echo json_encode(['error' => 'Username or email already exists']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (Username, Email, Password, Name) VALUES (:username, :email, :password, :name)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        http_response_code(201); // Created status code
        echo json_encode(['message' => 'User registered successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred during registration', 'details' => $e->getMessage()]);
    }
}