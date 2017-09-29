<?php include "header.php"; ?>
  <p><h1>Tisztelt <?=$name?>!</h1></p>
  <p><strong>Üzenetét sikeresen megkaptuk. Hamarosan válaszolni fogunk Önnek.</strong></p>
  <br>
  <p><strong>Témakör:</strong> <span class="hltext"><?php echo $temakor; ?></span></p>
  <p><strong>Tárgy:</strong> <span class="hltext"><?php echo $targy; ?></span></p>
  <br>
  <p><strong>Az Ön üzenete:</strong></p>
  <div class="message">
    <?php echo $uzenet; ?>
  </div>
<?php include "footer.php"; ?>
