<?php echo $form->renderFormTag(url_for('@fpPaymentPlugin_info?type=' . sfContext::getInstance()->getRequest()->getParameter('type'))) ?>
  <table>
    <?php echo $form ?>
  </table>
  <hr />
  <input type="submit" value="Send" />
</form>