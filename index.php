<?php
  include 'includes/header.php';

  $stmt = $pdo->query("SELECT * FROM images ORDER BY id DESC");
  $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">
    <div class="row">
      <div class="my-5 text-center">
         <h1>Photo Gallery</h1>
      </div>

        <?php foreach ($images as $image) : ?>
            <div class="col-md-3 col-sm-4 col-12 pb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($image['filename']); ?>" class="card-img-top" alt="Image" style="height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($image['description']); ?></p>
                        <p class="card-text">Date: <?php echo date('jS F Y', strtotime(htmlspecialchars($image['upload_date']))); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>


<?php
include 'includes/footer.php';

 ?>
