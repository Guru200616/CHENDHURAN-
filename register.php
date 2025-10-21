<?php
/**
 * ===============================================
 * Project: Student Registration with Validation
 * College: Chendhuran College of Engineering and Technology
 * Course: B.E CSE
 * Description: Server-side Registration Handler with Validation and Security
 * ===============================================
 */

// Include database connection file
require_once 'database.php';

// Initialize variables for error handling
$errors = [];
$success = false;

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and retrieve form data
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Server-side validation
    
    // Validate Full Name
    if (empty($fullName)) {
        $errors[] = 'Full name is required';
    } elseif (strlen($fullName) < 3) {
        $errors[] = 'Full name must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullName)) {
        $errors[] = 'Full name should contain only letters and spaces';
    }
    
    // Validate Email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Validate Phone Number
    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Phone number must be exactly 10 digits';
    }
    
    // Validate Password
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    // Validate Confirm Password
    if (empty($confirmPassword)) {
        $errors[] = 'Please confirm your password';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }
    
    // If no errors, proceed with database insertion
    if (empty($errors)) {
        try {
            // Check if email already exists
            $checkEmailSql = "SELECT id FROM students WHERE email = :email";
            $checkStmt = $conn->prepare($checkEmailSql);
            $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                $errors[] = 'Email already registered. Please use a different email.';
            } else {
                // Hash the password for security (using bcrypt)
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                
                // Prepare SQL statement using prepared statements to prevent SQL injection
                $sql = "INSERT INTO students (full_name, email, phone, password) 
                        VALUES (:fullName, :email, :phone, :password)";
                
                $stmt = $conn->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $errors[] = 'Registration failed. Please try again.';
                }
            }
            
        } catch (PDOException $e) {
            // Log error for debugging (don't show to user for security)
            error_log("Registration Error: " . $e->getMessage());
            $errors[] = 'An error occurred during registration. Please try again later.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .status-container {
            text-align: center;
            padding: 20px;
        }
        .status-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .success-icon {
            color: #28a745;
        }
        .error-icon {
            color: #dc3545;
        }
        .status-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        .error-list {
            text-align: left;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .error-list li {
            margin-bottom: 5px;
            color: #721c24;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <div class="header">
                <h1>Student Registration</h1>
                <p>Chendhuran College of Engineering and Technology</p>
            </div>

            <div class="status-container">
                <?php if ($success): ?>
                    <div class="status-icon success-icon">âœ“</div>
                    <div class="status-title">Registration Successful!</div>
                    <p>Your account has been created successfully. You can now login with your credentials.</p>
                <?php else: ?>
                    <div class="status-icon error-icon">âœ—</div>
                    <div class="status-title">Registration Failed</div>
                    <?php if (!empty($errors)): ?>
                        <div class="error-list">
                            <strong>Please fix the following errors:</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <a href="index.html" class="back-btn">
                    <?php echo $success ? 'Register Another Student' : 'Back to Registration'; ?>
                </a>
            </div>

            <div class="footer">
                <p>&copy; 2025 Chendhuran College of Engineering and Technology | B.E CSE</p>
            </div>
        </div>
    </div>
</body>
</html>
