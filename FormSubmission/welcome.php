<html>

    <body>
        My query is <?php 
        $name =  $_POST["name"]; 
        $email = $_POST["email"];
        echo "INSERT INTO MYDB.TABLE(name,email) VALUES($name , $email)";
         ?>
</body>
</html>