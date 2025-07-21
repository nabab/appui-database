<bbn-form class="appui-database-table-rename"
          :action="action"
          :source="formSource"
          @success="onSuccess"
          @error="onError">
  <div class="bbn-grid-fields bbn-padding">
    <span class="bbn-label"><?= _("Current name") ?></span>
    <span bbn-text="table"/>
    <span class="bbn-label"><?= _("New name") ?></span>
    <bbn-input bbn-model="formSource.name"/>
    <template bbn-if="options">
      <span class="bbn-label"><?= _("Options") ?></span>
      <bbn-switch bbn-model="formSource.options"
                  class="bbn-right-sspace"
                  :value="true"
                  :novalue="false"/>
    </template>
  </div>
</bbn-form>