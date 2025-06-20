<!-- HTML Document -->
<bbn-form :source="formData"
          :scrollable="false"
          :action="root + 'actions/database/create'"
          @success="onSuccess"
          @error="onError">
  <div class="bbn-grid-fields bbn-padding">
    <span class="bbn-label"><?= _("Name") ?></span>
    <bbn-input bbn-model="formData.name"/>
    <template bbn-if="charset?.length">
      <span class="bbn-label"><?= _("Charset") ?></span>
      <bbn-input bbn-model="formData.charset"
                 :disabled="true"/>
    </template>
    <template bbn-if="collation?.length">
      <span class="bbn-label"><?= _("Collation") ?></span>
      <bbn-input bbn-model="formData.collation"
                 :disabled="true"/>
    </template>
    <span class="bbn-label"><?= _("Options") ?></span>
    <bbn-switch bbn-model="formData.options"
                  :value="1"
                  :novalue="0"/>
  </div>
</bbn-form>
