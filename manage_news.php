<?php
include_once('includes/functions.php');

if (!$loggedIn) {
    header("Location: page/404");
}

$title = 'Manage News';

// Fields and order for sorting. I removed the options to sort by title and news below because why would anyone need that?
isset($_GET['field']) ? $field = $_GET['field'] : $field = 'datetime';
isset($_GET['order']) ? $order = $_GET['order'] : $order = 'DESC';

// If the action add, create, edit, update or hide is specified as a GET, then do that. If not, display all news items
if (isset($_GET['action'])) {
    // Can't have html before these or header('location:') wont work 
    if ($_GET['action'] == "Create" || $_GET['action'] == "Hide" || $_GET['action'] == "Unhide" || $_GET['action'] == "Update") {
        if ($_GET['action'] == "Create") {
            // Means text can have 's or "s. Without it, news comes in blank
            $title = mysqli_real_escape_string($db, $_GET['title']);
            $text = mysqli_real_escape_string($db, $_GET['text']);
            $inuse = (isset($_GET['inuse']))? 0: 1; // If set, it's checked to hide post
            $post_user = $_COOKIE['user'];
            mysqli_query($db, "INSERT INTO news_items (username,title,text,datetime,inuse) 
                VALUES('".$post_user."', '".$title."', '".$text."', NOW(), '".$inuse."')");
            header('Location:manage_news.php?success=added');
            
        } else if ($_REQUEST['action'] == "Hide") {
            // Hide news
            if (isset($_REQUEST['newsId'])) {
                mysqli_query($db, "UPDATE news_items SET inuse='0' WHERE id='".$_REQUEST['newsId']."' LIMIT 1");
                header('Location:manage_news.php?success=hidden');
            }
            else {
                echo "No id provided";
            }
        } 
        else if ($_REQUEST['action'] == "Unhide") {
            // Hide news
            if (isset($_REQUEST['newsId'])) {
                mysqli_query($db, "UPDATE news_items SET inuse='1' WHERE id='".$_REQUEST['newsId']."' LIMIT 1");
                header('Location:manage_news.php?success=unhidden');
            }
            else {
                echo "No id provided";
            }
        } else if ($_GET['action'] == "Update") {
            if (isset($_GET['newsId'])) {
                $title = mysqli_real_escape_string($db, $_GET['title']);
                $text = mysqli_real_escape_string($db, $_GET['text']);
                $inuse = (isset($_GET['inuse']))? 0: 1; // If set, it's checked to hide post
                $post_user = $_COOKIE['user'];
                
                mysqli_query($db, "UPDATE news_items SET username='".$post_user."', datetime=NOW(), title='".$title."', text='".$text."', inuse='".$inuse."' WHERE id='".$_GET['newsId']."' LIMIT 1");
                echo mysqli_error($db);
                header('Location:manage_news.php?success=edited');
            }
        }
    }
    // Need HTML to add or edit a news item
    else {
        addHeader();
?>
        <h2>
            Manage News
            <small><small><a href="manage_news.php">News Menu</a></small></small>
        </h2>
        <p>
            News must be formatted using HTML. Instructions can be found <a href="files/usefuldocs/Committee_Page_Instructions.doc" target="_blank">here</a>.
            <br> Note to WebM - do iframe's for youtube embed in phpmyadmin.
        </p>
        
        <?php
        if ($_GET['action'] == "add") {
?>
            <h4>Add News item</h4>
            <form action="manage_news.php" method="GET" role="form">
                <div class="form-group">
                    <input class="form-control" type="text" name="title" placeholder="Title" autofocus>
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="14" placeholder="HTML formatted news in here" name="text"></textarea>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="inuse">
                        Check to hide news item
                    </label>
                </div>
                <button class="btn btn-primary" type="submit" name="action" value="Create">Add News</button>
            </form>          
<?php
        } else if ($_GET['action'] == "Edit") {
            // Get news item to edit
            if (isset($_GET['newsId'])) {
                $news_query = mysqli_query($db, "SELECT * FROM news_items WHERE id='".$_GET['newsId']."'");
                $news = mysqli_fetch_array($news_query, MYSQL_ASSOC);
?>  
                <h4>Edit news item</h4>
                <form action="manage_news.php" method="GET" role="form">
                    <div class="form-group">
                        <input type="hidden" name="newsId" value="<?= $news['id']; ?>">
                        <input class="form-control" type="text" name="title" placeholder="Title" autofocus value="<?= $news['title']; ?>">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="14" placeholder="HTML formatted news in here" name="text"><?= $news['text']; ?></textarea>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" <?= ($news['inuse']==0)? 'checked': ''; ?> name="inuse">
                            Check to hide news item
                        </label>
                    </div>
                    <button class="btn btn-default" type="submit" name="action" value="Update">Update News</button>
                </form>
<?php
            } 
            else {
                echo '<b>Error:</b> No news item selected for editing';
            }
        }
    }
}
// Show all the news
else {
    addHeader();
?>
    <h2>
        Manage News
    </h2>
    <p>
        News must be formatted using HTML. Instructions can be found <a href="files/usefuldocs/Committee_Page_Instructions.doc" target="_blank">here.</a>
    </p>
<?php
    if (isset($_GET['success'])) { // If an action has been successful, a msg box will be displayed
        echo '
        <h4 style="color:green">
            News item '.$_GET['success'].' successfully!
        </h4>';
    }
?>
    <h4>
        <a href="manage_news.php?action=add">
            + Add News
        </a>
    </h4>
    <style>
        th {
            text-align:center;
        }
        td:nth-child(2) {
            text-align: center;
            min-width: 80px;
        }
        @media (max-width: 768px) {
            table  { display: block; padding: 0;}
            table  td, table  th { display: inline-block; }
            td:nth-child(1) {
                width: 10%;
            }
            td:nth-child(2) {
                width: 40%;
            }
            td:nth-child(3){
                width: 50%;
            }
            td:nth-child(4){
                width: 100%;
            }
        }
    </style>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><i class="fa fa-cog"></i></th>
                <th>Date <a href="manage_news.php?field=datetime&order=ASC">
                        <i <?= ($order == 'ASC') ? 'style="color:red"' : '';?> class="fa fa-angle-up"></i>
                    </a>
                    <a href="manage_news.php?field=datetime&order=DESC">
                        <i <?= ($order == 'DESC') ? 'style="color:red"' : '';?> class="fa fa-angle-down"></i>
                    </a>
                </th>
                <th>Title</th>
                <th>News</th>
            </tr>
        </thead>
        <tbody>
<?php    
    // List all news with edit/hide buttons
    $news_query = mysqli_query($db, "SELECT *,DATE_FORMAT(datetime,'%e/%m/%y %H:%i') as formatted_date FROM news_items ORDER BY ".$field." ".$order." ");
    while ($news = mysqli_fetch_array($news_query, MYSQL_ASSOC)) {
        $newstext = (strlen($news['text']) > 200)? substr($news['text'], 0, 199).'...': $news['text'];
        
        echo '
            <tr>
                <td>
                    <a href="manage_news.php?action=Edit&newsId='.$news['id'].'" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>';
        if ($news['inuse'] == 1) {
            echo '
                    <a style="color:green" href="manage_news.php?action=Hide&newsId='.$news['id'].'" title="Click to hide">
                        <i class="fa fa-eye"></i>
                    </a>';
        } else {
            echo '
                    <a style="color:red" href="manage_news.php?action=Unhide&newsId='.$news['id'].'" title="Click to unhide">
                        <i class="fa fa-eye-slash"></i>
                    </a>';
        }
        echo '
                </td>
                <td>'.$news['formatted_date'].'</td>
                <td>'.$news['title'].'</td>
                <td>'.smilify($newstext, 0).'</td>
            </tr>';
    }
    echo '
        </tbody>
    </table>';
}
addFooter();

?>