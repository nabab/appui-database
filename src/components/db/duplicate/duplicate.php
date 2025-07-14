<bbn-form class="appui-database-db-duplicate"
          :action="action"
          :source="formSource"
          @success="onSuccess"
          @error="onError">
  <div class="bbn-grid-fields bbn-padding">
    <span class="bbn-label"><?= _("Selected database") ?></span>
    <span bbn-text="database"/>
    <span class="bbn-label"><?= _("New database name") ?></span>
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