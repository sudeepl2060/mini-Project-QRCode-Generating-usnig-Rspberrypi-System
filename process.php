<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'lab_objects');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate QR code for components
function generateQRCodeComponent($component_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT 
            colleges.name AS college_name,
            departments.name AS department_name,
            labs.name AS lab_name,
            systems.name AS system_name,
            components.name AS component_name
        FROM components 
        JOIN systems ON components.system_id = systems.id 
        JOIN labs ON systems.lab_id = labs.id 
        JOIN departments ON labs.department_id = departments.id 
        JOIN colleges ON departments.college_id = colleges.id
        WHERE components.id = ?
    ");
    $stmt->bind_param("i", $component_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    $stmt->close();

    if ($details) {
        $info = "College: " . $details['college_name'] . "\n" .
                "Department: " . $details['department_name'] . "\n" .
                "Lab: " . $details['lab_name'] . "\n" .
                "System: " . $details['system_name'] . "\n" .
                "Component: " . $details['component_name'];
        
        $encoded_info = urlencode($info);
        $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?data={$encoded_info}&size=150x150";
        
        // Save the QR code image locally
        $image_path = "qrcode_component_{$component_id}.png";
        file_put_contents($image_path, file_get_contents($qr_code_url));

        // Update the component's QR code path in the database
        $update_stmt = $conn->prepare("UPDATE components SET qr_code_path = ? WHERE id = ?");
        $update_stmt->bind_param("si", $image_path, $component_id);
        $update_stmt->execute();
        $update_stmt->close();
        return "<img src='$image_path' /><br><h3><a href='$image_path' download>Download QR Code</a></h3>$info";
    } else {
        return "Component not found.";
    }
}

// Function to generate QR code for trees
function generateQRCodeTree($tree_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT 
            colleges.name AS college_name,
            campus_parts.name AS campus_part_name,
            trees.name AS tree_name,
            trees.details
        FROM trees 
        JOIN campus_parts ON trees.campus_part_id = campus_parts.id 
        JOIN colleges ON campus_parts.college_id = colleges.id
        WHERE trees.id = ?
    ");
    $stmt->bind_param("i", $tree_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    $stmt->close();

    if ($details) {
        $info = "College: " . $details['college_name'] . "\n" .
                "Campus Part: " . $details['campus_part_name'] . "\n" .
                "Tree: " . $details['tree_name'] . "\n" .
                "Details: " . $details['details'];
        
        $encoded_info = urlencode($info);
        $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?data={$encoded_info}&size=150x150";
        
        // Save the QR code image locally
        $image_path = "qrcode_tree_{$tree_id}.png";
        file_put_contents($image_path, file_get_contents($qr_code_url));

        // Update the tree's QR code path in the database
        $update_stmt = $conn->prepare("UPDATE trees SET qr_code_path = ? WHERE id = ?");
        $update_stmt->bind_param("si", $image_path, $tree_id);
        $update_stmt->execute();
        $update_stmt->close();
        return "<img src='$image_path' /><br><h2><a href='$image_path' download>Download QR Code</a></h2><br>$info";
    } else {
        return "Tree not found.";
    }
}

// Handle AJAX requests
$action = $_GET['action'];
switch ($action) {
    case 'get_colleges':
        $result = $conn->query("SELECT id, name FROM colleges");
        if ($result->num_rows > 0) {
            echo '<option value="">Select College</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Colleges Available</option>';
        }
        break;
    case 'get_departments':
        $college_id = $_GET['college_id'];
        $stmt = $conn->prepare("SELECT id, name FROM departments WHERE college_id = ?");
        $stmt->bind_param("i", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<option value="">Select Department</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Departments Available</option>';
        }
        $stmt->close();
        break;
    case 'get_labs':
        $department_id = $_GET['department_id'];
        $stmt = $conn->prepare("SELECT id, name FROM labs WHERE department_id = ?");
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<option value="">Select Lab</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Labs Available</option>';
        }
        $stmt->close();
        break;
    case 'get_systems':
        $lab_id = $_GET['lab_id'];
        $stmt = $conn->prepare("SELECT id, name FROM systems WHERE lab_id = ?");
        $stmt->bind_param("i", $lab_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<option value="">Select System</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Systems Available</option>';
        }
        $stmt->close();
        break;
    case 'get_components':
        $system_id = $_GET['system_id'];
        $stmt = $conn->prepare("SELECT id, name FROM components WHERE system_id = ?");
        $stmt->bind_param("i", $system_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<option value="">Select Component</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Components Available</option>';
        }
        $stmt->close();
        break;
    case 'generate_qr_component':
        $component_id = $_GET['component_id'];
        echo generateQRCodeComponent($component_id);
        break;
    case 'get_campus_parts':
        $result = $conn->query("SELECT id, name FROM campus_parts");
        if ($result->num_rows > 0) {
            echo '<option value="">Select Part of Campus</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Parts of Campus Available</option>';
        }
        break;
    case 'get_trees':
        $campus_part_id = $_GET['campus_part_id'];
        $stmt = $conn->prepare("SELECT id, name FROM trees WHERE campus_part_id = ?");
        $stmt->bind_param("i", $campus_part_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo '<option value="">Select Tree</option>';
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else {
            echo '<option value="">No Trees Available</option>';
        }
        $stmt->close();
        break;
    case 'generate_qr_tree':
        $tree_id = $_GET['tree_id'];
        echo generateQRCodeTree($tree_id);
        break;
    default:
        echo "Invalid action.";
        break;
}

$conn->close();
?>
