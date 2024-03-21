<div class="bbn-w-100">
  <bbn-form v-if="editTable">
    <label><?= _("Column name") ?></label>
    <bbn-input v-model="source.name"/>

    <div class="bbn-grid-full" v-if="source.name">
      <bbn-checkbox label="<?= _("Configure the option") ?>"
                    v-model="editOption"/>
    </div>
  </bbn-form>
  <bbn-form v-else-if="editOption"
            :source="option">

    <label><?= _("Column title") ?></label>
    <bbn-input v-model="option.text"/>

    <label><?= _("Column editor") ?></label>
    <bbn-input v-model="option.editor"/>

    <label><?= _("Column component") ?></label>
    <bbn-input v-model="option.component"/>

  </bbn-form>
  <div v-else>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          v-if="option && (option.text !== option.code)"
          v-text="option.text"/>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          v-text="source.name"/>
  </div>
</div>