<?php
$filename = "users.txt";

function registerUser($username, $password)
{
    global $filename;

    $salt = bin2hex(random_bytes(8));
    $hashedPassword = hash('sha256', $password . $salt);

    $userData = $username . "," . $salt . "," . $hashedPassword . PHP_EOL;


    file_put_contents($filename, $userData, FILE_APPEND);

    echo "[+] User '$username' registered successfully!\n";
}
function loginUser($username, $password) {
    global $filename;
    
    if (!file_exists($filename)) {
        echo "[-] Database file does not exist.\n";
        return;
    }
    
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        list($storedUser, $storedSalt, $storedHash) = explode(",", $line);
        
        if ($storedUser === $username) {
            
    
            $checkHash = hash('sha256', $password . $storedSalt);
            
            if ($checkHash === $storedHash) {
                echo "[+] Login SUCCESSFUL! Welcome, $username.\n";
                return;
            } else {
                echo "[-] Login FAILED: Incorrect password.\n";
                return;
            }
        }
    }
    

    echo "[-] Login FAILED: User not found.\n";
}


echo "=== Password Hashing Authentication System ===\n\n";

registerUser("alice", "MySecret123");
registerUser("bob", "Password456");

echo "\n--- Testing Login ---\n";
loginUser("alice", "MySecret123");

loginUser("alice", "WrongPassword");


loginUser("charlie", "AnyPassword");



// 1. User Registration (registerUser)
// Salt Generation: Generates a unique 16-character hexadecimal salt for every new user using
// Password Hashing: Concatenates the plain-text password with the generated salt ($password . $salt) and hashes the result using hash('sha256', ...)
// Data Storage: Formats the user record as username,salt,hashedPassword and appends it to users.txt using file_put_contents() with the FILE_APPEND flag

// ==============================================================================================================================================================

// 2. User Authentication (loginUser)
// File Reading: Reads all lines from users.txt using file().   
//Parsing Data: Uses explode(',', $line) to separate the stored username, salt, and hashed password 
// Credential Verification: When a matching username is found, it concatenates the entered password with the user's stored salt and hashes it with SHA-256.
// Hash Comparison: Compares the newly computed hash with the stored hash. If they match, access is granted (Login SUCCESSFUL); otherwise, it fails (Login FAILED).


?>