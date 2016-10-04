<?php
require 'blogpost.php';

require 'Tags.php';

$databases = new Database;

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

//    $databases->query('SELECT * FROM posts');

// print_r($rows);\

if(@$_POST['delete']){
    $delete_id = $_POST['delete_id'];
    $databases->query('DELETE FROM blog_post WHERE id = :id');
    $databases->bind(':id',$delete_id);
    $databases->execute();
}

if(@$post['update']){
    $id = $post['id'];
    $title = $post['title'];
    $body = $post['post'];
    $date = $post['date'];

    $databases->query('UPDATE blog_post SET title = :title, post = :post, date = :date WHERE id = :id');
    $databases->bind(':post',$body);
    $databases->bind(':id',$id);
    $databases->execute();
}

if(@$post['submit']) {
    $title = $post['title'];
    $body = $post['post'];
    $date = $post['date'];


    $databases->query('INSERT INTO blog_post(title, post, date) VALUES(:title, :post, :date)');
    $databases->bind(':title', $title);
    $databases->bind(':post', $body);
    $databases->bind(':date', $date);
    $databases->execute();
    if ($databases->lastInsertId()) {
        echo '<p>Post Added!</p>';
    }
}

$tags = new tags();
$tags->query('SELECT * FROM blog_post');
$rows = $tags->resultset();



?>

<link rel="stylesheet" type="text/css" href="stylesheet.css" />

<div class="body">
<h1>Add Posts</h1>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

    <label>POST ID</label><br />
    <input type="text" name="id" placeholder="Specify Id" required/> <br /><br />

    <label>Post Title</label><br />
    <input type="text" name="title" placeholder="Add a Title..." required /><br /><br />

    <label>Post Date</label><br />
    <input type="date" name="date" required/><br /><br />

    <label>Post Body</label><br />
    <textarea name="post" required></textarea><br /><br />

    <input type="submit" name="submit" value="Submit"/>
</form>

<div class="posts">
<h1>Posts</h1>
<div>
    <?php foreach($rows as $row) : ?>
        <div>
            <h3>
                <?php echo $row['title'];?>
            </h3>
            <p>
                <?php echo $row['post']; ?>
            </p>
            <br />

            <span class="footer">
                <?php echo 'Date Created: ' .$row['date']; ?>
            </span>

            <p>
                <?php echo $row['tags']; ?>
            </p>

        </div>


        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="delete_id" value="<?php echo $row['id'] ?>">
            <input type="submit" name="delete" value="Delete" />
        </form>

    <?php endforeach; ?>
     </div>
  </div>

</div>
