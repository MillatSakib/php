<?php
include "connection.php";

$message = "";

// Handle update/delete actions before listing rows.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["delete_id"])) {
    $delete_id = (int)$_POST["delete_id"];
    $delete_sql = "DELETE FROM myDB.MyGuests WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
      $message = "Record deleted successfully";
    } else {
      $message = "Error deleting record: " . $conn->error;
    }
  }

  if (isset($_POST["update_id"])) {
    $update_id = (int)$_POST["update_id"];
    $firstname = $conn->real_escape_string($_POST["firstname"]);
    $lastname = $conn->real_escape_string($_POST["lastname"]);
    $email = $conn->real_escape_string($_POST["email"]);

    $update_sql = "UPDATE myDB.MyGuests SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id = $update_id";
    if ($conn->query($update_sql) === TRUE) {
      $message = "Record updated successfully";
    } else {
      $message = "Error updating record: " . $conn->error;
    }
  }
}

$edit_row = null;
// Load a row into the edit form when the user clicks Update.
if (isset($_GET["edit"])) {
  $edit_id = (int)$_GET["edit"];
  $edit_sql = "SELECT id, firstname, lastname, email FROM myDB.MyGuests WHERE id = $edit_id";
  $edit_result = $conn->query($edit_sql);
  if ($edit_result && $edit_result->num_rows === 1) {
    $edit_row = $edit_result->fetch_assoc();
  }
}

if ($message !== "") {
  echo $message . "<br>";
}

if ($edit_row) {
  echo "<h3>Update Record</h3>";
  echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
  echo "<input type='hidden' name='update_id' value='" . $edit_row["id"] . "'>";
  echo "Firstname: <input type='text' name='firstname' value='" . $edit_row["firstname"] . "' required><br>";
  echo "Lastname: <input type='text' name='lastname' value='" . $edit_row["lastname"] . "' required><br>";
  echo "Email: <input type='email' name='email' value='" . $edit_row["email"] . "'><br>";
  echo "<button type='submit'>Update</button>";
  echo " <a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>Cancel</a>";
  echo "</form><br>";
}

$sql = "SELECT id, firstname, lastname, email, reg_date FROM myDB.MyGuests";
$result = $conn->query($sql);

if ($result === false) {
  echo "Error: " . $conn->error;
} else {
  if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Reg Date</th><th>Actions</th></tr>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["id"] . "</td>";
      echo "<td>" . $row["firstname"] . "</td>";
      echo "<td>" . $row["lastname"] . "</td>";
      echo "<td>" . $row["email"] . "</td>";
      echo "<td>" . $row["reg_date"] . "</td>";
      echo "<td>";
      echo "<a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?edit=" . $row["id"] . "'>Update</a> ";
      echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' style='display:inline;'>";
      echo "<input type='hidden' name='delete_id' value='" . $row["id"] . "'>";
      echo "<button type='submit' onclick=\"return confirm('Delete this record?');\">Delete</button>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "0 results";
  }
}

$conn->close();
?>
