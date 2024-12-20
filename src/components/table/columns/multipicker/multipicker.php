<!-- HTML Document -->
 <!--  :action="source.root +'actions/column/validform'" -->

<div class="bbn-iblock">
  <bbn-form :source="formData">
    <div class="bbn-padding">
      <h3 class="bbn-c"><?= _("Displayed column(s) selector") ?></h3>
      <p>
        <?= _("Select the columns that should be shown when a row is referenced in another table") ?>
      </p>
      <div class="bbn-c">
        <bbn-values :source="textValues"
                    bbn-model="formData.dcolumns"
                    mode="dropdown"/>
      </div>
    </bbn-form>
  </div>
</div>