<?php
session_start();
require_once '../include/connection.php';
require_once '../include/message.php';

$id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['todo']) && empty($_POST['id'])) {
        $todo = $_POST['todo'];
        if (!empty($todo)) {
            if (insertTodo($todo, $conn)) {
                 $_SESSION['message'] = "New Todo Added Successfully !";
                header("Location: ../index.php");
                exit(0);
            } else {

                $_SESSION['message_error'] = "Failed to Add Todo";
                header("Locatiuon: ../index.php");
                exit(0);
            }
        } else {
            $_SESSION['message_error'] = "Please Add Todo";
            header("Locatiuon: ../index.php");
            exit(0);
        }
    }

    if (isset($_POST['todo']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $todo = $_POST['todo'];
        if (updateTodo($id, $todo, $conn)) {
            $_SESSION['message'] = " Todo Updated Successfully !";
            header("Location: ../index.php");
            exit(0);
        } else {
            $_SESSION['message_error'] = "Failed to update Todo";
            header("Locatiuon: ../index.php");
            exit(0);
        }
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if (deleteTodo($id, $conn)) {
        $_SESSION['message'] = " Todo deleted Successfully !";
        header("Location: ../index.php");
        exit(0);
    } else {
        $_SESSION['message_error'] = "Failed to delete Todo";
        header("Locatiuon: ../index.php");
        exit(0);
        
    }
}

if (isset($_GET['marking_id'])) {
    $id = $_GET['marking_id'];
    if (completeTodo($id, $conn)) {
        $_SESSION['message'] = " Todo completed Successfully !";
        header("Location: ../index.php");
        exit(0);
    } else {
        $_SESSION['message_error'] = "Failed to Todo complete";
        header("Locatiuon: ../index.php");
        exit(0);
        
    }
}

function insertTodo($todo, $conn)
{
    $query = $conn->prepare("INSERT INTO todos (todo) VALUES (?)");
    $query->bind_param("s", $todo);
    return $query->execute();
}

function updateTodo($id, $todo, $conn)
{
    $query = $conn->prepare("UPDATE todos SET todo = ?, updated = NOW() WHERE id = ?");
    $query->bind_param("si", $todo, $id);
    
    if (!$query->execute()) {
        error_log("Error updating todo: " . $query->error);
        return false;
    }
    
    return true;
}

function deleteTodo($id, $conn)
{
    $query = $conn->prepare("DELETE FROM todos WHERE id = ?");
    $query->bind_param("i", $id);
    return $query->execute();
}

function completeTodo($id, $conn)
{
    $query = $conn->prepare("UPDATE todos SET status = 1, updated = NOW() WHERE id = ?");
    $query->bind_param("i", $id);
    return $query->execute();
}
?>
