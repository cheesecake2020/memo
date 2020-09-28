<?php
require_once('header.php');
?>
<form action="" method="post">
    <textarea name="memo" cols="50" rows="10" placeholder="自由にメモを残してください"></textarea><br>
    <button type="submit">登録する</button>
</form>
<?php
require('dbconnect.php');

$statement = $db->prepare('INSERT INTO memos SET memo=?, created_at=NOW()');
$statement->bindParam(1,$_POST['memo']);
$statement->execute();

if(isset($_REQUEST['page'])&& is_numeric($_REQUEST['page'])){
    $page=$_REQUEST['page'];
}else{
    $page=1;
}

$start =5*($page - 1);
$memos = $db->prepare('SELECT * FROM memos ORDER BY id DESC LIMIT ?, 5');
$memos->bindParam(1,$start, PDO::PARAM_INT);
$memos->execute();
?>

<article>
<?php while ($memo = $memos->fetch()):?>
<p><a href="memo.php?id=<?php print($memo['id']); ?>"><?php print(mb_substr($memo['memo'],0,50)); ?></a></p>
<time><?php print($memo['created_at']); ?> </time>
<hr>
<?php endwhile; ?>

<?php if($page >= 2): ?>
<a href="index.php?page=<?php print($page-1); ?>"><?php print($page-1); ?>ページ目へ</a>
<?php endif;?>
|
<?php 
$count =$db->query('SELECT COUNT(*) as cnt FROM memos');
$count = $count->fetch();
$max_page = ceil($count['cnt']/5);
if($page < $max_page):
?>
<a href="index.php?page=<?php print($page+1); ?>"><?php print($page+1); ?>ページ目へ</a>
<?php endif;?>
</article>

</main>
</div>

</body>    
</html>
