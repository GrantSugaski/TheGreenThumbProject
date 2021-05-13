<?php

function authenticateUserPermissions($db, $id, $token) {
    // Get auth token
    $sql = "select Token from users where ID = :bind_id ;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_id', $id);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($token == $results[0]) {
        return true;
    } else {
        return false;
    }
}

function createUser($db, $email, $password, $role, $firstName, $lastName, $organization, $description) {
    // Check if user already exists with email
    $sql = "select * from users where Email = :bind_email;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_email', $email);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        return false;
    }

    // Hash password
    $hashpass = password_hash($password, PASSWORD_DEFAULT);

    // Hash token
    $token = password_hash(date("Y-m-d H:i:s"), PASSWORD_DEFAULT);

    // Create user account
    $sql = "insert into users (Email, Password, Role, Token) values (:bind_email, :bind_pass, :bind_role, :bind_token);";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_email', $email);
    $stmt->bindparam(':bind_pass', $hashpass);
    $stmt->bindparam(':bind_role', $role);
    $stmt->bindparam(':bind_token', $token);
    $stmt->execute();

    // Get the user id
    $sql = "select ID from users where Email = :bind_email;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_email', $email);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $id = $results[0]['ID'];

    // Create customer or producer profile
    if ($role == "Customer") {
        $sql = "insert into customers (ID, FirstName, LastName) values (:bind_id, :bind_firstname, :bind_lastname);";
        $stmt = $db->prepare($sql);
        $stmt->bindparam(':bind_id', $id);
        $stmt->bindparam(':bind_firstname', $firstName);
        $stmt->bindparam(':bind_lastname', $lastName);

        $stmt->execute();
    } else if ($role == "Producer") {
        $sql = "insert into producers (ID, Organization, Description) values (:bind_id, :bind_org, :bind_desc);";
        $stmt = $db->prepare($sql);
        $stmt->bindparam(':bind_id', $id);
        $stmt->bindparam(':bind_org', $organization);
        $stmt->bindparam(':bind_desc', $description);

        $stmt->execute();
    }

    return true;
}

function getAuthorization($db, $email) {
    // Get user ID
    $sql = "select ID from users where Email = :bind_email;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_email', $email);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Insert user authentication into database
    if (count($results) > 0) {
        $id = $results[0]['ID'];

        $sql = "select Role, Token from users where ID = :bind_uid;";
        $stmt = $db->prepare($sql);
        $stmt->bindparam(':bind_uid', $id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

function getUser($db, $token, $role) {
    // Get user ID
    $sql = "select ID from users where Token = :bind_token;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_token', $token);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $id = $results[0]['ID'];

    // Get user
    $sql = "";

    if ($role == "Customer") {
        $sql = "select Email, Role, FirstName, LastName from users u join customers c where u.ID = c.ID and u.ID = :bind_id;";
    } else if ($role == "Producer") {
        $sql = "select Email, Role, Organization, Description from users u join producers p where u.ID = p.ID and u.ID = :bind_id;";
    }

    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_id', $id);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function updateUser($db, $email, $password, $firstName, $lastName, $organization, $description) {
    // Get UID
    $sql = "select ID from users where Token = :bind_token";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_token', $_SESSION['AUTH_TOKEN']);
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $id = $results[0]['ID'];
    
    // Build update statement
    $sql = "update users set ";

    $total = 0;

    if (!empty($email)) {
        $sql .= "Email = '".htmlspecialchars($email)."'";

        $total += 1;
    }

    if (!empty($password)) {
        if ($total > 0) {
            $sql .= ", ";
        }
        
        $sql .= "Password = '".htmlspecialchars(password_hash($password, PASSWORD_DEFAULT))."'";

        $total += 1;
    }

    $sql .= " where ID = ".$id.";";

    // Update table
    if ($total > 0) {
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    if ($_SESSION['AUTH_ROLE'] == "Customer") {
        // Build update statement
        $sql = "update customers set ";

        $total = 0;
        
        if (!empty($firstName)) {
            if ($total > 0) {
                $sql .= ", ";
            }
            
            $sql .= "FirstName = '".$firstName."'";
    
            $total += 1;
        }
    
        if (!empty($lastName)) {
            if ($total > 0) {
                $sql .= ", ";
            }
            
            $sql .= "LastName = '".$lastName."'";
    
            $total += 1;
        }

        $sql .= " where ID = ".$id.";";

        // Update table
        if ($total > 0) {
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
    } else if ($_SESSION['AUTH_ROLE'] == "Producer") {
        // Build update statement
        $sql = "update producers set ";

        $total = 0;
        
        if (!empty($organization)) {
            if ($total > 0) {
                $sql .= ", ";
            }
            
            $sql .= "Organization = '".$organization."'";
    
            $total += 1;
        }
    
        if (!empty($description)) {
            if ($total > 0) {
                $sql .= ", ";
            }
            
            $sql .= "Description = '".$description."'";
    
            $total += 1;
        }

        $sql .= " where ID = ".$id.";";

        // Update table
        if ($total > 0) {
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
    }
}

function verifyCredentials($db, $email, $password) {
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    $sql = "select password from users where email = :bind_email;";
    $stmt = $db->prepare($sql);
    $stmt->bindparam(':bind_email', $email);
    
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0 && password_verify($password, $results[0]['password'])) {
        return True;
    } else {
        return False;
    }   
}

?>