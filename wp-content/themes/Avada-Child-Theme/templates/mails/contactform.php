<?php include "header.php"; ?>
  <p><strong>Témakör:</strong> <span class="hltext"><?php echo $temakor; ?></span></p>
  <p><strong>Tárgy:</strong> <span class="hltext"><?php echo $targy; ?></span></p>
  <br>
  <p><strong>Üzenet tartalma:</strong></p>
  <div class="message">
    <?php echo $uzenet; ?>
  </div>
  <div class="message-from">
    - <strong><?php echo $name; ?></strong>
    | E-mail: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
    <?php if ($phone != ''): ?>
    | Telefon: <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
    <?php endif; ?>
  </div>
<?php include "footer.php"; ?>
