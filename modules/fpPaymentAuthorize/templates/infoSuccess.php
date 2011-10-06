<?php echo $form->renderFormTag(url_for('@fpPaymentPlugin_info?method=' . sfContext::getInstance()->getRequest()->getParameter('method'))) ?>
  <table>
    <?php echo $form ?>
  </table>
  <hr />
  <input type="submit" value="Send" />
</form>