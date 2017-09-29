<div class="des-v coffee">
  <img src="<?=IMG?>/coffee.svg" alt="Kávé">
</div>
<div class="des-v levcer">
  <img src="<?=IMG?>/level-ceruza.svg" alt="Ajánlat">
</div>
<div class="des-v klavi">
  <img src="<?=IMG?>/klaviatura.svg" alt="Klaviatúra">
</div>
<div class="group-holder">
  <div class="heading">
    <h3>Bátran vegye fel a kapcsolatot velünk, bármilyen kérdése merül fel.</h3>
  </div>
  <form id="mailsend" action="" method="post">
    <div class="flxtbl">
      <div class="name">
        <label for="name">Név *</label>
        <div class="form-input-holder">
          <input type="text" id="name" name="name" class="form-control" value="">
        </div>
      </div>
      <div class="email">
        <label for="email">E-mail cím *</label>
        <div class="form-input-holder">
          <input type="text" id="email" name="email" class="form-control" value="">
        </div>
      </div>
      <div class="phone">
        <label for="phone">Telefonszám</label>
        <div class="form-input-holder">
          <input type="text" id="phone" name="phone" class="form-control" value="">
        </div>
      </div>
      <div class="temakor">
        <label for="temakor">Témakör *</label>
        <div class="form-input-holder">
          <select class="form-control" name="temakor" id="temakor">
            <option value="Ajánlatkérés" selected="selected">Ajánlatkérés</option>
            <option value="Információ">Információ</option>
            <option value="Hibajelentés">Hibajelentés</option>
          </select>
        </div>
      </div>
      <div class="tema">
        <label for="targy">Üzenet tárgya *</label>
        <div class="form-input-holder">
          <input type="text" id="targy" name="targy" class="form-control" value="">
        </div>
      </div>
      <div class="uzenet">
        <label for="uzenet">Üzenete*</label>
        <div class="form-input-holder">
          <textarea name="uzenet" id="uzenet" class="form-control"></textarea>
        </div>
      </div>
      <div class="recaptcha">
        <div class="g-recaptcha" data-sitekey="<?=CAPTCHA_SITE_KEY?>"></div>
      </div>
      <div class="btns">
        <div id="mail-msg" style="display: none;">
          <div class="alert"></div>
        </div>
        <button type="button" id="mail-sending-btn" onclick="ajanlatkeresKuldes();">Üzenet küldése</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
var mail_sending_progress = 0;
var mail_sended = 0;
function ajanlatkeresKuldes()
{
  if(mail_sending_progress == 0 && mail_sended == 0){
    jQuery('#mail-sending-btn').html('<?php echo __('Küldés folyamatban', 'Avada'); ?> <i class="fa fa-spinner fa-spin"></i>').addClass('in-progress');
    jQuery('#mailsend .missing').removeClass('missing');

    mail_sending_progress = 1;
    var mailparam  = jQuery('#mailsend').serializeArray();
    jQuery.post(
      '<?php echo admin_url('admin-ajax.php'); ?>?action=contact_form',
      mailparam,
      function(data){
        var resp = jQuery.parseJSON(data);
        console.log(resp);
        if(resp.error == 0) {
          mail_sended = 1;
          jQuery('#mail-sending-btn').html('<?php echo __('Az üzenete elküldve', 'Avada'); ?> <i class="fa fa-check-circle"></i>').removeClass('in-progress').addClass('sended');
        } else {
          jQuery('#mail-sending-btn').html('<?php echo __('Üzenet küldése', 'Avada'); ?>').removeClass('in-progress');
          jQuery('#mail-msg').show();
          jQuery('#mail-msg .alert').html(resp.msg).addClass('alert-danger');
          mail_sending_progress = 0;
          if(resp.missing != 0) {
            jQuery.each(resp.missing_elements, function(i,e){
              jQuery('#mailsend #'+e).addClass('missing');
            });
          }
        }
      }
    );
  }
}
</script>
