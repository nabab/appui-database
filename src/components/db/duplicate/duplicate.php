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
    <span class="bbn-label"><?= _("Duplicate the data") ?></span>
    <bbn-switch bbn-model="formSource.with_data"
                :value="true"
                :novalue="false"/>
    <template bbn-if="options">
      <span class="bbn-label"><?= _("Options") ?></span>
      <bbn-switch bbn-model="formSource.options"
                  :value="true"
                  :novalue="false"/>
    </template>
  </div>
</bbn-form>