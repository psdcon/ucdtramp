<?php
include_once('includes/functions.php');

if (isset($_GET['name'])) {
    $pageURL = $_GET['name'];
    $pageSQL = mysqli_query($db, "SELECT * FROM pages WHERE pageurl='$pageURL' ");
    $pageResult = mysqli_fetch_array($pageSQL);
    $editPermission = false;
    
    if (!$pageResult) {
        header("Location: //ucdtramp.com/page/404");
    }
    
    if ($pageResult['readperm'] > 0 && !$loggedIn) {
        header("Location: //ucdtramp.com/page/404");
    }
    else if ($loggedIn) {
        // TODO: Not sure if I want to use these anymore...
        $lastEditUser = $pageResult['lasteditu']; //user to last edit page
        $lastEditTime = $pageResult['lasteditt']; //time it was last edited
        
        $editPermission = true;
        if ($pageResult['editperm'] > 2 && $userPosition != 'Webmaster') {
            $editPermission = false;
        }
    }
    
    $title = $pageResult['pagetitle']; //Used in header.php to set title    
    addHeader();

    // if the user has editPermissions, add the editor
    if($editPermission){
        echo '
        <div class="btn-edit-page">
            <button class="btn btn-default js-editor-start">Edit this page</button>
            <div class="btn-group js-editor-running">
                <button class="btn btn-default js-btn-cancel">Cancel</button>
                <button class="btn btn-primary js-btn-save" style="margin-left: -1px;">Save</button>
            </div>
        </div>
        <div id="editor" class="full-width"><!-- Code editor appears in here --></div>';
    }
    
    echo '
    <div id="page-content" data-pageid="'.$pageURL.'">'.
        $pageResult['pagecontent'].'
    </div>';

    addFooter();
    // only add the required js if the editor has been added
    if($editPermission){
        echo '
            <script src="dist/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
            <script src="dist/js/page.js"></script>
        ';
    }
}
// Ajax page backup and update
else if(isset($_POST['action']) && $_POST['action'] == 'pageUpdate'){
    $pageURL = $_POST['pageurl'];
    $pagecontent = mysqli_real_escape_string($db,$_POST['new_content']);
    $lastEditUser = $_COOKIE['user'];
    $lastEditTime = time();
    $columsToBackup = '`year`, `type`, `pagetitle`, `pageurl`, `pagecontent`, `eventname`, `readperm`, `editperm`, `lasteditu`, `lasteditt`';

    $backupQuery = mysqli_query($db, "INSERT INTO pages_history ($columsToBackup) SELECT $columsToBackup FROM pages WHERE pageurl='$pageURL'");
    $updateQuery = mysqli_query($db, "UPDATE pages SET pagecontent='$pagecontent', lasteditu='$lastEditUser', lasteditt=$lastEditTime WHERE pageurl='$pageURL'");
    if (!$backupQuery || !$updateQuery) // if either failed, error out
        die(mysqli_error($db));
    else
        die(); // If nothing went wrong, say nothing
}
else { //for debugin
    addHeader(); 
?>
    <style>
        table {border-collapse: collapse;}
        td, th {
          border: 1px solid #999;
          padding: 0.5rem;
        }
    </style>
<?php
    // $sql = "SELECT * FROM pages WHERE readperm <2 AND page-content LIKE '%\%%' ORDER BY TYPE ASC , YEAR ASC ";
    $sql = "SELECT * FROM pages ORDER BY type ASC";
    
    $result = mysqli_query($db, $sql);
    echo '
        <p>
            <strong>This is a test page. You shouldnt really be here...</strong><br>
            Currently showing '.mysqli_num_rows($result).' pages
        </p>
        <pre>'.$sql.'</pre>
        <table>
            <tr><th>Id</th><th>Type</th><th>Year</th><th>Pageurl</th><th>Pagetitle</th></tr>';
    while ($row = mysqli_fetch_array($result)) {
        echo '
        <tr>
            <td>'.$row['id'].'.</td>
            <td>'.$row['type'].'</td>
            <td>'.$row['year'].'</td>
            <td>'.$row['pageurl'].'</td>
            <td><a href="page/'.$row['pageurl'].'" target="_blank">'.$row['pagetitle'].'</a></td>
        </tr>';
    }
    echo '</table>';
    
    addFooter();
}
?>