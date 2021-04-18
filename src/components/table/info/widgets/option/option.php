<!-- HTML Document -->

<div class="bbn-padded">
  <div class="bbn-state-error bbn-padded bbn-c"
       v-if="!table.source.table_id">
    <?=_("No option")?>
  </div>
  <div class="bbn-grid-fields"
       v-else>
    <div style="width: 6em">
      <?=_("ID")?>
    </div>
    <div v-text="table.source.table_id"/>

    <div>
      <?=_("Title")?>
    </div>
    <bbn-editable v-model="table.source.option.text"
                  @save="saveTitle"
                  :required="true"/>
  </div>
</div>
