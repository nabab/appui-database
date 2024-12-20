<div class="bbn-w-100">
  <bbn-form bbn-if="editTable">
    <label><?= _("Column name") ?></label>
    <bbn-input bbn-model="source.name"/>

    <div class="bbn-grid-full" bbn-if="source.name">
      <bbn-checkbox label="<?= _("Configure the option") ?>"
                    bbn-model="editOption"/>
    </div>
  </bbn-form>
  <bbn-form bbn-else-if="editOption"
            :source="option">

    <label><?= _("Column title") ?></label>
    <bbn-input bbn-model="option.text"/>

    <label><?= _("Column editor") ?></label>
    <bbn-input bbn-model="option.editor"/>

    <label><?= _("Column component") ?></label>
    <bbn-input bbn-model="option.component"/>

  </bbn-form>
  <div bbn-else>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          bbn-if="option && (option.text !== option.code)"
          bbn-text="option.text"/>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          bbn-text="source.name"/>
  </div>
</div>